<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 17:27
 */

namespace core\forms;


use core\entities\Image;
use yii\base\Model;

class ImageForm extends Model
{
    public $config;
    public $formName;

    public function __construct(string $formName = 'ImageForm', \core\entities\user\Image $image = null, array $attributes = [])
    {
        if($image){
            $this->config = $image->config;
        }
        $this->formName = $formName;
        parent::__construct($attributes);
    }

    public function formName()
    {
        return $this->formName;
    }

    public function rules():array
    {
        return [
            [['config'],'safe']
        ];
    }
}