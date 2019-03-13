<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 02:11
 */

namespace core\useCase\auth;


use core\entities\user\customer\Profile;
use core\entities\user\User;
use core\forms\auth\SignupForm;
use core\repositories\user\customer\ProfileRepository;
use core\repositories\user\UserRepository;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $supportEmail;
    private $users;
    private $customers;

    public function __construct(array $supportEmail, MailerInterface $mailer, UserRepository $users, ProfileRepository $customerRepository)
    {
        $this->mailer = $mailer;
        $this->supportEmail = $supportEmail;
        $this->users = $users;
        $this->profiles = $customerRepository;
    }

    public function signup(SignupForm $form):User
    {
        if(User::find()->where(['email'=> $form->email])->one()){
            throw new \DomainException('Емейл уже занят');
        }

        $user = User::requestSignup(
            $form->email, $form->password
        );

        $customer = Profile::create($user, '', '', '',);
        $this->profiles->save($customer);

        $this->users->save($user);
        $this->mailer
            ->compose(
                'auth/signup/confirm-html',
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($user->email)
            ->setSubject('Подтвердите регистрацию ' . \Yii::$app->name)
            ->send();


        return $user;
    }

    public function confirm(string $token):void
    {
        if(empty($token)){
            throw new \DomainException('Пустой токен.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }

}