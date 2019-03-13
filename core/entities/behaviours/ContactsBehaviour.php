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
use core\entities\contractor\vo\Contact;
use core\entities\contractor\vo\Contacts;
use core\entities\services\printing\Format;
use core\entities\services\printing\Printing;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ContactsBehaviour extends Behavior
{
    public $attribute = 'contacts';
    public $jsonAttribute = 'contacts';

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
        $contacts = new Contacts();
        foreach ($data as $item){
            $contact = new Contact(
                ArrayHelper::getValue($item,'name'),
                ArrayHelper::getValue($item,'email'),
                ArrayHelper::getValue($item,'position'),
                ArrayHelper::getValue($item,'phone')
            );
            $contacts->addContact($contact);
        }
        $ar->{$this->attribute} = $contacts;
    }
}