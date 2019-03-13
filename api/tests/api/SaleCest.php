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
use common\fixtures\CertFixture;
use common\fixtures\PartnerFixture;
use common\fixtures\SalesFixture;
use common\fixtures\UserAuthFixture;
use common\fixtures\UserFixture;
use common\fixtures\UserProfileFixture;
use core\entities\user\UserAuth;

class SaleCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'sale' => SalesFixture::class,
            'partner'=>PartnerFixture::class,
            'card'=>CardFixture::class,
            'cert'=>CertFixture::class,

        ]);
    }

    public function registerWithoutCert(ApiTester $i)
    {
        $i->sendPOST('partner/sale/register',[
            'token' => $i->grabFixture('partner','partner')->token,
            'card_id' => $i->grabFixture('card','card1')->id,
            'amount' => 500
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>[
                "amount"=> "500",
                "saved"=> 50,
                "payed"=> 450,
                "partner"=> $i->grabFixture('partner','partner')->getAttributes(['id','category_id','can_buy_ur','name','description','website','instagram','token','logo','header_photo','adresses','work_time','category','favorite'
                ]),
                "date"=> (new \DateTime())->format('d.m.y')
            ]
        ]);
    }

    public function registerWithCert(ApiTester $i)
    {
        $i->sendPOST('partner/sale/register',[
            'token' => $i->grabFixture('partner','partner')->token,
            'card_id' => $i->grabFixture('card','card1')->id,
            'amount' => 500,
            'cert_num' =>$i->grabFixture('cert','cert1')->number
        ]);
        $i->seeResponseIsJson();
        $i->canSeeResponseJsonMatchesJsonPath('$.status');
        $i->canSeeResponseJsonMatchesJsonPath('$.result');
        $i->seeResponseContainsJson([
            'status'=>'ok',
            'result'=>[
                "amount"=> "500",
                "saved"=> 550,
                "payed"=> -50,
                "partner"=> $i->grabFixture('partner','partner')->getAttributes(['id','category_id','can_buy_ur','name','description','website','instagram','token','logo','header_photo','adresses','work_time','category','favorite'
                ]),
                "date"=> (new \DateTime())->format('d.m.y')
            ]
        ]);
    }



}