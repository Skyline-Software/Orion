<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 13:25
 */

namespace api\forms;


use core\entities\cert\FaceValue;
use yii\base\Model;

class BuyCertForm extends Model
{
    public $nominal;

    public function rules()
    {
        return [
            ['nominal', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['nominal'],'required','message' => 'NOMINAL_IS_EMPTY'],
            ['nominal', 'compare', 'compareValue' => (new FaceValue())->getAll()[0], 'operator' => '>=', 'type' => 'number', 'message' => 'NOMINAL_NOT_IN_RANGE'],
            #[['nominal'],'in','range' => (new FaceValue())->getAll(), 'message' => 'NOMINAL_NOT_IN_RANGE'],
        ];
    }
}