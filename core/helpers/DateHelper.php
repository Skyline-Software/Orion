<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.09.2018
 * Time: 20:16
 */

namespace core\helpers;


class DateHelper
{
    public static function format($timestamp){
        return date("d.m.Y H:i",$timestamp);
    }
}