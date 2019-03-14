<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 18:07
 */

namespace core\helpers;


class AgencyHelper
{
    public static function getAllowedAgencies():array
    {
        $user = \Yii::$app->user->getIdentity();
        if(!$user->isAdmin()){
            $agencies = array_map(function ($item){
                return $item['id'];
            },$user->getAgencies()->select('id')->asArray()->all());
            return \yii\helpers\ArrayHelper::map(
                \core\entities\agency\Agency::find()
                    ->andFilterWhere(['IN','id',$agencies])
                    ->all(),'id','name'
            );
        }

        return \yii\helpers\ArrayHelper::map(
            \core\entities\agency\Agency::find()
                ->all(),'id','name'
        );
    }

    public static function getAllowedAgenciesIds():array
    {
        $user = \Yii::$app->user->getIdentity();
        if(!$user->isAdmin()){
            $agencies = array_map(function ($item){
                return $item['id'];
            },$user->getAgencies()->select('id')->asArray()->all());
            return $agencies;
        }

        return \yii\helpers\ArrayHelper::map(
            \core\entities\agency\Agency::find()
                ->all(),'id','name'
        );
    }
}