<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 18.12.2018
 * Time: 16:50
 * Номинал сертификата
 */

namespace core\entities\cert;


use yii\base\Model;

class FaceValue extends Model
{
    private $array;
    private $configFile = __DIR__.'/../../../faceValues.json';

    public function __construct()
    {
        $this->array = $this->parseJson();
    }

    private function parseJson():array
    {
        $values = json_decode(file_get_contents($this->configFile), true);
        return $values;
    }

    public function getAll():array
    {
        return $this->array;
    }

    public function getMultidimensional():array
    {
        $arr = [];
        foreach ($this->array as $index=>$value){
            $arr[$value] = $value;
        }

        return $arr;
    }



}