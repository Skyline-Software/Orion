<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\user\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form of `core\entities\user\User`.
 */
class AgencyAdminSearch extends Model
{
    public $id;
    public $email;
    public $name;
    public $phone;

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['id'], 'integer'],
            [['email','name','phone'], 'safe'],
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
        $query->joinWith('agencyAssn agency_assn');
        $query->where(['agency_assn.role' => User::ROLE_AGENCY_ADMIN]);

        if(!\Yii::$app->user->getIdentity()->isAdmin()){
            $query->andFilterWhere(['in','agency_assn.agency_id',
                array_map(function($item){
                    return $item['id'];
                },\Yii::$app->user->getIdentity()->getAgencies()->select(['id'])->asArray()->all())]);
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
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
