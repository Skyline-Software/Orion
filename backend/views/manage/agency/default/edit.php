<?php
/* @var $model \core\forms\manage\card\CardForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = Yii::t('backend','Редактирование агентства');
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(); ?>
            <?= $form->field($model,'web_site')->textInput(); ?>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label"><?= Yii::t('backend','Ценообразование:'); ?> </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model,'agent_price')->textInput()->label(false); ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model,'agent_metrik')->dropDownList([
                        2 => Yii::t('backend','За километр'),
                        1 => Yii::t('backend','В час')
                    ])->label(false); ?>
                </div>
            </div>
            <?= $form->field($model->logo, 'config')->widget(\core\forms\CUpload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'AgencyForm.LogoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label(Yii::t('backend','Лого')); ?>
            <?= $form->field($model,'status')->dropDownList(
                [
                    1 => Yii::t('backend','Вкл.'),
                    0 => Yii::t('backend','Выкл.'),
                ]
            ); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend','Сохранить'),['class'=>'btn btn-primary']); ?>
                <?= Html::a(Yii::t('backend','Cancel'), ['index'], ['class'=>'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>