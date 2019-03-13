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
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;
use common\fixtures\UserProfileFixture;
use core\entities\user\UserAuth;

class BuyCertCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'auth' => [
                'class' => UserAuthFixture::className(),
                'depends' => [UserFixture::class,CardFixture::class]
            ],
            'card' => CardFixture::class,
            'cardType' => CardTypeFixture::class
        ]);
    }

    public function buyCert(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/buy/cert',[
            'nominal' => 500
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>[
                'value'=> '500',
            ]
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>[
                'value'=> 'string',
            ]
        ]);
    }

    public function buyCard(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $cardType = $i->grabFixture('cardType','type1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/buy/card',[
            'card_type_id' => $cardType->id
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
        ]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'string'
        ]);
    }



}