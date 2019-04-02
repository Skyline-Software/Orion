<?php
namespace core\forms\auth;

use core\entities\user\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\core\entities\user\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('backend','Пользователя с такой почтой не существует.')
            ],
        ];
    }

}
