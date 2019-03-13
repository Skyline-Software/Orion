<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 09.11.2018
 * Time: 19:26
 */

namespace core\forms;

use yii\helpers\Html;
use trntv\filekit\widget\Upload;

class CUpload extends Upload
{
    public function run()
    {
        $this->registerClientScript();
        $content = Html::beginTag('div');
        /*$content .= Html::hiddenInput($this->name, null, [
            'class' => 'empty-value',
            'id' => $this->hiddenInputId === null ? $this->options['id'] : $this->hiddenInputId
        ]);*/
        $content .= Html::fileInput($this->getFileInputName(), null, [
            'name' => $this->getFileInputName(),
            'id' => $this->getId(),
            'multiple' => $this->multiple
        ]);
        $content .= Html::endTag('div');
        return $content;
    }

}