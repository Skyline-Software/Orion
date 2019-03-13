<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 12:23
 */

namespace core\entities\user;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UserAuth extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_auth';
    }

    public static function create($user_id):self
    {
        $auth = new static();
        $auth->user_id = $user_id;
        $auth->lastact = time();
        $auth->token = \Yii::$app->security->generateRandomString();
        $auth->ip = ArrayHelper::getValue($_SERVER,'REMOTE_ADDR');
        return $auth;
    }

    public static function updateActivity($token):void
    {
        $row = self::findOne(['token'=>$token]);
        $row->lastact = time();
        $row->update(false);
    }

    public function setPushToken($token):void
    {
        $this->push_token = $token;
    }

    public function setDevice($device):void
    {
        $this->device = $device;
    }
}