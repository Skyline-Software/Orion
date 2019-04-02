<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 15:51
 */

namespace core\entities\assn;


use core\entities\agency\Agency;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserAgencyAssn
 * @package core\entities\assn
 * @property integer $id
 * @property integer $user_id
 * @property integer $agency_id
 * @property integer $created_at
 * @property integer $role
 * @property User $user
 * @property Agency $agency
 */
class UserAgencyAssn extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_agency_assn';
    }

    public static function assign($user_id, $agency_id, $agent_price, $agent_metrik, $role = 0):self
    {
        $assn = new static();
        $assn->user_id = $user_id;
        $assn->agency_id = $agency_id;
        $assn->role = $role;
        $assn->created_at = time();
        $assn->agent_price = $agent_price;
        $assn->agent_metrik = $agent_metrik;

        return $assn;
    }

    public function rules()
    {
        return [
            [['user_id','agency_id','role','created_at','agent_price','agent_metrik'],'safe']
        ];
    }

    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getAgency():ActiveQuery
    {
        return $this->hasOne(Agency::class,['id'=>'agency_id']);
    }


}