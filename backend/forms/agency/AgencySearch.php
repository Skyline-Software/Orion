<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 16:11
 */

namespace backend\forms\agency;


use core\entities\agency\Agency;
use core\entities\user\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AgencySearch extends Model
{
    public $name;
    public $date_from;
    public $date_to;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['status'], 'safe'],
            [['name'], 'string'],
            [['date_from','date_to'],'date', 'format'=>'php:Y-m-d'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Agency::find();
        if(!\Yii::$app->user->getIdentity()->isAdmin()){
            $query->joinWith('userAssn as user_assn');
            $query->andFilterWhere([
                'user_assn.user_id'=>\Yii::$app->user->id,
                'user_assn.role'=>User::ROLE_AGENCY_ADMIN
            ]);
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
        $query->andFilterWhere([
            'agency.status' => $this->status,
        ]);
        $query->andFilterWhere(['LIKE','agency.name',$this->name]);
        $query
        ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
        ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}