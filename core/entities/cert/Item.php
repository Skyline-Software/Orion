<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 19.12.2018
 * Time: 12:03
 */

namespace core\entities\cert;


class Item implements \core\entities\Item
{
    public $value;
    public $number;

    /**
     * Item constructor.
     * @param $value
     * @param $number
     */
    public function __construct($value, $number)
    {
        $this->value = $value;
        $this->number = $number;
    }

}