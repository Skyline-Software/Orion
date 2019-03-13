<?php
/* @var $model \core\forms\manage\card\CardTypeForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = 'Редактирование сертификата';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'user_id')->widget(\kartik\widgets\Select2::class,
                [
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'initValueText' => 'Выберите клиента', // set the initial display text
                    'options' => [
                        'placeholder' => 'Выберите клиента',
                    ],
                    'data'=>\yii\helpers\ArrayHelper::map(
                        \core\entities\user\User::find()->where(['id'=>$model->user_id])->all(),'id','name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => 'ru',
                        'ajax' => [
                            'url' => \yii\helpers\Url::toRoute(['site/user-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ]
                ]
            ); ?>
            <div class="form-group">
                <?= Html::submitButton('Назначить',['class'=>'btn btn-primary']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>