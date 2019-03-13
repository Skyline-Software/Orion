<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 05.09.2018
 * Time: 19:47
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin(['id' => 'form-signup']);

echo $form->field($causeForm, 'text')->textarea()->label('Причина');

echo Html::submitButton('Отменить', ['class' => 'btn btn-danger']);

ActiveForm::end();


?>
