<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 21:36
 */

namespace core\useCase\auth;


use core\entities\user\customer\Profile;
use core\entities\user\User;
use core\repositories\user\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($network, $identity, $name, $email): User
    {
        if($user = $this->users->findByNetworkIdentity($network, $identity)){
            return $user;
        }
        $find = User::find()->where(['email'=>$email])->one();
        if($find){
            $this->attach($find->id,$network,$identity);
            return $find;
        }
        $user = User::signupByNetwork($network,$identity,$email);
        $this->users->save($user);
        $customer = Profile::create($user, $name, '', '');
        $customer->save();
        return $user;
    }

    public function attach($id, $network, $identity):void
    {

        if($this->users->findByNetworkIdentity($network,$identity)){
            throw new \DomainException('Сеть уже подключена.');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network,$identity);
        $this->users->save($user);
    }
}