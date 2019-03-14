<?php
/* @var $model \core\forms\manage\card\CardForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = 'Редактирование агентства';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(); ?>
            <?= $form->field($model,'web_site')->textInput(); ?>
            <?= $form->field($model->logo, 'config')->widget(\core\forms\CUpload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'AgencyForm.LogoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Лого'); ?>
            <?= $form->field($model,'status')->dropDownList(
                [
                    1 => 'Вкл.',
                    0 => 'Выкл.',
                ]
            ); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>