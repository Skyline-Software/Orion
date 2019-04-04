<?php
/* @var $model \core\forms\manage\user\AgencyAdminForm */
/* @var $this \yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
$this->title = Yii::t('backend','Создание нового агента');
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'email')->textInput(['maxLength'=>'255']); ?>
            <?= $form->field($model,'password')->passwordInput(['maxLength'=>'255']); ?>
            <?= $form->field($model,'price')->textInput(['maxLength'=>'255']); ?>

            <?= $form->field($model->profile,'name')->textInput(['maxLength'=>'255']); ?>
            <?= $form->field($model->profile,'phone')->widget(\yii\widgets\MaskedInput::class,['mask'=>'+9(999) 999 99 99']); ?>
            <?= $form->field($model->profile,'sex')->dropDownList([
                0 => Yii::t('backend','Мужской'),
                1 => Yii::t('backend','Женский'),
            ]); ?>
            <?= $form->field($model,'status')->dropDownList([
                0 => Yii::t('backend','Активен'),
                -1 => Yii::t('backend','Заблокирован'),

            ])->label(Yii::t('backend','Статус')); ?>
            <?= $form->field($model->profile,'birthday')->widget(\kartik\widgets\DatePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => Yii::$app->params['datepickerFormat']
                ]
            ]); ?>

            <?= $form->field($model->photo, 'config')->widget(\common\widgets\upload\Upload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'AdminForm.PhotoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label(Yii::t('backend','Фото')); ?>

            <?= $form->field($model->profile,'language')->dropDownList([
                'en' => Yii::t('backend','En'),
                'ru' => Yii::t('backend','Ru'),
            ]); ?>

            <?= $form->field($model,'working_status')->dropDownList(\core\helpers\user\AgentHelper::roleList()); ?>

            <?=
            $form->field($model, 'coordinates')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
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
                        'type' => \kartik\select2\Select2::class,
                        'defaultValue'=>\core\entities\user\User::ROLE_AGENT,
                        'options' => [
                            'data' => \core\helpers\user\UserHelper::agencyRoleList()
                        ]
                    ],
                    [
                        'title' => Yii::t('backend','Ценообразование'),
                        'name'  => 'agent_price',
                    ],
                    [
                        'title' => '',
                        'name'  => 'agent_metrik',
                        'type' => \kartik\select2\Select2::class,
                        'defaultValue'=>2,
                        'options' => [
                            'data' => [
                                2 => Yii::t('backend','За километр'),
                                1 => Yii::t('backend','В час')
                            ]
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

