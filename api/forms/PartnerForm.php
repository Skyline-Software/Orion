<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 12:41
 */

namespace api\forms;


use yii\base\Model;

class PartnerForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            ['id', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['id','required','message' => '{attribute}_IS_EMPTY'],
            ['id','integer','message' => '{attribute}_MUST_BE_INTEGER'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID'
        ];
    }
}