<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:39
 */

namespace backend\modules\emailSender\core\useCases\sendEmails;


use backend\modules\emailSender\core\useCases\sendEmails\jobs\SendEmailJob;
use Yii;
use yii\web\User;

class SendEmailService
{
    public $emails;
    public $message;
    public $subject;

    public function makeJobs():void
    {
        if(empty($this->emails)){
            throw new \Exception('Емейлы не указаны');
        }

        foreach ($this->emails as $email){
            Yii::$app->queue->push(new SendEmailJob([
                'email' => $email,
                'message' => $this->prepareMessage($this->message, $email),
                'template' => 'marketing/confirm-html'
            ]));
        }
    }

    public function prepareMessage($message, $email):string
    {
        $user = \core\entities\user\User::findOne(['email'=>$email]);
        $prepare = str_replace('{{ФИО}}',$user->profile->name, $message);
        return $prepare;
    }

}