<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 15:45
 */

namespace core\entities\agency;


use core\entities\assn\UserAgencyAssn;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Agency
 * @package core\entities\agency
 * @property integer $id
 * @property integer $created_at
 * @property integer $status
 * @property string $name
 * @property string $logo
 * @property string $web_site
 * @property string $payed_for
 * @property User[] $users
 * @property UserAgencyAssn[] $userAssn
 */
class Agency extends ActiveRecord
{
    CONST STATUS_ACTIVE = 1;
    CONST STATUS_INACTIVE = 0;

    CONST AGENCY_METRIK_LIST = [
        1 => 'В час',
        2 => 'За километр'
    ];

    public static function tableName()
    {
        return 'agency';
    }

    public static function create($name, $logo, $web_site, $status, $payed_for = 0):self
    {
        $agency = new static();
        $agency->name = $name;
        $agency->logo = $logo;
        $agency->web_site = $web_site;
        $agency->status = $status;
        $agency->payed_for = $payed_for;
        $agency->created_at = time();

        return $agency;
    }

    public function edit($name, $logo, $web_site, $status, $payed_for = 0):void
    {
        $this->name = $name;
        $this->logo = $logo;
        $this->web_site = $web_site;
        $this->status = $status;
        $this->payed_for = $payed_for;
        #$this->created_at = time();
    }

    public function setupAgentPrice($agent_price,$agent_metrik):void
    {
        $this->agent_price = $agent_price;
        $this->agent_metrik = $agent_metrik;
    }

    public function activate():void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function deactivate():void
    {
        $this->status = self::STATUS_INACTIVE;
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Название',
            'logo'=>'Логотип',
            'web_site'=>'Веб-сайт',
            'status'=>'Статус',
            'payed_for'=>'Дата окончания оплаченного периода',
            'created_at'=>'Зарегистрирован',
        ];
    }

    public function getUsers():ActiveQuery
    {
        return $this->hasMany(User::class,['id'=>'user_id'])->via('userAssn');
    }

    public function getUserAssn():ActiveQuery
    {
        return $this->hasMany(UserAgencyAssn::class,['agency_id'=>'id']);
    }


}