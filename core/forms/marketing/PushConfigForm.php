<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:10
 */

namespace core\forms\marketing;


use core\entities\marketing\EmailConfig;
use yii\base\Model;

class PushConfigForm extends Model
{
    public $title;
    public $message;
    public $card_types;
    public $age;
    public $sex;
    public $partner_categories;
    public $partners;
    public $is_for_already_has_sale;

    public function __construct(EmailConfig $eConfig = null, array $config = [])
    {
        if($eConfig){
            $this->setAttributes($eConfig->getAttributes());
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title','message'],'string'],
            [['is_for_already_has_sale'],'integer'],
            [['card_types','age','sex','partner_categories','partners'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'is_for_already_has_sale' => 'Уже есть полученные скидки',
            'card_types' => 'Типы карт',
            'age' => 'Возраст',
            'sex' => 'Пол',
            'partner_categories' => 'Категории заведений',
            'partners' => 'Заведения',
        ];
    }
}