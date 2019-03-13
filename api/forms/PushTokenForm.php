<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 13:04
 */

namespace api\forms;


use yii\base\Model;

class PushTokenForm extends Model
{
    public $push_token;

    public function rules()
    {
        return [
            ['push_token', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
          [['push_token'],'required','message' => 'PUSH_TOKEN_IS_EMPTY'],
          [['push_token'],'string','message' => 'PUSH_TOKEN_MUST_BE_A_STRING']
        ];
    }
}