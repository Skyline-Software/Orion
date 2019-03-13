<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 13:43
 */

namespace core\forms\manage\stock;


use core\entities\Rows;
use core\entities\stock\Stock;
use core\forms\MultipleInputForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class StockForm
 * @package core\forms\manage\stock
 * @property int $card_type_id;
 *
 * @property string $name;
 * @property string $discount;
 * @property string $from;
 * @property string $to;
 *
 * @property MultipleInputForm $partner;
 * @property MultipleInputForm $category;
 */
class StockForm extends CompositeForm
{
    public $card_type_id;
    public $name;
    public $discount;
    public $from;
    public $to;

    public function __construct(Stock $stock = null, array $config = [])
    {
        if($stock){
            $this->card_type_id = $stock->card_type_id;
            $this->name = $stock->name;
            $this->discount = $stock->discount;
            $this->from = $stock->from;
            $this->to = $stock->to;
            $this->partner = new MultipleInputForm('PartnerForm', new Rows($stock->partner));
            $this->category = new MultipleInputForm('CategoryForm',new Rows($stock->category));
        }else{
            $this->partner = new MultipleInputForm('PartnerForm');
            $this->category = new MultipleInputForm('CategoryForm');
        }
        parent::__construct($config);
    }

    public function rules():array
    {
        return [
            [['card_type_id'],'integer'],
            [['name','discount','from','to'],'string'],
            [['card_type_id','name','discount','from','to'],'required']
        ];
    }

    public function internalForms()
    {
        return ['partner','category'];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'discount' => 'Скидка',
            'from' => 'С',
            'to' => 'До',
        ];
    }
}