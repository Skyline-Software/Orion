<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 17:11
 */

namespace core\entities\behaviours;

use core\entities\Image;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ImageBehaviour extends Behavior
{
    /**
     * Key is model attribute, value is json attribute
     * @var array
     */
    public $fields = ['thumbnail'=>'thumbnail'];

    public function events():array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event):void
    {
        $ar = $event->sender;
        foreach ($this->fields as $attribute=>$jsonAttribute){
            $config = $ar->getAttribute($jsonAttribute);
            $ar->{$attribute} = new Image(
                [
                    'path'=> ArrayHelper::getValue($config,'base_path'),
                    'base_url' =>ArrayHelper::getValue($config, 'base_url')
                ]
            );
        }
    }

    public function onBeforeSave(Event $event):void
    {
        /* @var $ar ActiveRecord */
         $ar = $event->sender;

         foreach ($this->fields as $attribute=>$jsonAttribute){
             $ar->setAttribute($jsonAttribute, [
                 'base_path'=> ArrayHelper::getValue($ar->{$attribute}->config,'path'),
                 'base_url'=>ArrayHelper::getValue($ar->{$attribute}->config,'base_url')
             ]);
         }
    }
}