<?php
/* @var $model \core\forms\manage\agency\AgencyForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use core\entities\user\User;
$this->title = 'Создание нового заказа';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'agency_id')->widget(Select2::class,[
                    'data' => \core\helpers\AgencyHelper::getAllowedAgencies(),
                    'options' => ['placeholder' => 'Выберите компанию','id'=>'agency-id']
            ]);
             ?>
            <?= $form->field($model,'agent_id')
                ->widget(\kartik\depdrop\DepDrop::class,[
                    'options'=>['id'=>'agent-id'],
                    'type' => 2,
                    'pluginOptions'=>[
                        'depends'=>['agency-id'],
                        'placeholder'=>'Выберите агента...',
                        'url'=>Url::to(['/manage/agency/orders/agents'])
                    ]
                ]); ?>
            <?= $form->field($model,'user_id')
                ->widget(\kartik\depdrop\DepDrop::class,[
                    'options'=>['id'=>'user-id'],
                    'type' => 2,
                    'pluginOptions'=>[
                        'depends'=>['agency-id'],
                        'placeholder'=>'Выберите клиента...',
                        'url'=>Url::to(['/manage/agency/orders/customers'])
                    ]
                ]); ?>
            <?=
            $form->field($model, 'start_coordinates')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
                'key' => 'AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0' ,	// require , Put your google map api key
                'valueTemplate' => '{latitude},{longitude}' , // Optional , this is default result format
                'options' => [
                    'style' => 'width: 100%; height: 400px',  // map canvas width and height
                ] ,
                'enableSearchBox' => true , // Optional , default is true
                'searchBoxOptions' => [ // searchBox html attributes
                    'style' => 'width: 300px;', // Optional , default width and height defined in css coordinates-picker.css
                ],
                'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'), // optional , default is TOP_LEFT
                'mapOptions' => [
                    // google map options
                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                    'mapTypeControl' => true, // Enable Map Type Control
                    'mapTypeControlOptions' => [
                        'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
                        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
                    ],
                    'streetViewControl' => false, // Enable Street View Control
                ],
                'clientOptions' => [
                    // jquery-location-picker options
                    #'radius'    => 300,
                    'addressFormat' => 'street_number',
                ]
            ]);
            ?>
            <?=
            $form->field($model, 'end_coordinates')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
                'key' => 'AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0' ,	// require , Put your google map api key
                'valueTemplate' => '{latitude},{longitude}' , // Optional , this is default result format
                'options' => [
                    'style' => 'width: 100%; height: 400px',  // map canvas width and height
                ] ,
                'enableSearchBox' => true , // Optional , default is true
                'searchBoxOptions' => [ // searchBox html attributes
                    'style' => 'width: 300px;', // Optional , default width and height defined in css coordinates-picker.css
                ],
                'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'), // optional , default is TOP_LEFT
                'mapOptions' => [
                    // google map options
                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                    'mapTypeControl' => true, // Enable Map Type Control
                    'mapTypeControlOptions' => [
                        'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
                        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
                    ],
                    'streetViewControl' => false, // Enable Street View Control
                ],
                'clientOptions' => [
                    // jquery-location-picker options
                    #'radius'    => 300,
                    'addressFormat' => 'street_number',
                ]
            ]);
            ?>
            <?= $form->field($model,'start_time')->widget(\kartik\widgets\DateTimePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => Yii::$app->params['datepickerFormatWithTime']
                ]
            ]); ?>
            <?= $form->field($model,'end_time')->widget(\kartik\widgets\DateTimePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => Yii::$app->params['datepickerFormatWithTime']
                ]
            ]); ?>
            <?= $form->field($model,'price')->textInput(); ?>
            <?= $form->field($model,'comment')->textarea(); ?>
            <?= $form->field($model,'rating')->dropDownList(range(0,5)); ?>
            <?= $form->field($model,'status')->dropDownList(
                \core\entities\agency\Order::STATUS_LIST
            ); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
