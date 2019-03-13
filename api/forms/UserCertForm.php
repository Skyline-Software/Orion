<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 12:45
 */

namespace api\forms;


use core\entities\buy\Buy;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use core\entities\partner\Partner;
use yii\base\Model;

class UserCertForm extends Model
{
    public $token;
    public $cert_num;


    public function rules()
    {
        return [

            ['token', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['cert_num', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],

          [['token','cert_num'],'required','message' => '{attribute}_IS_EMPTY'],
          [['token'],'exist','targetClass' => Partner::class,'targetAttribute' => 'token','message'=>'PARTNER_NOT_FOUND_BY_THIS_TOKEN'],
          [['token'],'string'],
          [['cert_num'],'exist','targetClass' => Cert::class,'targetAttribute' => 'number','message' => 'CERT_IS_NOT_FOUND']
        ];
    }

    public function attributeLabels()
    {
        return [
            'token' =>'TOKEN',
        ];
    }
}