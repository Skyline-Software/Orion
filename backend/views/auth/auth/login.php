<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \core\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('backend','Вход');
$this->params['breadcrumbs'][] = $this->title;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<div class="login-box">
    <div class="login-logo">
        <a href="/"><img src="/logo.png"></a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('backend','Пожалуйста заполните данные чтобы войти в систему:'); ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'email', $fieldOptions1)->textInput(['autofocus' => true])->label('E-mail') ?>

        <?= $form->field($model, 'password', $fieldOptions2)->passwordInput()->label(Yii::t('backend','Пароль')) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox(['label' => Yii::t('backend','Запомнить меня')]) ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('backend','Войти'), ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

    </div>
</div>
