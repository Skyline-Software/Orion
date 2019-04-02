<?php
/* @var $model \core\forms\manage\agency\AgencyForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = 'Создание нового агентства';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(); ?>
            <?= $form->field($model,'web_site')->textInput(); ?>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Ценообразование: </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model,'agent_price')->textInput()->label(false); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model,'agent_metrik')->dropDownList([
                        2 => 'За километр',
                        1 => 'В час'
                    ])->label(false); ?>
                </div>
            </div>

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
