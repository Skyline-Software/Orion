<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 01:38
 */

namespace core\tests\unit\entities\User;


use Codeception\Test\Unit;
use core\entities\user\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::requestSignup(
            $email = 'email@site.com', $password = 'password'
        );

        $this->assertEquals($username,$user->username);
        $this->assertEquals($email,$user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertTrue($user->isActive());
    }
}