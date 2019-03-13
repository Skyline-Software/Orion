<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 12.02.2019
 * Time: 16:21
 */

namespace core\entities\partner;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class PartnerAdres extends ActiveRecord
{


    public function getCoords():?string
    {
        return implode('@',[$this->lat,$this->long]);
    }

    public function setCoords($value):?string
    {
        return $value;
    }


    public static function tableName()
    {
        return 'partners_adresses';
    }

    public function rules()
    {
        return [
            [['address','partner_id','lat','long','phone'],'safe']
        ];
    }

    public function fields()
    {
        return [
            'address',
            'phone',
            'lat',
            'long'
        ];
    }
}