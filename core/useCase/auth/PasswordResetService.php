<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 02:30
 */

namespace core\useCase\auth;


use core\entities\user\User;
use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;
use core\repositories\user\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $supportEmail;
    private $mailer;
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->supportEmail = 'test@test.com';
        $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form):void
    {
        /* @var $user User */
        $user = $this->users->getByEmail($form->email);
        if(!$user->isActive()){
            throw  new \DomainException('user is not active.');
        }
        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                'auth/reset/confirm-html',
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($user->email)
            ->setSubject('Сброс пароля ' . Yii::$app->name)
            ->send();

        if(!$sent){
            throw new \RuntimeException('Sanding error');
        }
    }

    public function requestByApi($email)
    {
        /* @var $user User */
        $user = $this->users->getByEmail($email);
        if(!$user->isActive()){
            throw  new \DomainException('user is not active.');
        }
        $user->requestPasswordReset();
        $this->users->save($user);

        return $user;

        /*$sent = $this->mailer
            ->compose(
                'auth/reset/confirm-html',
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($user->email)
            ->setSubject('Сброс пароля ' . Yii::$app->name)
            ->send();

        if(!$sent){
            throw new \RuntimeException('Sanding error');
        }*/
    }

    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }

    public function resetByApi(string $token, $pass): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($pass);
        $this->users->save($user);
    }




}