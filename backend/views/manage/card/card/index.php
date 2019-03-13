<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = 'Карты';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">

                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Импорт', ['/manage/card/csv/import'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Экспортировать все', ['/manage/card/csv/export'], ['class' => 'btn btn-info']) ?>
                    <?= Html::a('Добавить карту', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a('Импорт', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Экспорт', ['create'], ['class' => 'btn btn-info']) ?>
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
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>'Тип',
                        'attribute'=>'type_id',
                        'value' => function($model, $key, $index, $column){
                            if($model->type){
                                return $model->type->name;

                            }
                            return '';
                        },
                        'filter' => \yii\helpers\ArrayHelper::map(
                                    \core\entities\card\CardType::find()->all(),'id','name')

                    ],
                    [
                        'label'=>'Номер',
                        'attribute'=>'number',
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column){
                            return Html::a($model->number,['view','id'=>$model->id]);
                        },


                    ],
                    [
                        'label'=>'Клиент',
                        'attribute'=>'user_id',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->client){
                                return Html::a($model->client->name,['manage/users/customer/view','id'=>$model->client->id]);
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                                'model'=>$searchModel,
                                'attribute' => 'client_id',
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
                        'label'=>'Дата активации',
                        'attribute'=>'activated',
                        'filter' => \kartik\widgets\DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight'=>true,
                                'autoclose' => true,
                                'format'=> Yii::$app->params['datepickerFormat']
                            ],
                        ]),
                        'value' => function($model){
                            if(is_null($model->activated)){
                                return '';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->activated);
                        }
                    ],
                    [
                        'label'=>'Статус',
                        'attribute'=>'status',
                        'content' => function($model){
                            return $model->status ? 'Вкл.' : 'Выкл';
                        },
                        'filter' => [
                                    1 => 'Вкл.',
                                    0 => 'Выкл.',
                            ]

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
                                    'confirm' => 'Вы уверены что хотите удалить карту?',
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
