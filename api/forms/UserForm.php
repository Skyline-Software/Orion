<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 21:08
 */

namespace api\forms;


use core\entities\user\User;
use yii\base\Model;

class UserForm extends Model
{
    public $email;
    public $password;

    public $name;
    public $phone;
    public $sex;
    public $birthday;
    public $photo;
    public $language;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['email','password','name'],'required','message' => "{attribute}_IS_EMPTY"],
            [['email'],'email','message' => 'INVALID_EMAIL'],
            [['email'],'unique','message' => 'NOT_UNIQUE_EMAIL','targetClass' => User::class,'targetAttribute' => 'email'],
            ['password', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['password'],'string',
                'min'=>6,'max'=>20,
                'tooShort'=>'INCORRECT_STRING_LONG_MIN_6',
                'tooLong'=>'INCORRECT_STRING_LONG_MAX_20'
            ],
            ['name', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['name'],'string',
                'min'=>4,'max'=>20,
                'tooShort'=>'INCORRECT_STRING_LONG_MIN_4',
                'tooLong'=>'INCORRECT_STRING_LONG_MAX_20'
            ],
            ['sex', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['sex'],'in','range' => [0,1],'message' => 'NOT_IN_RANGE_0_OR_1'],
            [['sex'],'integer','message' => 'MUST_BE_INTEGER'],
            ['birthday', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['birthday'],'date','format' => 'php:d.m.y','message' => 'INVALID_FORMAT_D.M.Y'],
            #[['phone'],'match','pattern' =>'/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'],
            ['phone', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['phone'],'safe'],
            ['language', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['language'],'default','value'=>'ru'],
            [['language'],'string',
                'min'=>2,
                'max'=>2,
                'tooLong'=>'LANGUAGE_MUST_BE_2_CHAR',
                'tooShort'=>'LANGUAGE_MUST_BE_2_CHAR'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>'EMAIL',
            'password'=>'PASSWORD',
            'name'=>'NAME',
        ];
    }
}