<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 16:34
 */

namespace core\entities;


class Rows
{
    public $config;

    /**
     * Thumbnail constructor.
     * @param $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
    }


}