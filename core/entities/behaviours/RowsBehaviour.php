<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 17:11
 */

namespace project\entity\behaviours;

use project\entities\Image;
use project\entities\Rows;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class RowsBehaviour extends Behavior
{
    public $fields = ['residence_price'=>'residence_price'];

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
            $ar->{$attribute} = new Rows($config);
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