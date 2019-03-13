<?php
/* @var $model \core\forms\manage\cert\CertCreateForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use core\entities\cert\FaceValue;
$this->title = 'Создание сертификата/ов';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'count')->textInput(['type'=>'number']) ?>
            <?= $form->field($model,'nominal')->textInput(['type'=>'number']); ?>

            <div class="form-group">
                <?= Html::submitButton('Создать',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
