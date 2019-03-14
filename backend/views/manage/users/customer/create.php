<?php
/* @var $model \core\forms\manage\user\AdminForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Создание нового клиента';
$this->registerJs("
var select = document.getElementById('customerform-status').getElementsByTagName('option');
select[0].disabled = true;
")
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'email')->textInput(['maxLength'=>'255']); ?>
            <?= $form->field($model,'password')->passwordInput(['maxLength'=>'255']); ?>

            <?= $form->field($model->profile,'name')->textInput(['maxLength'=>'255']); ?>
            <?= $form->field($model->profile,'phone')->widget(\yii\widgets\MaskedInput::class,['mask'=>'+9(999) 999 99 99']); ?>
            <?= $form->field($model->profile,'sex')->dropDownList([
                0 => 'Мужской',
                1 => 'Женский',
            ]); ?>
            <?= $form->field($model,'status')->dropDownList([
                0 => 'Активен',
                -1 => 'Заблокирован',

            ],['prompt'=>'Ожидает активации'])->label('Статус'); ?>
            <?= $form->field($model->profile,'birthday')->widget(\kartik\widgets\DatePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd.mm.yy'
                ]
            ]); ?>

            <?= $form->field($model->photo, 'config')->widget(\common\widgets\upload\Upload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'AdminForm.PhotoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Фото'); ?>

            <?= $form->field($model->profile,'language')->dropDownList([
                'ru' => 'Ru',
                'en' => 'En',
            ]); ?>

            <?= $form->field($model->agencies, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                'min'=>1,
                'columnClass' => \common\widgets\mapPickerMulti\MultipleInputColumn::class,
                'columns' => [
                    [
                        'name'  => 'agency_id',
                        'title' => 'Агентство',
                        'enableError' => true,
                        'type' => \kartik\select2\Select2::class,
                        'options' => [
                            'data' => \core\helpers\AgencyHelper::getAllowedAgencies()
                        ]
                    ],
                    [
                        'name'  => 'role',
                        'type' => 'hiddenInput',
                        'defaultValue' => \core\entities\user\User::ROLE_CUSTOMER
                    ],
                    [
                        'name'  => 'created_at',
                        'type' => 'hiddenInput',
                        'defaultValue' => time()
                    ]
                ]
            ])->label('Агентства'); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
