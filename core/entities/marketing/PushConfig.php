<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:06
 */

namespace core\entities\marketing;


use yii\db\ActiveRecord;

class PushConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 'push_config';
    }

    public static function create($title,  $message, $card_types, $age, $sex, $partner_categories, $partners, $is_for_already_has_sale):self
    {
        $eConfig = new static();
        $eConfig->title = $title;
        $eConfig->message = $message;
        $eConfig->card_types = $card_types;
        $eConfig->age = $age;
        $eConfig->sex = $sex;
        $eConfig->partner_categories = $partner_categories;
        $eConfig->partners = $partners;
        $eConfig->is_for_already_has_sale = $is_for_already_has_sale;

        return $eConfig;
    }

    public function edit($title, $message, $card_types, $age, $sex, $partner_categories, $partners, $is_for_already_has_sale):void
    {
        $this->title = $title;
        $this->message = $message;
        $this->card_types = $card_types;
        $this->age = $age;
        $this->sex = $sex;
        $this->partner_categories = $partner_categories;
        $this->partners = $partners;
        $this->is_for_already_has_sale = $is_for_already_has_sale;
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'subject' => 'Тема письма',
            'is_for_already_has_sale' => 'Уже есть полученные скидки',
            'card_types' => 'Типы карт',
            'age' => 'Возраст',
            'sex' => 'Пол',
            'partner_categories' => 'Категории заведений',
            'partners' => 'Заведения',
        ];
    }


}