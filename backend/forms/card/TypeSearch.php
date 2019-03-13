<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 14:57
 */

namespace backend\forms\card;


use core\entities\card\CardType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TypeSearch
 * @package backend\forms\card
 * @property integer $id;
 * @property string $name;
 * @property integer $price;
 * @property integer $validity
 */
class TypeSearch extends Model
{
    public $id;
    public $name;
    public $price;
    public $validity;


    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['price', 'validity', 'id'], 'integer'],
            [['name'], 'string'],
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
        $query = CardType::find();

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
            'price' => $this->price,
            'validity' => $this->validity,

        ])
        ->andFilterWhere(['like','name',$this->name]);

        return $dataProvider;
    }
}