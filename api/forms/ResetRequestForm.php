<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 13:33
 */

namespace api\forms;


use core\entities\user\User;
use yii\base\Model;

class ResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['email'],'required','message' => 'EMAIL_IS_EMPTY'],
            [['email'],'email','message' => 'EMAIL_MUST_BE_VALID_EMAIL'],
            [['email'],'exist','targetClass' => User::class,'targetAttribute' => 'email','message' => 'USER_NOT_FOUND']
        ];
    }
}