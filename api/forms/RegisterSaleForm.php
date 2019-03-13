<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 12:45
 */

namespace api\forms;


use core\entities\buy\Buy;
use core\entities\card\Card;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use core\entities\partner\Partner;
use yii\base\Model;

class RegisterSaleForm extends Model
{
    public $card_number;
    public $token;
    public $amount;


    public function rules()
    {
        return [
            ['card_number', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['token', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['amount', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
          [['token','card_number'],'required','message' => '{attribute}_IS_EMPTY'],
          [['token'],'exist','targetClass' => Partner::class,'targetAttribute' => 'token','message'=>'PARTNER_NOT_FOUND_BY_THIS_TOKEN'],
          [['card_number'],'exist','targetClass' => Card::class,'targetAttribute' => 'number','message'=>'CARD_NOT_FOUNT_BY_THIS_NUMBER'],
          [['amount'],'integer','message' => 'MUST_BE_INTEGER'],
          [['amount'],'default','value' => 0],
          [['token'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'card_number'=>'CARD_NUMBER',
            'token' =>'TOKEN',
            'amount' => 'AMOUNT'
        ];
    }
}