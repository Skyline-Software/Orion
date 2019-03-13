<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.09.2018
 * Time: 02:05
 */

namespace core\useCase\auth;


use alexeevdv\sms\ru\Sms;
use core\entities\user\User;
use core\forms\auth\ApproveForm;
use core\forms\auth\RequestKeyForm;
use core\helpers\notify\NotifyHelper;
use core\repositories\NotFoundExeption;
use Yii;
class SignupByKeyService
{

    public function create(RequestKeyForm $form){
        $user = User::signupByKey($form->phone);
        return $user;
    }

    public function send(User $user){
        if($user->new == 1){
            $loginUrl = Yii::$app->urlManager->createAbsoluteUrl('login');
            \Yii::$app->sms->send(new Sms([
                "to" => $user->profile->phone,
                "text" => 'Здравствуйте, вы успешно зарегистрировались в системе удаленного заказа печати "Мигом" '.$loginUrl
            ]));
        }
        \Yii::$app->sms->send(new Sms([
            "to" => $user->profile->phone,
            "text" => $user->auth_key,
        ]));


    }

    public function approve(ApproveForm $form): User
    {
        if(!$user = User::findOne(['auth_key'=>$form->key])){
            throw new NotFoundExeption('Пользователь не найден');
        }
        return $user;
    }
}