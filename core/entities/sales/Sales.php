<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 15:42
 */

namespace core\entities\sales;


use core\entities\card\Card;
use core\entities\card\CardAndPartner;
use core\entities\cert\Cert;
use core\entities\partner\Partner;
use core\entities\partner\PartnerCategory;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Sales
 * @package core\entities\sales
 * @property Partner $partner
 */
class Sales extends ActiveRecord
{
    public static function tableName()
    {
        return 'sales_history';
    }

    public static function create($card_id, $user_id, $partner_id, $amount):self
    {
        $sale = new static();
        $sale->card_id = $card_id;
        $sale->user_id = $user_id;
        $sale->partner_id = $partner_id;
        $sale->amount = $amount;
        $sale->saved = $amount * (int)self::getDiscount($card_id, $partner_id) / ( 100 - (int)self::getDiscount($card_id, $partner_id));
        $sale->created_at = time();
        return $sale;
    }

    public static function createByApi($partner_id, $cert_value,$user_id):self
    {
        $sale = new static();
        $sale->partner_id = $partner_id;
        $sale->user_id = $user_id;
        $sale->saved = $cert_value;
        $sale->created_at = time();

        return $sale;
    }



    public function getPayed()
    {
        if($this->amount == 0 || is_null($this->amount)){
            return 0;
        }
        return $this->amount;
    }

    public function getPartner()
    {
        return $this->hasOne(Partner::class,['id'=>'partner_id']);
    }

    public function getCard()
    {
        return $this->hasOne(Card::class,['id'=>'card_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public static function getDiscount($card_id,$partner_id)
    {
        $type_id = Card::findOne(['id'=>$card_id])->type_id;
        $discount = CardAndPartner::findOne(['card_type_id'=>$type_id,'partner_id'=>$partner_id])->discount;

        return $discount;
    }

    public function fields()
    {
        return ['amount','saved','partner' => function($model){
            /* @var Sales $model*/
            if($model->partner){
                $categories = array_map(function ($category){
                    /* @var PartnerCategory $category */
                    return [$category->id=>$category->name];
                },$model->partner->category);
                return ['name'=>$model->partner->name,'categories'=>$categories];
            }
            return [];
        },'date'=>function($model){
            return (new \DateTime())->setTimestamp($model->created_at)->format('d.m.y');
        }];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Сумма/руб.',
            'saved' => 'Сэкономлено/руб.',
            'created_at' => 'Дата транзакции',
            'payed' => 'Фактически оплачено',
        ];
    }
}