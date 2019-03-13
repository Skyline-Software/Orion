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

class ActivateCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
            ],
        ]);
    }

    public function activate(ApiTester $i)
    {
        $i->sendPOST('user/login',[
            'email' => $i->grabFixture('user','user2')->email,
            'pass' => '49dcbe',
            'device' => 'ios',
            'code' => $i->grabFixture('user','user2')->status,
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'string'
        ]);
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
    }

    public function banned(ApiTester $i)
    {
        $i->sendPOST('user/login',[
            'email' => $i->grabFixture('user','user3')->email,
            'pass' => '49dcbe',
            'device' => 'ios',
        ]);
        $i->seeResponseIsJson();
        $i->seeResponseCodeIs(200);
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }

    public function bannedWithCode(ApiTester $i)
    {
        $i->sendPOST('user/login',[
            'email' => $i->grabFixture('user','user3')->email,
            'pass' => '49dcbe',
            'device' => 'ios',
            'code' => 999222333
        ]);
        $i->seeResponseIsJson();
        $i->seeResponseCodeIs(200);
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.code');
    }



}