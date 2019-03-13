<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 20:19
 */

namespace backend\forms\partner;


use core\entities\partner\Partner;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PartnerSearch extends Model
{
    public $name;
    public $category_id;
    public $can_buy_ur;

    public function rules()
    {
        return [
            [['name'],'string'],
            [['category_id','can_buy_ur'],'integer']
        ];
    }

    public function search($params)
    {
        $query = Partner::find();

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
            'category_id' => $this->category_id,
            'can_buy_ur' => $this->can_buy_ur,
        ])
        ->andFilterWhere(['like','name',$this->name]);

        return $dataProvider;
    }

}