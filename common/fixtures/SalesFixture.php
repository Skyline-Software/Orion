<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\sales\Sales;
use yii\test\ActiveFixture;

class SalesFixture extends ActiveFixture
{
    public $modelClass = Sales::class;
    public $dataFile = "@api/tests/_data/sales.php";
    public $tableName = 'sales_history';
    public $depends = [
      'card'=>CardFixture::class,
      'partner'=>PartnerFixture::class
    ];
}