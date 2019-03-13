<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 17:21
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\CardFixture;
use common\fixtures\CardTypeFixture;
use common\fixtures\PartnerFixture;
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;
use common\fixtures\UserProfileFixture;
use core\entities\partner\Partner;
use core\entities\user\UserAuth;

class CertCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'auth' => [
                'class' => UserAuthFixture::className(),
                'depends' => [UserFixture::class]
            ],
            'partner' => PartnerFixture::class,
        ]);
    }

    public function list(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('cert/list');
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }

    public function listNotLogged(ApiTester $i)
    {
        $i->sendPOST('cert/list');
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

    public function values(ApiTester $i)
    {
        $i->sendPOST('cert/value');
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }
}