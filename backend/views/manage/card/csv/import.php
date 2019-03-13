<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 18:12
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Импорт карт';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'file')->fileInput(); ?>
            <div class="form-group">
                <?= Html::submitButton('Загрузить',['class'=>'btn btn-primary']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
