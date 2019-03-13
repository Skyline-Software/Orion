<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\card\Card;
use core\entities\card\CardType;
use yii\test\ActiveFixture;

class CardTypeFixture extends ActiveFixture
{
    public $modelClass = CardType::class;
    public $dataFile = "@api/tests/_data/cardType.php";
    public $tableName = 'card_type';
}