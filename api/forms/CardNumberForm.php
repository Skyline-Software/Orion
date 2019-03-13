<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 20:44
 */

namespace api\forms;


use core\entities\card\Card;
use yii\base\Model;

class CardNumberForm extends Model
{
    public $number;



    public function rules()
    {
        return [
            ['number', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['number'],'required','message' => 'NUMBER_IS_EMPTY'],

            [['number'],'exist','targetClass' => Card::class,'targetAttribute' => 'number','message' => 'CARD_NOT_FOUND']
        ];
    }
}