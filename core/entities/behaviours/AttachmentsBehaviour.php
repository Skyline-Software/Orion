<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 17:11
 */

namespace core\entities\behaviours;

use core\entities\Image;
use core\entities\Images;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

class AttachmentsBehaviour extends Behavior
{
    /**
     * Key is model attribute, value is json attribute
     * @var array
     */
    public $fields = ['attachments'=>'attachments'];

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
            $ar->{$attribute} = new Images($config);
        }
    }

    public function onBeforeSave(Event $event):void
    {
        /* @var $ar ActiveRecord */
         $ar = $event->sender;
         foreach ($this->fields as $attribute=>$jsonAttribute){
             $ar->setAttribute($jsonAttribute,$ar->{$attribute}->config);
         }
    }
}