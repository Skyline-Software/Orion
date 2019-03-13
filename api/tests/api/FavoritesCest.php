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

class FavoritesCest
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

    public function addToFavorites(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $partner = $i->grabFixture('partner','partner');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/favorites/add',[
            'partner_id' => $partner->id
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }

    public function addToFavoritesEmpty(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/favorites/add');
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

    public function addToFavoritesNotLogged(ApiTester $i)
    {
        $partner = $i->grabFixture('partner','partner');
        $i->sendPOST('user/favorites/add',[
            'partner_id' => $partner->id
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

    public function removeFromFavorites(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $partner = $i->grabFixture('partner','partner');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/favorites/remove',[
            'partner_id' => $partner->id
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }

    public function removeFromFavoritesEmpty(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/favorites/remove');
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

    public function removeFromFavoritesNotLogged(ApiTester $i)
    {
        $partner = $i->grabFixture('partner','partner');
        $i->sendPOST('user/favorites/remove',[
            'partner_id' => $partner->id
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }






}