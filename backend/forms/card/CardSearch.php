<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 16:11
 */

namespace backend\forms\card;


use core\entities\card\Card;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CardSearch extends Model
{
    public $type_id;
    public $number;
    public $client_id;
    public $date_from;
    public $date_to;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['type_id', 'client_id','status'], 'safe'],
            [['number'], 'string'],
            [['date_from','date_to'],'date', 'format'=>'php:Y-m-d'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Card::find();
        $query->joinWith(['type ty']);

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
            'ty.id' => $this->type_id,
            'cards.status' => $this->status,
        ]);
        if($this->client_id){
            $query->andFilterWhere(['user_id'=>(int)$this->client_id]);
        };
        $query->andFilterWhere(['like', 'cards.number', $this->number])
        ->andFilterWhere(['>=', 'activated', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
        ->andFilterWhere(['<=', 'activated', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}