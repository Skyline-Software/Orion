<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 13:50
 */

namespace backend\forms\stock;


use core\entities\stock\Stock;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class StockSearch extends Model
{
    public $name;
    public $from;
    public $to;

    public function rules():array
    {
        return [
            [['name'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Stock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}