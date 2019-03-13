<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 20:38
 */

namespace core\entities\partner;


use core\entities\Image;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class PartnerCategory
 * @package core\entities\partner
 * @property string $name;
 * @property string $parent_id;
 * @property Image $icon;
 */
class PartnerCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'partners_categories';
    }

    public static function create($name):self
    {
        $category = new static();
        $category->name = $name;
        return $category;
    }

    public function setupParent($parent_id):void
    {
        $this->parent_id = $parent_id;
    }

    public function setupIcon($icon):void
    {
        $this->icon = $icon;
    }

    public function edit($name):void
    {
        $this->name = $name;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'parent_id' => 'Родительская категория',
            'icon' => 'Иконка',
        ];
    }

    public function getChild():ActiveQuery
    {
        return $this->hasMany(self::class,['parent_id'=>'id']);
    }

    public function getParent():ActiveQuery
    {
        return $this->hasOne(self::class,['id'=>'parent_id']);
    }

    public function fields()
    {
        return ['id','name','icon','child'=>function($model){
            return $this->getChild()->all();
        }];
    }

}