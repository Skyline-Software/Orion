<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 01.02.2019
 * Time: 13:59
 */

namespace core\helpers;


use core\entities\partner\PartnerCategory;
use yii\helpers\ArrayHelper;

class PartnerCategoryHelper
{
    public static function getCategoryTree():array
    {
        $cats = PartnerCategory::find()->all();
        $tree = [];
        foreach ($cats as $cat){
            /* @var PartnerCategory $cat*/
            if($cat->parent_id === 0){
                $tree[$cat->id] = [
                    'id'=> $cat->id,
                    'label' => $cat->name
                ];
                if($cat->child){
                    $tree[$cat->id]['items'] = array_map(function ($childCat) use ($tree,$cat){
                        return [
                            'id'=> $childCat->id,
                            'label' => $childCat->name
                        ];
                    },$cat->child);
                }
            }

        }
        return $tree;
    }
}