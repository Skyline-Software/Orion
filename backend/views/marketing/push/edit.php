<?php
/* @var $model \core\forms\manage\card\CardTypeForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Редактирование шаблона';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'title')->textInput(); ?>
            <?= $form->field($model,'message')->textarea(); ?>
            <?= $form->field($model,'card_types')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\card\CardType::find()->all(),'id','name'),
                ['prompt'=>'Выберите тип карты','multiple'=>true]
            ); ?>

            <?= $form->field($model,'age')->textInput(['type'=>'number']); ?>
            <?= $form->field($model,'sex')->dropDownList(
                [
                    0 => 'Мужской',
                    1 => 'Женский',
                ],
                [
                    'multiple'=>true
                ]
            ); ?>
            <?= $form->field($model,'partner_categories')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\partner\PartnerCategory::find()->all(),'id','name'),
                ['prompt'=>'Выберите тип карты','multiple'=>true]
            ); ?>
            <?= $form->field($model,'partners')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\partner\Partner::find()->all(),'id','name'),
                ['prompt'=>'Выберите тип карты','multiple'=>true]
            ); ?>

            <?= $form->field($model,'is_for_already_has_sale')->checkbox(); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>