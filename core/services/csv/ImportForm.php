<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 18:08
 */

namespace core\services\csv;


use yii\base\Model;

class ImportForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('uploads/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }

}