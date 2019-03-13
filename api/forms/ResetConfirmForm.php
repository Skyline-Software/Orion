<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 13:39
 */

namespace api\forms;


use core\entities\user\User;
use yii\base\Model;

class ResetConfirmForm extends Model
{
    public $token;
    public $pass;

    public function rules()
    {
        return [
            ['token', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['pass', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['token','pass'],'required','message' => '{attribute}_IS_EMPTY'],
            [['token'],'exist','targetClass' => User::class,'targetAttribute' => 'reset_token','message' => 'INVALID_RESET_TOKEN'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'token'=>'TOKEN',
            'pass'=>'PASS'
        ];
    }
}