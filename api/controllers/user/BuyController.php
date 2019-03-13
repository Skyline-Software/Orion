<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 15:14
 */

namespace api\controllers\user;


use api\forms\BuyCardForm;
use api\forms\BuyCertForm;
use common\traits\loadTrait;
use core\entities\buy\Buy;
use core\entities\card\Card;
use core\entities\card\CardType;
use core\entities\card\UserCard;
use core\entities\cert\Cert;
use core\entities\cert\Item;
use core\entities\cert\UserCert;
use core\entities\user\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class BuyController extends Controller
{
    use loadTrait;

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'optional' => ['*']
        ];

        return $behaviours;
    }

    public function actionCert()
    {
        $user = $this->loadUser();
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        $form = new BuyCertForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){

            $buy = Cert::create(new Item($form->nominal, rand(111111111111,999999999999)), $user->id);
            $buy->save();

            return ['status'=>'ok','result'=>$buy];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];


    }

    public function actionCard()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        $form = new BuyCardForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){
            $card = Card::find()->where(['type_id'=>$form->card_type_id,'buyed'=>Card::FREE])->andFilterWhere(['is', 'user_id', null])->one();
            if(!$card){
                return ['status'=>'fail','code'=>'NO_FREE_CARDS'];
            }

            $card->buy($user->id);
            $card->setupDelivery($form->name,$form->phone,$form->address);
            $card->save();

            if(!empty($form->name) || !empty($form->phone) || !empty($form->address))
            {
                $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $html = '<html><body>';
                $html .= "Здравствуйте, администратор!";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "Необходимо доставить карту с номером <a href=\"https://backend.ulitsarubinshteina.ru/manage/card/card/view?id=$card->id\">$card->number</a> по адресу:";
                $html .= "<br>";
                $html .= implode('<br>',$card->delivery);
                $html .= "<hr>";
                $html .= "С уважением, 3d-робот автоматической доставки собщений.";
                $html .= '</body></html>';

                $sent = mail(Yii::$app->params['adminEmail'], 'Заказана доставка карты', $html,$headers);
                mail(Yii::$app->params['developerEmail'], 'Заказана доставка карты', $html,$headers);
                return ['status'=>'ok','result'=>$card->number,'message'=>$sent];
            }

            return ['status'=>'ok','result'=>$card->number];
        }
        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];

    }

}