<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\card\Card;
use yii\test\ActiveFixture;

class CardFixture extends ActiveFixture
{
    public $modelClass = Card::class;
    public $dataFile = "@api/tests/_data/card.php";
    public $tableName = 'cards';
    public $depends = [
      CardTypeFixture::class,
        StockFixture::class
    ];
}