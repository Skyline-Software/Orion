<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 12:37
 */

namespace core\entities\cert;


use core\entities\partner\Partner;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Cert
 * @package core\entities\cert
 * @property integer $id;
 * @property string $value
 * @property string $number
 * @property string $user_id
 * @property string $partner_id
 * @property string $created_at
 * @property string $used
 */
class Cert extends ActiveRecord
{
    public static function tableName()
    {
        return 'certs';
    }

    public static function create(Item $item, $user_id):self
    {
        $cert = new static();
        $cert->value = $item->value;
        $cert->number = $item->number;
        $cert->created_at = time();
        $cert->user_id = $user_id;

        return $cert;
    }

    public static function createByAdmin(Item $item):self
    {
        $cert = new static();
        $cert->value = $item->value;
        $cert->number = $item->number;
        $cert->created_at = time();

        return $cert;
    }

    public function assignUser($user_id):void
    {
        $this->user_id = $user_id;
    }

    public function useCert($partner_id):void
    {
        $this->partner_id = $partner_id;
        $this->used = (new \DateTime('now'))->format('Y-m-d H:i:s');
    }

    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getPartner():?ActiveQuery
    {
            return $this->hasOne(Partner::class,['id'=>'partner_id']);
    }

    public function attributeLabels()
    {
        return [
          'number'=>'Номер',
          'value'=>'Номинал',
          'used'=>'Использован',
          'created_at'=>'Приобретен',
        ];
    }

    public function fields()
    {
        return ['id','created_at','value','number','partner_id','name'=>function($model){
            if($model->partner){
                return $model->partner->name;
            }
        },'used'=>function($model){
            return $model->partner_id ? true : false;
        }];
    }


}