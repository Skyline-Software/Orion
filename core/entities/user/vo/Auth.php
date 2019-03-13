<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.12.2018
 * Time: 18:36
 */

namespace core\entities\user\vo;


/**
 * Class Auth
 * @package core\entities\user\vo
 * @property string $email
 * @property string $password
 */
class Auth
{
    public $email;
    public $password;

    /**
     * User constructor.
     * @param $email
     * @param $password
     */
    public function __construct(string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}