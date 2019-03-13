<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 20:19
 */

namespace backend\forms;


use core\entities\card\Card;
use core\entities\partner\Partner;
use core\entities\sales\Sales;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SaleSearch extends Model
{
    public $card_number;
    public $partner_id;
    public $amount;
    public $saved;
    public $user_id;
    public $date_from;
    public $date_to;
    public $category_id;

    public function rules()
    {
        return [
            [['card_number','partner_id','amount','saved','user_id','category_id','created_at','date_from','date_to'],'safe'],
        ];
    }

    public function search($params)
    {
        $query = Sales::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if($this->card_number){
            $card = Card::findOne(['number'=>$this->card_number]);
            $query->andFilterWhere(['sales_history.card_id'=>$card->id]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'sales_history.partner_id' => $this->partner_id,
            'sales_history.amount' => $this->amount,
            'sales_history.saved' => $this->saved,
        ]);
        if($this->user_id){
            $query->andFilterWhere(['sales_history.user_id' => (int)$this->user_id]);
        }

        if($this->category_id){
            $query->joinWith('partner p');
            $query->andFilterWhere(['p.category_id'=>$this->category_id]);
        };

        $query->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
        ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }

}