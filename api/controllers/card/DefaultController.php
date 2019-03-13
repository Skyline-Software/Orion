<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 29.11.2018
 * Time: 19:56
 */

namespace api\controllers\card;


use api\forms\CardNumberForm;
use common\traits\loadTrait;
use core\entities\card\Card;
use core\entities\sales\Sales;
use yii\data\ArrayDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class DefaultController extends Controller
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

    public function actionIndex()
    {
        $user = $this->loadUser();
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        return ['status'=>'ok','result'=> $user->cards];
    }

    public function actionActivate()
    {
        $user = $this->loadUser();
        if(!$user){
            ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }
        $card = $this->load();

        if(is_array($card)){
            return $card;
        }

        if($card->status == 0){
            return ['status'=>'fail','code'=> 'CARD_EXPIRED'];
        }

        if(!is_null($card->activated)){
            return ['status'=>'fail','code'=> 'CARD_ALREADY_ACTIVATED'];
        }
        $card->activate($user->id);
        $card->save();

        return ['status'=>'ok','result'=> ''];
    }

    public function actionDelete(){
        $card = $this->load();
        if(is_array($card)){
            return $card;
        }
        if($card->status == 0){
            return ['status'=>'fail','code'=> 'CARD_ALREADY_DEACTIVATED'];
        }
        $card->deactivate();
        $card->save();

        return ['status'=>'ok','result'=> ''];
    }

    public function actionDiscounts(){
        $card = $this->load();
        if(is_array($card)){
            return $card;
        }
        $offset = ArrayHelper::getValue($_POST,'offset');
        $count = ArrayHelper::getValue($_POST,'count');

        $discounts = new ArrayDataProvider([
            'allModels' => Sales::find()->where(['card_id'=>$card->id])->limit($count)->offset($offset)->all(),
            'pagination' => false
        ]);

        return ['status'=>'ok','result'=>
            [
                'items' => $discounts->getModels(),
                'totalCount' =>(int) Sales::find()->where(['card_id'=>$card->id])->count(),
                'totalSaved' => $card->getSaved()
            ]
        ];
    }

    private function load()
    {
        $form = new CardNumberForm();
        $form->load(\Yii::$app->request->post(),'');

        if($form->validate()){
            if(!$card = Card::find()->where(['number'=>$form->number])->one()){
                return ['status'=>'fail','code'=>'CARD_NOT_FOUND'];
            };

            return $card;
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];
    }
}