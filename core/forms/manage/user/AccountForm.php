<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 27.08.2018
 * Time: 14:07
 */

namespace core\forms\manage\user;


use core\entities\user\User;
use Yii;
use yii\base\Model;

class AccountForm extends Model
{
    public $email;
    public $password;

    private $_ar;

    public function __construct(User $ar, array $config = [])
    {
        $this->email = $ar->email;
        $this->_ar = $ar;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email','password'],'safe'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_ar->id], 'message' => 'Этот E-mail уже занят.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('backend','E-mail'),
            'password' => Yii::t('backend','Пароль'),
        ];
    }

}