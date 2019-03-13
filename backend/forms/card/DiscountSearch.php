<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 18:53
 */

namespace backend\forms\card;


use core\entities\card\CardType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DiscountSearch extends Model
{
    public $partner_id;
    public $category_id;
    public $card_type_id;
    public $discount;
    public $status;

    public function rules()
    {
        return [
            [['partner_id','card_type_id','status','category_id'],'integer'],
            [['discount'],'string']
        ];
    }


    public function search($id, $params)
    {
        $query = CardType::findOne(['id'=>$id]);
        $query = $query->getSales();

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
            'discount' => $this->discount,
            'partner_id' => $this->partner_id,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }

}