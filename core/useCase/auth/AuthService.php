<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 17:05
 */

namespace core\useCase\auth;


use core\forms\auth\LoginForm;
use core\repositories\user\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form){

        $user = $this->users->getByEmail($form->email);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Некорректные данные (почта или пароль)');
        }


        return $user;

    }
}