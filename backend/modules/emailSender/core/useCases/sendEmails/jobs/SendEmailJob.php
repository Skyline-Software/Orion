<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:40
 */

namespace backend\modules\emailSender\core\useCases\sendEmails\jobs;


use yii\base\BaseObject;

class SendEmailJob extends BaseObject implements \yii\queue\JobInterface
{
    public $email;
    public $subject;
    public $message;
    public $template;

    public function execute($queue)
    {
            \Yii::$app->mailer
            ->compose(
                $this->template,
                ['message' => $this->message]
            )
            ->setFrom('test@test.com')
            ->setTo($this->email)
            ->setSubject($this->subject)
            ->send();
    }
}