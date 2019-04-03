<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 21.03.2019
 * Time: 09:05
 */

namespace core\helpers\user;


use core\entities\user\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class AgentHelper
{
    public static function roleList(): array
    {
        return [
            User::WORKING_STATUS_READY => Yii::t('backend','Готов к работе'),
            User::WORKING_STATUS_IN_PROCESS => Yii::t('backend','Выполняю заказ'),
            User::WORKING_STATUS_REST => Yii::t('backend','Не готов к работе'),
            User::WORKING_STATUS_BANNED => Yii::t('backend','Заблокирован'),
        ];
    }

    public static function roleName($status): string
    {
        return ArrayHelper::getValue(self::roleList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status){
            case User::WORKING_STATUS_BANNED:
                $class = 'label label-danger';
                $label = self::roleName(User::WORKING_STATUS_BANNED);
                break;
            case User::WORKING_STATUS_READY:
                $class = 'label label-success';
                $label = self::roleName(User::WORKING_STATUS_READY);
                break;
            case User::WORKING_STATUS_IN_PROCESS:
                $class = 'label label-info';
                $label = self::roleName(User::WORKING_STATUS_IN_PROCESS);
                break;
            case User::WORKING_STATUS_REST:
                $class = 'label label-warning';
                $label = self::roleName(User::WORKING_STATUS_REST);
                break;
            default:
                $class = 'label label-warning';
                $label = self::roleName(User::WORKING_STATUS_REST);
        }

        return Html::tag('span',$label,[
            'class'=>$class
        ]);
    }
}