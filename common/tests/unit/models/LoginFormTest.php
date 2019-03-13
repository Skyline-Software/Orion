<?php

namespace common\tests\unit\models;

use core\forms\auth\LoginForm;
use Yii;
use common\fixtures\UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'email' => 'asd@asd.ru',
            'password' => '49dcbe',
        ]);

        expect('error message should not be set', $model->errors)->hasntKey('password');
    }
}
