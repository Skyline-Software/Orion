<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 26.12.2018
 * Time: 20:50
 */

namespace core\helpers;


use yii\helpers\ArrayHelper;

class ImageHelper
{
    public static function getConfig($param)
    {
        return ArrayHelper::getValue(
            \Yii::$app->params['resize'],
            $param,
            ArrayHelper::getValue(\Yii::$app->params['resize'],'default')
        );
    }
}