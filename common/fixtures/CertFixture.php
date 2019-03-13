<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 14.12.2018
 * Time: 13:15
 */

namespace common\fixtures;


use core\entities\card\Card;
use core\entities\cert\Cert;
use yii\test\ActiveFixture;

class CertFixture extends ActiveFixture
{
    public $modelClass = Cert::class;
    public $dataFile = "@api/tests/_data/cert.php";
    public $tableName = 'certs';
    public $depends = [
      UserFixture::class
    ];
}