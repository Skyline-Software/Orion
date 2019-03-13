<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:41
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;
use core\entities\user\UserAuth;

class LogoutCest
{
    public function _fixtures(): array
    {
        return [
            'userAuth' => [
                'class' => UserAuthFixture::class,
            ],

        ];
    }

    public function test(ApiTester $i)
    {
        $i->amBearerAuthenticated($i->grabFixture('userAuth','user1')->token);

        $i->sendPOST(
            'user/logout'
        );
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');


    }

    public function empty(ApiTester $i)
    {
        $i->sendPOST(
            'user/logout'
        );
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');

    }
}