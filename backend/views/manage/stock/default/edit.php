<?php
/* @var $model \core\forms\manage\stock\StockForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Редактирование скидки';
?>
<style>
    .field-salesform-config .js-input-remove{
        display:none;
    }
</style>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput()->label('Название'); ?>

            <?= $form->field($model,'card_type_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\card\CardType::find()->all(),'id','name')
            )->label('Тип карты'); ?>

            <?= $form->field($model,'discount')->textInput()->label('Размер скидки'); ?>

            <?= $form->field($model,'from')->widget(\kartik\widgets\DatePicker::class,[
                'attribute' => 'from',
                'attribute2' => 'to',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => 'до',
                'pluginOptions' => [
                    'todayHighlight'=>true,
                    'autoclose' => false,
                    'format'=>'dd.mm.yy',

                ]
            ])->label('Время действия'); ?>

            <?= $form->field($model->partner, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                'min'=>1,
                'columns' => [
                    [
                        'name'  => 'partner_id',
                        'title' => 'Заведение',
                        'type' => \kartik\select2\Select2::class,
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\core\entities\partner\Partner::find()->all(),'id','name'),
                        ],
                        'enableError' => true,
                    ],
                ]
            ])->label('Заведения'); ?>

            <?= $form->field($model->category, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                'min'=>1,
                'columns' => [
                    [
                        'name'  => 'category_id',
                        'title' => 'Категория',
                        'type' => \kartik\select2\Select2::class,
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\core\entities\partner\PartnerCategory::find()->all(),'id','name'),
                        ],
                        'enableError' => true,
                    ],
                ]
            ])->label('Категории'); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>