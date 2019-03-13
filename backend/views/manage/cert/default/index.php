<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use kartik\widgets\Select2;
use yii\web\JsExpression;

$this->title = 'Купленные сертификаты';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">

                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Добавить сертификат', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel'=>$searchModel,
                'columns' => [
                    'number',
                    [
                        'label' => 'Номинал',
                        'attribute' => 'value',
                        'filter' => (new \core\entities\cert\FaceValue())->getMultidimensional()
                    ],
                    [
                        'label'=>'Заведение',
                        'attribute'=>'partner_id',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->partner){
                                return Html::a($model->partner->name,['manage/partner/default/view','id'=>$model->partner->id]);
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                                'model'=>$searchModel,
                                'attribute' => 'partner_id',
                                #'data' => \yii\helpers\ArrayHelper::map(\core\entities\user\User::find()->where(['status'=>0,'type'=>0])->all(),'id','name'),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'hideSearch' => false,
                                'initValueText' => 'Выберите заведение', // set the initial display text
                                'options' => [
                                    'placeholder' => 'Выберите заведение',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => 'ru',
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::toRoute(['site/partner-list']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]
                        ),
                    ],
                    [
                        'label'=>'Владелец сертификата',
                        'attribute'=>'user_id',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->user){
                                return Html::a($model->user->name,['manage/users/customer/view','id'=>$model->user->id]);
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                                'model'=>$searchModel,
                                'attribute' => 'user_id',
                                #'data' => \yii\helpers\ArrayHelper::map(\core\entities\user\User::find()->where(['status'=>0,'type'=>0])->all(),'id','name'),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'hideSearch' => false,
                                'initValueText' => 'Выберите клиента', // set the initial display text
                                'options' => [
                                    'placeholder' => 'Выберите клиента',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => 'ru',
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::toRoute(['site/user-list']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ]
                            ]
                        ),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{edit}{delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="Детальная информация"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-edit" title="Редактирование"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => 'Вы уверены что хотите удалить сертификат?',
                                    'method' => 'post',
                                ]]);
                            },
                        ]
                    ],
                ],
            ]); ?>
            </div>
        </div>
    </div>

</div>
