<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.12.2018
 * Time: 18:38
 */

namespace core\forms\manage\cert;


use core\entities\cert\FaceValue;
use yii\base\Model;

class CertCreateForm extends Model
{
    public $count;
    public $nominal;

    public function rules()
    {
        return [
            [['count','nominal'],'required'],
            ['nominal', 'compare', 'compareValue' => (new FaceValue())->getAll()[0], 'operator' => '>=', 'type' => 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'count' => 'Кол-во сертификатов',
          'nominal' => 'Номинал'
        ];
    }
}