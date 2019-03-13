<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.11.2018
 * Time: 13:12
 */

namespace core\entities\status;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class StatusAssn extends ActiveRecord
{
    public static function assign(ActiveRecord $model, int $status):self
    {
        if($model->status){
            $assign = $model->statusAssn;
            $assign->status = $status;
        }else{
            $assign = new static();
            $assign->class = $model->classname();
            $assign->class_id = $model->id;
            $assign->status = $status;
        }
        return $assign;
    }

    public function getStatus():ActiveQuery
    {
        return $this->hasOne(Status::class,['id'=>'status_id']);
    }


    public static function tableName()
    {
        return 'status_assn';
    }

    public function behaviors()
    {
        return [
          [
              'class' => SaveRelationsBehavior::class,
              'relations' => [
                  'status'
              ]
          ]
        ];
    }
}