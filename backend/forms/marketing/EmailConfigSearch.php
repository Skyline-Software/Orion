<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:15
 */

namespace backend\forms\marketing;


use core\entities\marketing\EmailConfig;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EmailConfigSearch extends Model
{
    public $title;

    public function rules()
    {
        return [
            [['title'],'string'],
        ];
    }

    public function search($params)
    {
        $query = EmailConfig::find();

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
            'like',
            'title',
            $this->title,
        ]);

        return $dataProvider;
    }
}