<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\user\customer\Profile;
use core\entities\user\UserAuth;
use yii\test\ActiveFixture;

class UserAuthFixture extends ActiveFixture
{
    public $modelClass = UserAuth::class;
    public $dataFile = "@api/tests/_data/auth.php";
    public $tableName = 'user_auth';
}