<?php
/* @var $model \core\forms\manage\user\AdminForm */
/* @var $this \yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


$this->title = Yii::t('backend','Редактирование профиля');
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
                0 => Yii::t('backend','Мужской'),
                1 => Yii::t('backend','Женский'),
            ]); ?>
            <?= $form->field($model,'status')->dropDownList([
                0 => Yii::t('backend','Активен'),
                -1 => Yii::t('backend','Заблокирован'),

            ],['prompt'=>Yii::t('backend','Ожидает активации')])->label('Статус'); ?>
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
            )->label(Yii::t('backend','Фото')); ?>

            <?= $form->field($model->profile,'language')->dropDownList([
                'ru' => Yii::t('backend','Ru'),
                'en' => Yii::t('backend','En'),
            ]); ?>

            <?= $form->field($model->agencies, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                'min'=>0,
                'columnClass' => \common\widgets\mapPickerMulti\MultipleInputColumn::class,
                'columns' => [
                    [
                        'name'  => 'agency_id',
                        'title' => Yii::t('backend','Агентство'),
                        'enableError' => true,
                        'type' => \kartik\select2\Select2::class,
                        'options' => [
                            'data' => \core\helpers\AgencyHelper::getAllowedAgencies()
                        ]
                    ],
                    [
                        'title' => Yii::t('backend','Роль'),
                        'name'  => 'role',
                        'defaultValue'=>\core\entities\user\User::ROLE_CUSTOMER,
                        'type' => \kartik\select2\Select2::class,
                        'options' => [
                            'data' => \core\helpers\user\UserHelper::agencyRoleList()
                        ]
                    ],
                    [
                        'name'  => 'created_at',
                        'type' => 'hiddenInput',
                        'defaultValue' => time()
                    ]
                ]
            ])->label(Yii::t('backend','Агентства')); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend','Сохранить'),['class'=>'btn btn-primary']); ?>
                <?= Html::a(Yii::t('backend','Cancel'), ['index'], ['class'=>'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>