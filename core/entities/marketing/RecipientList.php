<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 01.02.2019
 * Time: 16:56
 */

namespace core\entities\marketing;


use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class RecipientList extends ActiveRecord
{
    CONST SEND = 1;
    CONST NOT_SEND = 0;
    CONST ERROR = 2;
    CONST WATCHED = 3;

    CONST LIST = [
        self::SEND => 'Отправлено',
        self::NOT_SEND => 'Не отправлено',
        self::ERROR => 'Ошибка',
        self::WATCHED => 'Просмотрено'
    ];

    public static function tableName()
    {
        return 'recipient_list';
    }

    public function rules()
    {
        return [
            [['client_id','config_id','status'],'safe']
        ];
    }

    public function getConfig():ActiveQuery
    {
        return $this->hasMany(EmailConfig::class,['id'=>'config_id']);
    }

    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'client_id']);
    }
}