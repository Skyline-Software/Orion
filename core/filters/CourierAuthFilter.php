<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.09.2018
 * Time: 11:27
 */

namespace core\filters;


use Yii;
use yii\base\ActionFilter;

class CourierAuthFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if($action->id === "login"){
            $user = Yii::$app->user;
            if(!$user->isGuest){
                if(!$user->identity->isCourier()){
                    Yii::$app->session->setFlash('error','У вас нет прав');
                    return Yii::$app->getResponse()->redirect('/login');
                }
                return parent::beforeAction($action);
            }
        }
    }
}