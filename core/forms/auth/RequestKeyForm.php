<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.09.2018
 * Time: 02:04
 */

namespace core\forms\auth;


use Yii;
use yii\base\Model;

class RequestKeyForm extends Model
{
    public $phone;

    public function rules()
    {
        return [
            [['phone'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('backend','Номер телефона')
        ];
    }
}