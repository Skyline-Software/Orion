<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 17:21
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\UserFixture;

class ResetCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
            ],
        ]);
    }

    public function reset(ApiTester $i)
    {
        $i->sendPOST('user/reset/request',[
            'email' => $i->grabFixture('user','user1')->email
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }

    public function resetEmptyEmail(ApiTester $i)
    {
        $i->sendPOST('user/reset/request',[
            'email' => ''
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

}