<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 19.12.2018
 * Time: 12:00
 */

namespace backend\forms\cert;


use core\entities\cert\Cert;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CertSearch extends Model
{
    public $value;
    public $number;
    public $user_id;
    public $partner_id;
    public $date_from;
    public $date_to;
    public $created_at;

    public function rules()
    {
        return [
            [['value','number','user_id','partner_id','created_at','date_to','date_from'],'safe']
        ];
    }



    public function search($params): ActiveDataProvider
    {
        $query = Cert::find();
        $query->orderBy(['id'=>SORT_DESC]);
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
            'value' => $this->value,

        ]);
        $query->andFilterWhere(['LIKE','number',$this->number]);
        if($this->user_id){
            $query->andFilterWhere(['user_id' => (int)$this->user_id]);
        }
        if($this->partner_id){
            $query->andFilterWhere(['partner_id'=>(int)$this->partner_id]);
        }

        $query->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}