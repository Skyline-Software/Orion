<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 13:25
 */

namespace api\forms;


use yii\base\Model;

class BuyCardForm extends Model
{
    public $card_type_id;
    public $name;
    public $phone;
    public $address;

    public function rules()
    {
        return [
            ['card_type_id', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['card_type_id'],'required','message' => 'CARD_TYPE_ID_IS_EMPTY'],
            [['card_type_id'],'integer','message' => 'CARD_TYPE_ID_MUST_BE_A_INTEGER'],
            [['name','phone','address'],'safe']
        ];
    }
}