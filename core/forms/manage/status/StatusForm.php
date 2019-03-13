<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.11.2018
 * Time: 16:23
 */

namespace core\forms\manage\status;


use core\entities\status\Status;
use yii\base\Model;

class StatusForm extends Model
{
    public $title;
    public $category_id;

    public function __construct(Status $status = null, array $config = [])
    {
        if($status){
            $this->title = $status->title;
            $this->category_id = $status->category_id;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['category_id','title'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'category_id' => 'Категория',
            'title' => 'Название'
        ];
    }


}