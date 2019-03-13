<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'core\entities\user\User';
    public $dataFile = '@api/tests/_data/user.php';
}