<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 22.08.2018
 * Time: 17:11
 */

namespace core\entities\behaviours;

use company\entities\Color;
use core\entities\contractor\vo\Address;
use core\entities\contractor\vo\Addresses;
use core\entities\services\printing\Format;
use core\entities\services\printing\Printing;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class AddressesBehaviour extends Behavior
{
    public $attribute = 'addresses';
    public $jsonAttribute = 'addresses';

    public function events():array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
        ];
    }

    public function onAfterFind(Event $event):void
    {
        $ar = $event->sender;
        $data = $ar->getAttribute($this->jsonAttribute);
        $addresses = new Addresses();
        if(!empty($data)) {
            foreach ($data as $address) {
                $address = new Address(
                    ArrayHelper::getValue($address, 'type'),
                    ArrayHelper::getValue($address, 'address')
                );
                $addresses->addAddress($address);
            }
        }
        $ar->{$this->attribute} = $addresses;
    }
}