<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 14:51
 */

namespace core\forms\manage\card;


use core\entities\card\CardType;
use core\entities\Image;
use core\entities\Rows;
use core\forms\ImageForm;
use core\forms\MultipleInputForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class CardTypeForm
 * @package core\forms\manage\card
 * @property string $name;
 * @property string $description;
 * @property ImageForm $photo;
 * @property MultipleInputForm $sales;
 * @property string $support_phone;
 * @property integer $price;
 * @property string $validity;
 */
class CardTypeForm extends CompositeForm
{
    public $name;
    public $description;
    public $support_phone;
    public $price;
    public $validity;

    public function __construct(CardType $cardType = null, array $config = [])
    {
        if($cardType){
            $this->name = $cardType->name;
            $this->description = $cardType->description;
            $this->support_phone = $cardType->support_phone;
            $this->price = $cardType->price;
            $this->validity = $cardType->validity;
            $this->photo = new ImageForm('PhotoForm',new Image($cardType->photo));
        }else{
            $this->photo = new ImageForm('PhotoForm');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name','description','support_phone'],'string'],
            [['price','validity'],'integer']
        ];
    }

    public function internalForms()
    {
        return ['photo'];
    }

}