<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 12:53
 */

namespace api\forms;


use core\entities\user\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $pass;
    public $device;
    public $code;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['pass', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['device', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['code', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['email'],'required','message' => '{attribute}_IS_EMPTY'],
            [['email'],'exist','targetClass' => User::class,'targetAttribute' => 'email', 'message' => 'USER_NOT_FOUND'],
            [['email'],'email','message' => 'MUST_BE_EMAIL'],
            [['device'],'string','message' => '{attribute}_MUST_BE_STRING'],
            [['device'],'default','value' => 'desktop'],
            [['device'],'in','range'=>['desktop','android','ios'],'message' => 'DEVICE_MUST_BE_FROM_RANGE'],
            [['code'],'string','min'=>9,'max'=>9,
                'tooShort'=>'INCORRECT_INT_LONG_MIN_9',
                'tooLong'=>'INCORRECT_INT_LONG_MAX_9'
            ],
            [['pass'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>'EMAIL',
            'pass'=>'PASS',
            'device'=>'DEVICE',
        ];
    }
}