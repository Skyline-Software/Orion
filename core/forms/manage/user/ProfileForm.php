<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 16.09.2018
 * Time: 19:34
 */

namespace core\forms\manage\user;


use core\entities\user\vo\Profile;
use yii\base\Model;

class ProfileForm extends Model
{
    public $name;
    public $phone;
    public $sex;
    public $birthday;
    public $photo;
    public $language;

    public function __construct(Profile $profile = null, array $config = [])
    {
        if($profile){
            $this->setAttributes(get_object_vars($profile));
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name'],'string',
                'min'=>4,'max'=>20,
                'tooShort'=>'INCORRECT_STRING_LONG_MIN_4',
                'tooLong'=>'INCORRECT_STRING_LONG_MAX_20'
            ],
            [['phone'],'safe'],
            [['sex'],'in','range' => [0,1],'message' => 'NOT_IN_RANGE_0_OR_1'],
            [['sex'],'integer','message' => 'MUST_BE_INTEGER'],
            [['birthday'],'date','format' => 'php:d.m.y','message' => 'INVALID_FORMAT_D.M.Y'],
            [['language'],'required','message'=>'LANGUAGE_IS_NOT_SET'],
            [['language'],'string',
                'min'=>2,
                'max'=>2,
                'tooLong'=>'LANGUAGE_MUST_BE_2_CHAR',
                'tooShort'=>'LANGUAGE_MUST_BE_2_CHAR'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'ФИО',
            'type' => 'Тип',
            'phone' => 'Номер телефона',
            'sex' => 'Пол',
            'birthday' => 'Дата рождения',
            'language' => 'Язык',
        ];
    }
}