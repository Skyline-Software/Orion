<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 26.03.2019
 * Time: 15:57
 */

namespace core\entities\agency;


use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Order
 * @package core\entities\agency
 * @property integer $id
 * @property integer $agency_id
 * @property integer $agent_id
 * @property integer $user_id
 * @property integer $price
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $status
 * @property integer $rating
 * @property integer $created_at
 *
 * @property string $start_coordinates
 * @property string $end_coordinates
 * @property string $comment
 *
 * @property Agency $agency
 * @property User $agent
 * @property User $user
 */
class Order extends ActiveRecord
{
    CONST STATUS_PAYED = 1;
    CONST STATUS_NOT_PAYED = 2;

    CONST STATUS_LIST = [
        self::STATUS_NOT_PAYED => 'Не оплачен',
        self::STATUS_PAYED => 'Оплачен',
    ];

    public static function tableName()
    {
        return 'orders';
    }

    public static function create($agency_id,$agent_id,$user_id,$start_coords,$end_coords,$price,$start_time,$status):self
    {
        $order = new static();
        $order->agency_id = $agency_id;
        $order->agent_id = $agent_id;
        $order->user_id = $user_id;
        $order->start_coordinates = $start_coords;
        $order->end_coordinates = $end_coords;
        $order->price = $price;
        $order->start_time = $start_time;
        $order->status = self::STATUS_NOT_PAYED;
        $order->created_at = time();

        return $order;
    }

    public function edit($agency_id,$agent_id,$user_id,$start_coords,$end_coords,$price,$start_time,$status):void
    {
        $this->agency_id = $agency_id;
        $this->agency_id = $agent_id;
        $this->user_id = $user_id;
        $this->start_coordinates = $start_coords;
        $this->end_coordinates = $end_coords;
        $this->price = $price;
        $this->start_time = $start_time;
        $this->status = $status;
    }

    public function setupEndTime($end_time):void
    {
        $this->end_time = $end_time;
    }

    public function setupRating($rating):void
    {
        $this->rating = $rating;
    }

    public function setupComment($comment):void
    {
        $this->comment = $comment;
    }

    public function attributeLabels()
    {
        return [
            'agency_id'=>'Агенство',
            'agent_id'=>'Агент',
            'user_id'=>'Клиент',
            'start_coordinates'=>'Исходная локация',
            'end_coordinates'=>'конечная локация',
            'start_time'=>'Время начала выполнения',
            'end_time'=>'Время окончания выполнения',
            'price'=>'Стоимость заказа',
            'rating'=>'Оценка заказа клиентом',
            'comment'=>'Комментарий заказчика',
            'status'=>'Статус',
        ];
    }

    public function getAgency():ActiveQuery
    {
        return $this->hasOne(Agency::class,['id'=>'agency_id']);
    }

    public function getAgent():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'agent_id']);
    }
    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }
}