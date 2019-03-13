<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:06
 */

namespace core\entities\marketing;


use core\entities\partner\Partner;
use core\entities\user\User;
use yii\db\ActiveRecord;

class EmailConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 'email_config';
    }

    public static function create($title, $subject, $message, $card_types, $age, $sex, $partner_categories, $partners, $is_for_already_has_sale):self
    {
        $eConfig = new static();
        $eConfig->title = $title;
        $eConfig->subject = $subject;
        $eConfig->message = $message;
        $eConfig->card_types = $card_types;
        $eConfig->age = $age;
        $eConfig->sex = $sex;
        $eConfig->partner_categories = $partner_categories;
        $eConfig->partners = $partners;
        $eConfig->is_for_already_has_sale = $is_for_already_has_sale;

        return $eConfig;
    }

    public function edit($title, $subject, $message, $card_types, $age, $sex, $partner_categories, $partners, $is_for_already_has_sale):void
    {
        $this->title = $title;
        $this->subject = $subject;
        $this->message = $message;
        $this->card_types = $card_types;
        $this->age = $age;
        $this->sex = $sex;
        $this->partner_categories = $partner_categories;
        $this->partners = $partners;
        $this->is_for_already_has_sale = $is_for_already_has_sale;
    }

    public function setupRecipients():void
    {
        RecipientList::deleteAll(['config_id'=>$this->id]);

        $query = User::find()->where(['type'=>User::ROLE_CUSTOMER]);
        if($this->card_types){
            $query->joinWith(['cards']);
            $query->andFilterWhere(['in','cards.type_id',$this->card_types]);
        }
        if($this->age){
            $current = date('Y');
            $birth = (int)$current - (int)$this->age;
            $birth = (int)  substr($birth,0,2);
            $query->andFilterWhere(['>=', 'birthday', strtotime( '01.01.'.$birth.' 00:00:00')]);
            $query->andFilterWhere(['<=', 'birthday', strtotime( '31.12.'.$birth.' 00:00:00')]);
        }
        if($this->sex){
            $query->andFilterWhere(['in','sex',$this->sex]);
        }

        if($this->is_for_already_has_sale){
            $partners = Partner::find();
            if($this->partner_categories){
                $partners->andFilterWhere(['in','category_id',$this->partner_categories]);
            }
            if($this->partners){
                $partners->orFilterWhere(['in','id',$this->partners]);
            }
            $partnerIds = array_map(function ($partner){
                return $partner->id;
            },$partners->all());
            if(!empty($partnerIds)){
                $query->joinWith(['sales']);
                $query->andFilterWhere(['in','{{%sales_history}}.partner_id',$partnerIds]);
            }
        }
        $query->andFilterWhere(['status'=>User::STATUS_ACTIVE]);
        if(!empty($query->all())){
            foreach ($query->all() as $recipient){
                $toList = new RecipientList();
                $toList->client_id = $recipient->id;
                $toList->config_id = $this->id;
                $toList->status = RecipientList::NOT_SEND;
                $toList->save();
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'subject' => 'Тема письма/Ссылка',
            'is_for_already_has_sale' => 'Уже есть полученные скидки',
            'card_types' => 'Типы карт',
            'age' => 'Возраст',
            'sex' => 'Пол',
            'partner_categories' => 'Категории заведений',
            'partners' => 'Заведения',
            'message' => 'Сообщение',
        ];
    }


}