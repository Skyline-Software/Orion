<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 05.08.2018
 * Time: 00:07
 */

namespace core\helpers\user;

use core\entities\user\customer\Profile;
use core\entities\user\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function roleList(): array
    {
        return [
            User::ROLE_ADMIN => Yii::t('backend','Администратор'),
            User::ROLE_CUSTOMER => Yii::t('backend','Пользователь'),
            User::ROLE_AGENCY_ADMIN => Yii::t('backend','Администратор агентства'),
            User::ROLE_AGENT => Yii::t('backend','Агент'),
        ];
    }

    public static function agencyRoleList(): array
    {
        return [
            User::ROLE_AGENCY_ADMIN => 'Администратор агентства',
            User::ROLE_AGENT => 'Агент',
            User::ROLE_CUSTOMER => 'Пользователь'
        ];
    }

    public static function roleName($status): string
    {
        return ArrayHelper::getValue(self::roleList(), $status);
    }

    public static function typeList(): array
    {
        return [
            Profile::TYPE_INDIVIDUAL => 'Физ.лицо',
            Profile::TYPE_PERSON => 'Юр.лицо',
        ];
    }

    public static function typeName($status): string
    {
        if(is_null($status)){
            return Yii::t('backend','не задано');
        }
        return ArrayHelper::getValue(self::typeList(), $status);
    }

    public static function statusList(): array
    {
        return [
            User::STATUS_ACTIVE => Yii::t('backend','Активен'),
            User::STATUS_BANNED_BY_ADMIN => Yii::t('backend','Заблокирован администратором'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status){
            case User::STATUS_BANNED_BY_ADMIN:
                $class = 'label label-danger';
                $label = Yii::t('backend','Заблокирован администратором');
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                $label = Yii::t('backend','Активен');
                break;
            default:
                $class = 'label label-default';
                $label = Yii::t('backend','Ожидает активации');
        }

        return Html::tag('span',$label,[
            'class'=>$class
        ]);
    }
}