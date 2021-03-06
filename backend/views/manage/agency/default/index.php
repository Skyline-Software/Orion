<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\agency\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->title = Yii::t('backend','Агентства');
?>
<div class="orders-index">
    <?php if(Yii::$app->user->identity->isAdmin()){ ?>
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead"><?= Yii::t('backend','Управление:'); ?></p>
            </div>
            <div class="col-md-8 ">
                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a(Yii::t('backend','Добавить агентство'), ['create'], ['class' => 'btn btn-primary']) ?>
                </div>

                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a(Yii::t('backend','Добавить'), ['create'], ['class' => 'btn btn-primary']) ?>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>Yii::t('backend','Название'),
                        'attribute'=>'name',
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column){
                            return Html::a($model->name,['view','id'=>$model->id]);
                        },


                    ],
                    [
                        'label'=>Yii::t('backend','Дата регистрации'),
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
                        'label'=>Yii::t('backend','Статус'),
                        'attribute'=>'status',
                        'content' => function($model){
                            return $model->status ? Yii::t('backend','Вкл.') : Yii::t('backend','Выкл');
                        },
                        'filter' => [
                                    1 => Yii::t('backend','Вкл.'),
                                    0 => Yii::t('backend','Выкл.'),
                            ]

                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{activate}{view}{edit}{delete}',
                        'buttons' => [
                            'activate' => function ($url, $model, $key) {
                                if(!Yii::$app->user->getIdentity()->isAdmin()){
                                    return '';
                                }
                                if($model->status === \core\entities\agency\Agency::STATUS_INACTIVE){
                                    return Html::a('<i class="fa fa-play"></i>',['/manage/agency/change/activate','id'=>$model->id],['class'=>'btn']);
                                }
                                return Html::a('<i class="fa fa-stop"></i>',['/manage/agency/change/deactivate','id'=>$model->id],['class'=>'btn']);
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="'.Yii::t('backend','Детальная информация').'"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                if(!Yii::$app->user->getIdentity()->isAdmin()){
                                    return '';
                                }
                                return Html::a('<i class="fa fa-edit" title="'.Yii::t('backend','Редактирование').'"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                if(!Yii::$app->user->getIdentity()->isAdmin()){
                                    return '';
                                }
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить агенство?'),
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
