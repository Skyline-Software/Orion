<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 14:33
 */

namespace core\entities\card;


use core\entities\sales\Sales;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Card
 * @package core\entities\card
 * @property integer $id
 * @property integer $type_id
 * @property string $number
 * @property string $code
 * @property integer $client_id
 * @property integer $valid
 * @property string $activated
 * @property integer $status
 * @property integer $user_id
 * @property array $delivery
 */
class Card extends ActiveRecord
{
    CONST BUYED = 1;
    CONST FREE = 0;
    public static function tableName()
    {
        return 'cards';
    }

    public static  function create($type_id, $number, $code, $status): self
    {
        $card = new static();
        $card->type_id = $type_id;
        $card->number = $number;
        $card->code = $code;
        $card->status = $status;

        return $card;
    }

    public static  function createFromCsv($type_id, $number): self
    {
        $card = new static();
        $card->type_id = $type_id;
        $card->number = $number;
        return $card;
    }

    public function edit($type_id, $number, $code, $status): void
    {
        $this->type_id = $type_id;
        $this->number = $number;
        $this->code = $code;
        $this->status = $status;
    }

    public function buy($user_id): void
    {
        $this->user_id = $user_id;
        $this->buyed = self::BUYED;
    }

    public function setupDelivery($name,$phone,$address):void
    {
        if(!empty($name) || !empty($phone) || !empty($address))
        {
            $this->delivery = ['name'=>$name,'phone'=>$phone,'address'=>$address];
        }
    }

    public function activate($user_id):void
    {
        if(is_null($this->activated)){
            $this->activated = time();
        }
        $this->status = 1;
        $this->user_id = $user_id;
    }

    public function deactivate():void
    {
        $this->user_id = null;
    }

    public function getClient():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getType():ActiveQuery
    {
        return $this->hasOne(CardType::class,['id'=>'type_id']);
    }

    public function getSales():ActiveQuery
    {
        return $this->hasMany(Sales::class,['card_id'=>'id']);
    }

    public function attributeLabels()
    {
        return [
          'type_id' => 'Тип',
          'type' => 'Тип',
          'number' => 'Номер',
          'code' => 'Код',
          'client' => 'Владелец',
          'client_id' => 'Владелец',
          'activated' => 'Когда активирована',
          'whenActivated' => 'Когда активирована',
          'status' => 'Активна?',
          'validity' => 'Заканчивается через'
        ];
    }

    public function fields()
    {
        return ['id','type_id','valid','number','activated',
            'name'=>function($model){
                return $model->type->name;
            },'photo'=>function($model){
                return $model->type->photo;
            },'support_phone'=>function($model){
                return $model->type->support_phone;
            },'saved' => function($model){
                return $model->getSaved();
            },'valid' => function($model){
                return $model->getValidityDate();
            }
        ];
    }

    public function getSaved()
    {
        if(!$this->sales){
            return 0;
        }
        $saved = array_map(function ($item){
            return $item->saved;
        },$this->sales);
        return array_sum($saved);
    }


    public function getValidity($format = '%r%a дней')
    {
        if(!$this->activated){
            return false;
        }
        $whenActivated = new \DateTime();
        $whenActivated->setTimestamp($this->activated);
        $whenEnd = clone $whenActivated;
        $whenEnd->modify("+{$this->type->validity} days");
        $current = new \DateTime();
        $diff = $current->diff($whenEnd)->format($format);
        return $diff;
    }

    public function getValidityDate()
    {
        if(!$this->activated){
            return false;
        }
        $whenActivated = new \DateTime();
        $whenActivated->setTimestamp($this->activated);
        $whenEnd = clone $whenActivated;
        $whenEnd->modify("+{$this->type->validity} days");
        return $whenEnd->getTimestamp();
    }

    public function getWhenActivated()
    {
        $whenActivated = new \DateTime();
        $whenActivated->setTimestamp($this->activated);
        return $whenActivated->format('d.m.y');
    }
}