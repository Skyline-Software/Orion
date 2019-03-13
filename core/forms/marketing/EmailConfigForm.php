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

class EmailConfigForm extends Model
{
    public $title;
    public $message;
    public $subject;
    public $card_types;
    public $age;
    public $sex;
    public $partner_categories;
    public $partners;
    public $type;
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
            [['title','message','subject'],'string'],
            [['is_for_already_has_sale'],'integer'],
            [['card_types','age','sex','partner_categories','partners','type'],'safe'],
        ];
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
            'type' => 'Тип рассылки',
            'message' => 'Сообщение',
        ];
    }
}