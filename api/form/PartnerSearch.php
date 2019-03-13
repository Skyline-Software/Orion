<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 20:19
 */

namespace api\form;


use core\entities\card\CardAndPartner;
use core\entities\partner\Partner;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\JsonExpression;
use yii\helpers\ArrayHelper;
use yii\web\User;

class PartnerSearch extends Model
{
    public $name;
    public $categories;
    public $cardTypes;
    public $favorites;
    public $can_buy_ur;
    public $offset;
    public $count;
    public $x1;
    public $x2;
    public $y1;
    public $y2;
    public $action;
    public $certificats;

    public $action_from;
    public $action_to;

    public $avg_invoice_from;
    public $avg_invoice_to;

    public function rules()
    {
        return [
            [['name'],'string'],
            [['offset','count'],'safe'],
            [['can_buy_ur','favorites'],'integer'],
            [['x1','x2','y1','y2','action','certificats'],'safe'],
            [['avg_invoice_from','avg_invoice_to','action_from','action_to'],'safe'],
        ];
    }

    public function search($params)
    {
        $query = Partner::find();
        $query->andFilterWhere(['partners.status'=>Partner::STATUS_ACTIVE]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);



        $this->setAttributes($params,false);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'partners.can_buy_ur' => $this->can_buy_ur,
        ])
        ->andFilterWhere(['like','partners.name',$this->name]);

        if($this->categories){
            $query->andFilterWhere(['in','partners.category_id',explode(',',$this->categories)]);

        };

        if($this->x1 || $this->x2 || $this->y1 || $this->y2)
        {
            $query->joinWith('adresses adr');
            $query->andFilterWhere(['>','adr.lat',$this->x1]);
            $query->andFilterWhere(['<','adr.lat',$this->x2]);
            $query->andFilterWhere(['>','adr.long',$this->y1]);
            $query->andFilterWhere(['<','adr.long',$this->y2]);
        }

        if($this->favorites == 1){
            $header = ArrayHelper::getValue(getallheaders(),'Authorization');
            $token = str_replace('Bearer','',$header);

            $user = \core\entities\user\User::findIdentityByAccessToken(trim($token));
            $favorites = $user->getFavorites()->select('partner_id')->asArray()->all();
            $favoritesIds = [];
            foreach ($favorites as $key=>$data){
                $favoritesIds[] += ArrayHelper::getValue($data,'partner_id');
            }
            $query->orWhere(['in','partners.id',$favoritesIds]);
        }

        if($this->cardTypes){
            $query->joinWith(['cardTypes cts']);
            $query->orWhere(['in','cts.id',explode(',',$this->cardTypes)]);
        }



        if($this->action){
            $ids = array_map(function ($partner){
                /* @var Partner $partner*/
                return $partner->id;
            },$query->all());
            $query->joinWith('stocks st');
            $query->andFilterWhere(['st.hot'=>1]);
            $query->andFilterWhere(['in','st.partner_id',$ids]);

            /* action_from action_to*/
        }

        if($this->action_from || $this->action_to){
            $query->joinWith('stocks st');
            $query->andFilterWhere(['>=', 'st.discount', $this->action_from]);
            $query->andFilterWhere(['<=', 'st.discount', $this->action_to]);
        }

        if($this->avg_invoice_from || $this->avg_invoice_to){
            $query->andFilterWhere(['>=', 'partners.avg_invoice', $this->avg_invoice_from]);
            $query->andFilterWhere(['<=', 'partners.avg_invoice', $this->avg_invoice_to]);
        }

        if($this->certificats){
            $query->andFilterWhere(['partners.can_accept_cert'=>1]);
        }


        if($this->offset){
            $query->offset = (int)$this->offset;
        }

        if($this->count){
            $query->limit = (int)$this->count;
        }

        $query->orderBy('id');

        return [
            'items'=>$dataProvider,
            'count'=>count($dataProvider->getModels())
        ];
    }

}