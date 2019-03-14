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
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function roleList(): array
    {
        return [
            User::ROLE_ADMIN => 'Администратор',
            User::ROLE_CUSTOMER => 'Пользователь',
            User::ROLE_AGENCY_ADMIN => 'Администратор агентства',
            User::ROLE_AGENT => 'Агент',
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
            return 'не задано';
        }
        return ArrayHelper::getValue(self::typeList(), $status);
    }

    public static function statusList(): array
    {
        return [
            User::STATUS_ACTIVE => 'Активен',
            User::STATUS_BANNED_BY_ADMIN => 'Заблокирован администратором',
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
                $label = 'Заблокирован администратором';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                $label = 'Активен';
                break;
            default:
                $class = 'label label-default';
                $label = 'Ожидает активации';
        }

        return Html::tag('span',$label,[
            'class'=>$class
        ]);
    }
}