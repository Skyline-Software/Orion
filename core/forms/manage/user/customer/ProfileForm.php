<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 27.08.2018
 * Time: 12:06
 */

namespace core\forms\manage\user\customer;


use core\entities\user\customer\Profile;
use yii\base\Model;

class ProfileForm extends Model
{
    public $name;
    public $phone;
    public $address;

    private $_ar;

    public function __construct(Profile $ar = null, array $config = [])
    {
        if($ar){
            $this->name = $ar->name;
            $this->phone = $ar->phone;
        }
        $this->_ar = $ar;
        parent::__construct($config);
    }

    public function isNew():bool
    {
        return is_null($this->_ar);
    }

    public function rules()
    {
        return [
            [['name','phone','address'], 'string', 'max'=>'255']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'ФИО',
            'phone' => 'Номер телефона',
            'address' => 'Адрес',
        ];
    }
}