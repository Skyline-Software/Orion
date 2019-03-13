<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 15:45
 */

namespace core\tests\unit\entities\User;


use Codeception\Test\Unit;
use core\entities\user\User;

class ConfirmSignupTest extends Unit
{
    public function testSuccess(){
        $user = new User([
            'status' => User::STATUS_WAIT,
            'email_confirm_token' => 'token'
        ]);

        $user->confirmSignup();

        $this->assertEmpty($user->email_confirm_token);
        $this->assertFalse($user->isWait());
        $this->assertTrue($user->isActive());
    }

    public function testAlreadyActive(){
        $user = new User([
            'status' => User::STATUS_WAIT,
            'email_confirm_token' => null
        ]);

        $this->expectExceptionMessage('user is already active.');

        $user->confirmSignup();
    }
}