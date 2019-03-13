<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.11.2018
 * Time: 13:17
 */

namespace core\entities\journal;


use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Journal extends ActiveRecord
{
    public static function change(ActiveRecord $model, string $action, int $manager_id):self
    {
        $change = new static();
        $change->class = $model->classname();
        $change->class_id = $model->id;
        $change->action = $action;
        $change->manager_id = $manager_id;
        $change->created_at = time();
        $change->model_data = $model;

        return $change;
    }

    public function attributeLabels()
    {
        return [
            'action' => 'Действие',
            'manager' => 'Кто совершил',
            'created_at' => 'Время',
        ];
    }

    public function getManager():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'manager_id']);
    }
}