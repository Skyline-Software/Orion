<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\card\Card;
use core\entities\card\CardAndPartner;
use yii\test\ActiveFixture;

class StockFixture extends ActiveFixture
{
    public $modelClass = CardAndPartner::class;
    public $dataFile = "@api/tests/_data/stock.php";
    public $tableName = 'stocks';
}