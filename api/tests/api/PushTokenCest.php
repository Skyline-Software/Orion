<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:27
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\UserAuthFixture;

class PushTokenCest
{
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserAuthFixture::className(),
            ],
        ];
    }

    public function test(ApiTester $i)
    {
        $i->amBearerAuthenticated($i->grabFixture('user','user1')->token);
        $i->sendPOST(
            'user/push-token',
            [
                'push_token'=>'asd'
            ]
        );
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');

    }

    public function empty(ApiTester $i)
    {
        $i->amBearerAuthenticated($i->grabFixture('user','user1')->token);
        $i->sendPOST(
            'user/push-token',
            [
                'push_token'=>''
            ]
        );
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');

    }
}