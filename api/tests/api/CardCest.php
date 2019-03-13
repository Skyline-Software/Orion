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

class CardCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'auth' => [
                'class' => UserAuthFixture::className(),
                'depends' => [UserFixture::class,CardFixture::class]
            ],
            'card' => CardFixture::class,
        ]);
    }

    public function list(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('card/list');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'array'
        ]);
    }

    public function types(ApiTester $i)
    {
        $i->sendPOST('card/types');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'array'
        ]);
    }

    public function discounts(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $card = $i->grabFixture('card','card1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('card/discounts',[
            'number' => $card->number
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'array'
        ]);
    }

    public function listNotLogged(ApiTester $i)
    {
        $i->sendPOST('card/list');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'fail',
            'code'=>'NOT_LOGGED_IN'
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function values(ApiTester $i)
    {
        $i->sendPOST('cert/value');
        $i->seeResponseIsJson();
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'array'
        ]);
    }

    public function activate(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $card = $i->grabFixture('card','card2');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");

        $i->sendPOST('card/activate',[
            'number' => $card->number
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>''
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
        ]);
    }

    public function activateShort(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $card = $i->grabFixture('card','short');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");

        $i->sendPOST('card/activate',[
            'number' => $card->number
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'fail',
            'code'=>'INVALID_INPUT',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string',
            'errors'=>'array'
        ]);
    }

    public function activateLong(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $card = $i->grabFixture('card','long');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");

        $i->sendPOST('card/activate',[
            'number' => $card->number
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'fail',
            'code'=>'INVALID_INPUT',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string',
            'errors'=>'array'
        ]);
    }

    public function deactivate(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $card = $i->grabFixture('card','card1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");

        $i->sendPOST('card/deactivate',[
            'number' => $card->number
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>''
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
        ]);
    }


}