<?php
/* @var $model \core\forms\manage\card\CardTypeForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Создание нового типа карты';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(['maxLength'=>'255'])->label('Название'); ?>

            <?= $form->field($model,'description')->textarea()->label('Описание'); ?>

            <?= $form->field($model->photo, 'config')->widget(\core\forms\CUpload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'CardTypeForm.PhotoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Фото'); ?>

            <?= $form->field($model,'support_phone')->widget(\yii\widgets\MaskedInput::class,['mask'=>'+9(999) 999 99 99'])->label('Телефон поддержки'); ?>

            <?= $form->field($model,'price')->textInput(['minLength'=>'3','type'=>'number'])->label('Стоимость/руб.'); ?>

            <?= $form->field($model,'validity')->textInput(['minLength'=>'3','type'=>'number'])->label('Срок действия/дней'); ?>


            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
