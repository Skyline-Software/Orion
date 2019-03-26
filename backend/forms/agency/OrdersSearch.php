<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 16:11
 */

namespace backend\forms\agency;


use core\entities\agency\Agency;
use core\entities\agency\Order;
use core\entities\user\User;
use core\helpers\AgencyHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrdersSearch extends Model
{
    public $agency_id;
    public $agent_id;
    public $user_id;
    public $price;
    public $date_from;
    public $date_to;
    public $status;
    public $rating;

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['status','agent_id','user_id','price','rating','agency_id'], 'safe'],
            [['date_from','date_to'],'date', 'format'=>'php:Y-m-d'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Order::find();
        if(!\Yii::$app->user->getIdentity()->isAdmin()){
            $query->andFilterWhere(['in','orders.agency_id',AgencyHelper::getAllowedAgenciesIds()]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'orders.status' => $this->status,
            'orders.agent_id' => $this->agent_id,
            'orders.user_id' => $this->user_id,
            'orders.agency_id' => $this->agency_id,
            'orders.rating' => $this->rating,
        ]);
        $query
        ->andFilterWhere(['>=', 'orders.price', $this->price])
        ->andFilterWhere(['>=', 'orders.created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
        ->andFilterWhere(['<=', 'orders.created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}