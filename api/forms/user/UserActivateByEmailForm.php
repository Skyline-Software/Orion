<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 12.12.2018
 * Time: 21:07
 */

namespace api\forms\user;


use yii\base\Model;

class UserActivateByEmailForm extends Model
{
    public $email_confirm_token;


}