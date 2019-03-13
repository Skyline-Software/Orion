<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\card\Card;
use core\entities\partner\Partner;
use yii\test\ActiveFixture;

class PartnerFixture extends ActiveFixture
{
    public $modelClass = Partner::class;
    public $dataFile = "@api/tests/_data/partner.php";
    public $tableName = 'partners';
}