<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 11:20
 */

namespace core\forms\manage\user;


use core\entities\Image;
use core\entities\user\User;
use core\forms\ImageForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class ManagerForm
 * @package core\forms\manage\user
 * @property ProfileForm $profile
 * @property ImageForm $photo
 */
class AdminForm extends CompositeForm
{
    public $email;
    public $password;

    private $_user;

    public function __construct(User $user = null, array $config = [])
    {
        if($user){
            $this->email = $user->email;
            $this->profile = new ProfileForm($user->profile);
            $this->photo = new ImageForm('PhotoForm', new Image($user->profile->photo));

            $this->_user = $user;
        }else{
            $this->profile = new ProfileForm();
            $this->photo = new ImageForm('PhotoForm');
        }

        parent::__construct($config);
    }

    public function rules()
    {
        if(!$this->_user){
            return [
                [['email','password'],'safe'],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Этот E-mail уже занят.']
            ];
        }

        return [
            [['email','password'],'safe'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id], 'message' => 'Этот E-mail уже занят.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
        ];
    }

    public function internalForms()
    {
        return ['profile','photo'];
    }
}