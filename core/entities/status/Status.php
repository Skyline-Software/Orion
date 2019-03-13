<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.11.2018
 * Time: 16:08
 */

namespace core\entities\status;


use yii\db\ActiveRecord;

/**
 * Class Status
 * @package core\entities\status
 * @property int $category_id
 * @property string $title
 */
class Status extends ActiveRecord
{
    CONST CATEGORY_PROPOSAL = 1;
    CONST CATEGORY_DEAL = 2;

    CONST CATEGORIES = [
        self::CATEGORY_DEAL => 'Сделка',
        self::CATEGORY_PROPOSAL => 'Заявка'
    ];

    public function create(string $title, int $category):self
    {
        $status = new static();
        $status->title = $title;
        $status->category_id = $category;

        return $status;
    }

    public function edit(string $title, int $category):void
    {
        $this->title = $title;
        $this->category_id = $category;
    }

    public function attributeLabels()
    {
        return [
            'category_id' => 'Категория',
            'title' => 'Название'
        ];
    }
}