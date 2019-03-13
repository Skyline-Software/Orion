<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 14:34
 */

namespace core\entities\card;


use core\entities\partner\Partner;
use yii\db\ActiveRecord;

/**
 * Class CardAndPartner
 * @package core\entities\card
 * @property int $id
 * @property int $partner_id
 */
class CardAndPartner extends ActiveRecord
{
    public static function create($card_type_id, $discount,$description,$partner_id,$status):self
    {
        $cap = new static();
        $cap->card_type_id = $card_type_id;
        $cap->discount = $discount;
        $cap->description = $description;
        $cap->partner_id = $partner_id;
        $cap->status = $status;

        return $cap;
    }

    public static function tableName()
    {
        return 'stocks';
    }

    public function getType(){
        return $this->hasOne(CardType::class,['id'=>'card_type_id']);
    }

    public function getPartner(){
        return $this->hasOne(Partner::class,['id'=>'partner_id']);
    }

    public function rules()
    {
        return [
            [['card_type_id','discount','description','partner_id','status','hot'],'safe']
        ];
    }

    public function fields()
    {
        return ['id','card_type_id','discount','description','partner_id','hot'];
    }
}