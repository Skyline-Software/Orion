<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 09.09.2018
 * Time: 12:19
 */

namespace core\filters;


use core\helpers\ErrorHelper;
use yii\base\ActionFilter;
use yii\helpers\Url;

class CustomerFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);

    }
}