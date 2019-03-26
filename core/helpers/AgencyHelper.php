<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 18:07
 */

namespace core\helpers;


use core\entities\agency\Agency;
use core\entities\user\User;
use yii\helpers\ArrayHelper;

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

    public static function getAllowedAgents($agency_id):array
    {
        $data =  array_map(function ($user){
            return ['id'=>$user->id,'name'=>$user->name];
        },User::find()
            ->joinWith('agencyAssn assn')
            ->where(['assn.agency_id'=>$agency_id,'assn.role'=>User::ROLE_AGENT])
            ->all()
        );

        return ArrayHelper::map($data,'id','name');
    }

    public static function getAllowedUsers($agency_id):array
    {
        $data = array_map(function ($user){
            return ['id'=>$user->id,'name'=>$user->name];
        },User::find()
            ->joinWith('agencyAssn assn')
            ->where(['assn.agency_id'=>$agency_id,'assn.role'=>User::ROLE_CUSTOMER])
            ->all()
        );
        return ArrayHelper::map($data,'id','name');
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

        $agencies = array_map(function ($item){
            return $item['id'];
        },Agency::find()->select(['id'])->asArray()->all());
        return $agencies;
    }
}