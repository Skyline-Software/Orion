<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 16:34
 */

namespace core\entities;


class Images implements \core\entities\user\Image
{
    public $config;

    /**
     * Thumbnail constructor.
     * @param string $path
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

}