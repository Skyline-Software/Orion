<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 20:19
 */

namespace backend\forms\partner;


use core\entities\partner\Partner;
use core\entities\partner\PartnerCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PartnerCategorySearch extends Model
{
    public $name;

    public function rules()
    {
        return [
            [['name'],'string'],
        ];
    }

    public function search($params)
    {
        $query = PartnerCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'like',
            'name',
            $this->name,
        ]);

        return $dataProvider;
    }

}