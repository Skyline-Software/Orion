<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 12:04
 */

namespace api\tests\api\user;


use api\tests\ApiTester;
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;

class AuthCest
{

    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'depends' => [
                    'auth' =>UserAuthFixture::class
                ]
            ],
            'auth' =>UserAuthFixture::class
        ];
    }

    public function registration(ApiTester $i)
    {
        $user = $i->grabFixture('user','user1');
        $i->sendPOST('user/registration',[
            'email' => $user->email.'sad',
            'password' => '49dcbe',
            'name' => $user->name,
            'phone' => $user->phone,
            'sex' => $user->sex,
            'birthday' => $user->birthday,
            'language' => $user->language
        ]);



        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>[
                'id'=>4,
                'email'=> $user->email.'sad',
                'name'=>$user->name,
                'phone' => $user->phone,
                'sex' => $user->sex,
                'birthday' => $user->birthday,
                'language' => $user->language
        ]]);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>[
                'id'=>'integer',
                'email'=>'string',
                'created_at'=>'integer',
                'name'=>'string',
                'phone'=>'string|null',
                'sex'=>'string|null',
                'birthday'=>'string|null',
                'photo'=>'string|null',
                'language'=>'string|null'
            ]
        ]);

    }

    public function registrationImage(ApiTester $i)
    {
        $user = $i->grabFixture('user','user1');
        $i->sendPOST('user/registration',[
            'email' => $user->email.'sad',
            'password' => '49dcbe',
            'name' => $user->name,
            'phone' => $user->phone,
            'sex' => $user->sex,
            'birthday' => $user->birthday,
            'language' => $user->language
        ],['photo'=>\Yii::getAlias('@storage').'/web/source/empty.png']);

        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>[
                'id'=>'integer',
                'email'=>'string',
                'created_at'=>'integer',
                'name'=>'string',
                'phone'=>'string|null',
                'sex'=>'string|null',
                'birthday'=>'string|null',
                'photo'=>'string|null',
                'language'=>'string|null'
            ]
        ]);
    }

    public function registrationLangEmpty(ApiTester $i)
    {
        $user = $i->grabFixture('user','user1');
        $i->sendPOST('user/registration',[
            'email' => $user->email.'sad',
            'password' => '49dcbe',
            'name' => $user->name,
            'phone' => $user->phone,
            'sex' => $user->sex,
            'birthday' => $user->birthday,
            'language' => ''
        ],['photo'=>\Yii::getAlias('@storage').'/web/source/empty.png']);

        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>[
                'id'=>'integer',
                'email'=>'string',
                'created_at'=>'integer',
                'name'=>'string',
                'phone'=>'string|null',
                'sex'=>'string|null',
                'birthday'=>'string|null',
                'photo'=>'string|null',
                'language'=>'string|null'
            ]
        ]);
    }




    public function login(ApiTester $i){
        $user = $i->grabFixture('user','user1');
        $i->sendPOST('user/login',[
            'email' =>$user->email,
            'pass' => '49dcbe',
            'device' => 'ios'
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'string'
        ]);
    }

    public function loginDeviceEmpty(ApiTester $i){
        $user = $i->grabFixture('user','user1');
        $i->sendPOST('user/login',[
            'email' =>$user->email,
            'pass' => '49dcbe',
            'device' => ''
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'string'
        ]);
    }



    public function loginWithInvalidPass(ApiTester $i){
        $i->sendPOST('user/login',[
            'email' => 'asd@asd.ru',
            'pass' => '49dcbe2',
            'device' => 'ios'
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function loginWithInvalidEmail(ApiTester $i){
        $i->sendPOST('user/login',[
            'email' => 'asd',
            'pass' => '49dcbe',
            'device' => 'ios'
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function loginWithInvalidDevice(ApiTester $i){
        $i->sendPOST('user/login',[
            'email' => 'asd@asd.ru',
            'pass' => '49dcbe',
            'device' => 'koko'
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function loginWithEmptyData(ApiTester $i){
        $i->sendPOST('user/login',[
            'email' => '',
            'pass' => '',
            'device' => ''
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function loginUserNotFound(ApiTester $i){
        $i->sendPOST('user/login',[
            'email' => \Yii::$app->security->generateRandomString(4).'@test.com',
            'pass' => '49dcbe',
            'device' => 'ios'
        ]);
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'fail']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'code'=>'string'
        ]);
    }

    public function logout(ApiTester $i)
    {
        $auth = $i->grabFixture('auth','user1');
        $i->haveHttpHeader('Authorization',"Bearer {$auth->token}");
        $i->sendPOST('user/logout');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status'=>'ok']);
        $i->seeResponseMatchesJsonType([
            'status'=>'string',
            'result'=>'string'
        ]);
    }
}