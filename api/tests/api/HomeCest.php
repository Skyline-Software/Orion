<?php

namespace api\tests\api;

use api\tests\ApiTester;

class HomeCest
{
    public function mainPage(ApiTester $I)
    {
        $I->sendGET('');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'version' => '1.0.0'
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.version');
    }
}