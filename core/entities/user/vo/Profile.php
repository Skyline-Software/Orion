<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.12.2018
 * Time: 18:33
 */

namespace core\entities\user\vo;


class Profile
{
    public $name;
    public $phone;
    public $sex;
    public $birthday;
    public $photo;
    public $language;


    /**
     * Profile constructor.
     * @param $name
     * @param $phone
     * @param $sex
     * @param $birthday
     * @param $language
     * @param $photo
     */
    public function __construct($name, $phone, $sex, $birthday, $language, $photo)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->sex = $sex;
        $this->birthday = $birthday;
        $this->photo = $photo;
        $this->language = $language;
    }


}