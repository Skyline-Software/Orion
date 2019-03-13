<?php
/* @var $model \core\forms\manage\partner\PartnerCategoryForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Редактирование категории';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(); ?>

            <?= $form->field($model->icon, 'config')->widget(\core\forms\CUpload::className(),
                [
                    'url' => ['/file-storage/upload', 'type'=>'PartnerCategoryForm.IconForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Иконка'); ?>

            <?= $form->field($model,'parent_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(
                    \core\entities\partner\PartnerCategory::find()
                    ->where(['parent_id'=>0])
                    ->andFilterWhere(['not in','id',[$model->getId()]])
                    ->all(), 'id','name'
                ),[
                        'prompt' => 'Выберите родительскую категорию'
                ]
            ); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>