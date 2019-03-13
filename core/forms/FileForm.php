<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 22.08.2018
 * Time: 17:27
 */

namespace core\forms;


use yii\base\Model;
use yii\web\UploadedFile;

class FileForm extends Model
{
    public $files;

    public function __construct(array $files = null, array $config = [])
    {
        $this->files = $files;
        parent::__construct($config);
    }

    public function rules():array
    {
        return [
            ['files','required']
        ];
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate()){
            $this->files = UploadedFile::getInstance($this,'files');
            return true;
        }
        return false;
    }

}