<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\user\User;

/**
 * UserSearch represents the model behind the search form of `core\entities\user\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $email;
    public $status;
    public $date_from;
    public $date_to;
    public $created_at;
    public $type;

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['id', 'status', 'created_at','type'], 'integer'],
            [['email'], 'safe'],
            [['date_from','date_to'],'date', 'format'=>'php:Y-m-d']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

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
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
        ]);

        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
