<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 21:08
 */

namespace core\forms\manage\partner;


use core\entities\Image;
use core\entities\partner\PartnerCategory;
use core\forms\ImageForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class PartnerCategoryForm
 * @package core\forms\manage\partner
 * @property ImageForm $icon;
 */
class PartnerCategoryForm extends CompositeForm
{
    public $name;
    public $parent_id;
    private $_model;

    public function __construct(PartnerCategory $category = null, array $config = [])
    {
        if($category){
            $this->name = $category->name;
            $this->parent_id = $category->parent_id;
            $this->icon = new ImageForm('IconForm',new Image($category->icon));
            $this->_model = $category;
        }else{
            $this->icon = new ImageForm('IconForm');
        }
        parent::__construct($config);
    }

    public function getId():int
    {
        return $this->_model->id;
    }

    public function rules()
    {
        return [
            [['name'],'string'],
            [['parent_id'],'exist','targetClass' => PartnerCategory::class,'targetAttribute' => 'id'],
            [['parent_id'],'default','value' => 0],
        ];
    }

    public function internalForms()
    {
        return ['icon'];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'parent_id' => 'Родительская категория',
            'icon' => 'Иконка',
        ];
    }
}