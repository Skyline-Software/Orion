<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.12.2018
 * Time: 18:38
 */

namespace core\forms\manage\cert;


use core\entities\cert\Cert;
use core\entities\cert\FaceValue;
use core\entities\user\User;
use yii\base\Model;

class CertEditForm extends Model
{
    public $user_id;

    public function __construct(Cert $cert, array $config = [])
    {
        $this->user_id = $cert->user_id;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['user_id'],'required'],
            [['user_id'],'exist','targetClass' => User::class,'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'user_id' => 'Клиент',
        ];
    }
}