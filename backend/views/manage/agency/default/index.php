<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\agency\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = 'Агентства';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">

                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Добавить агентство', ['create'], ['class' => 'btn btn-primary']) ?>
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
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>'Название',
                        'attribute'=>'name',
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column){
                            return Html::a($model->name,['view','id'=>$model->id]);
                        },


                    ],
                    [
                        'label'=>'Дата регистрации',
                        'attribute'=>'created_at',
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
                            if(is_null($model->created_at)){
                                return '';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
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
                        'template' => '{activate}{view}{edit}{delete}',
                        'buttons' => [
                            'activate' => function ($url, $model, $key) {
                                if($model->status === \core\entities\agency\Agency::STATUS_INACTIVE){
                                    return Html::a('<i class="fa fa-play"></i>',['/manage/agency/change/activate','id'=>$model->id],['class'=>'btn']);
                                }
                                return Html::a('<i class="fa fa-stop"></i>',['/manage/agency/change/deactivate','id'=>$model->id],['class'=>'btn']);
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="Детальная информация"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-edit" title="Редактирование"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => 'Вы уверены что хотите удалить компанию?',
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
