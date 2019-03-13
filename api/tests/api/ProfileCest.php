<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 14:20
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;
use common\fixtures\UserProfileFixture;
use core\entities\user\User;

class ProfileCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'auth' => [
                'class' => UserAuthFixture::className(),
                'depends' => [UserFixture::class]
            ],
        ]);
    }



    public function test(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendGET('user/profile');
        $i->seeResponseJsonMatchesJsonPath('$.result');
        $i->seeResponseJsonMatchesJsonPath('$.status');
    }

    public function edit(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/profile/edit',[
            'pass' => '49dcbe2',
            'name' => '1234',
            'phone' => '+380662165871',
            'birthday' => '12.04.19',
            'sex' => "1",
            'language' => 'en',
        ]);
        $sample = [
            'status'=>'ok',
            'result'=>[
                "id"=> 1,
                "email"=> "test@test.com",
                "created_at"=> 1544785297,
                'name' => '1234',
                'phone' => '+380662165871',
                'sex' => "1",
                'birthday' => '12.04.19',
                'photo' => null,
                'language' => 'en',
            ]
        ];
        $i->seeResponseJsonMatchesJsonPath('$.result');
        $i->seeResponseJsonMatchesJsonPath('$.status');
        $i->seeResponseEquals(json_encode($sample,JSON_PRETTY_PRINT));
    }
}