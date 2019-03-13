<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 05.08.2018
 * Time: 00:07
 */

namespace core\helpers;

use core\entities\partner\Partner;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PartnerHelper
{
    public static function statusList(): array
    {
        return [
            Partner::STATUS_ACTIVE => 'Активно',
            Partner::STATUS_HIDE => 'Не активно',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status){
            case Partner::STATUS_HIDE:
                $class = 'label label-danger';
                $label = 'Не активно';
                break;
            case Partner::STATUS_ACTIVE:
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