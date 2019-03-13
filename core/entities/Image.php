<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 16:34
 */

namespace core\entities;


class Image implements \core\entities\user\Image
{
    public $config;

    /**
     * Thumbnail constructor.
     * @param string $path
     */
    public function __construct(?string $path)
    {
        $this->config = $this->setConfig($path);
    }

    public function setConfig(?string $path):array
    {
        return [
            'base_url' => \Yii::getAlias('@storageUrl').'/source',
            'path' => is_null($path) ? 'empty.png' : $path
        ];
    }


}
