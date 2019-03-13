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

class ProfileForm extends Model
{
    public $pass;
    public $name;
    public $phone;
    public $sex;
    public $birthday;
    public $photo;
    public $language;

    public function rules()
    {
        return [
            ['pass', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['pass'],'string',
                'min'=>6,'max'=>20,
                'tooShort'=>'INCORRECT_STRING_LONG_MIN_6',
                'tooLong'=>'INCORRECT_STRING_LONG_MAX_20'
            ],
            ['name', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['name'],'required','message' => 'NAME_IS_EMPTY'],
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
            ['phone', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['phone'],'safe'],
            ['birthday', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['birthday'],'date','format' => 'php:d.m.y','message' => 'INVALID_FORMAT_D.M.Y'],
            ['language', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['language'],'default','value'=>'ru'],
            [['language'],'string','min'=>2, 'max'=>2,'tooLong'=>'LANGUAGE_MUST_BE_2_CHAR','tooShort'=>'LANGUAGE_MUST_BE_2_CHAR']
        ];
    }

    public function attributeLabels()
    {
        return [
            'pass'=>'PASS',
            'name'=>'NAME',
        ];
    }
}