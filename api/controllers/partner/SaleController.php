<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 15:48
 */

namespace api\controllers\partner;


use api\forms\RegisterSaleForm;
use api\forms\UserCertForm;
use common\traits\loadTrait;
use core\entities\card\Card;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use core\entities\partner\Partner;
use core\entities\sales\Sales;
use Yii;
use yii\rest\Controller;

class SaleController extends Controller
{
    use loadTrait;

    public function actionRegister()
    {
        $form = new RegisterSaleForm();
        $form->load(\Yii::$app->request->post(),'');

        if($form->validate()){

            $card = Card::findOne(['number'=>$form->card_number]);
            if(is_null($card->activated)){
                return ['status'=>'fail','code'=>'NON_ACTIVATED_CARD'];
            }

            $partner = Partner::findOne(['token'=>$form->token]);
            if($form->amount === 0){
                $register = Sales::getDiscount($card->id,$partner->id);
                return ['status'=>'ok','result'=>$register];
            }
            $register = Sales::create($card->id,$card->user_id, $partner->id,$form->amount);
            $register->save();
            $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $username = $card->client->name;
            $html = '<html><body>';
            $html .= "Здравствуйте, $username!";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Вы получили скидку по карте <a href=\"https://ulitsarubinshteina.ru/card?number=".$card->id."\">".$card->id."</a> в заведении <a href=\"https://ulitsarubinshteina.ru/place?id=".$partner->id."\">".$partner->name."</a>.";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Размер скидки составил: ".$register->saved." рублей.";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Все полученные скидки доступны по адресу: <a href=\"https://ulitsarubinshteina.ru/discounts?id=".$card->id."\">https://ulitsarubinshteina.ru/discounts?id=".$card->id."</a>.";
            $html .= "<br>";
            $html .= "<hr>";
            $html .= "С уважением, 3d-робот автоматической доставки собщений.";
            $html .= '</body></html>';

            $sent = mail($card->client->email,'Получена скидка по карте', $html, $headers);
            return ['status'=>'ok','result'=>$register,'email'=>$sent];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];



    }

    public function actionUseCert(){
        $form = new UserCertForm();
        $form->load(\Yii::$app->request->post(),'');

        if($form->validate()){
            $partner = Partner::findOne(['token'=>$form->token]);
            if(!$partner){
                return ['status'=>'fail','code'=>'PARTNER_NOT_FOUND_BY_THIS_TOKEN'];
            }
            $cert = Cert::findOne(['number'=>$form->cert_num]);
            if(!is_null($cert->used)){
                return ['status'=>'fail','code'=>'USED_CERT'];
            }
            $cert->useCert($partner->id);
            $cert->save(false);
            $register = Sales::createByApi($partner->id,$cert->value,$cert->user_id);
            $register->save();
            $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $username = $cert->user->name;
            $html = '<html><body>';
            $html .= "Здравствуйте, $username!";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Вы использовали сертификат $cert->number в заведении <a href=\"https://ulitsarubinshteina.ru/place?id=".$partner->id."\">".$partner->name."</a>.";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Номинал сертификата: ".$cert->value." рублей.";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "Все сертификаты доступны по адресу: <a href=\"https://ulitsarubinshteina.ru/certs\">https://ulitsarubinshteina.ru/certs</a>.";
            $html .= "<br>";
            $html .= "<hr>";
            $html .= "С уважением, 3d-робот автоматической доставки собщений.";
            $html .= '</body></html>';
            $sent = mail($cert->user->email,'Использован сертификат', $html, $headers);
            return ['status'=>'ok','result'=>$register,'send'=>$sent];

        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];

    }
}