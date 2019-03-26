<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\agency\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = 'Заказы';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">

                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Добавить заказ', ['create'], ['class' => 'btn btn-primary']) ?>
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
            <?php Pjax::begin(['id' => 'orders','timeout' => 50000]) ?>
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>'Агенство',
                        'attribute'=>'agency_id',
                        'value' => function($model){
                            if($model->agency){
                                return $model->agency->name;
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                            'name' =>'OrdersSearch[agency_id]',
                            'data'=>\core\helpers\AgencyHelper::getAllowedAgencies(),
                            'options' => ['placeholder' => 'Выберите компанию']
                        ]),

                    ],
                    [
                        'label'=>'Агент',
                        'attribute'=>'agent_id',
                        'value' => function($model){
                            if($model->agent){
                                return $model->agent->name;
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                            'name' =>'OrdersSearch[agency_id]',
                            'data'=> \core\helpers\AgencyHelper::getAllowedAgents($searchModel->agency_id),
                            'options' => ['placeholder' => 'Выберите агента']
                        ]),
                    ],
                    [
                        'label'=>'Клиент',
                        'attribute'=>'user_id',
                        'value' => function($model){
                            if($model->user){
                                return $model->user->name;
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                            'name' =>'OrdersSearch[agency_id]',
                            'data'=> \core\helpers\AgencyHelper::getAllowedUsers($searchModel->agency_id),
                            'options' => ['placeholder' => 'Выберите клиента']
                        ]),
                    ],
                    'price',
                    [
                        'label'=>'Рейтинг',
                        'attribute'=>'rating',
                        'filter' => range(1,5)
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
                            return $model::STATUS_LIST[$model->status];
                        },
                        'filter' => \core\entities\agency\Order::STATUS_LIST

                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{edit}{delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="Детальная информация"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                if(!Yii::$app->user->getIdentity()->isAdmin()){
                                    return '';
                                }
                                return Html::a('<i class="fa fa-edit" title="Редактирование"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                if(!Yii::$app->user->getIdentity()->isAdmin()){
                                    return '';
                                }
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => 'Вы уверены что хотите удалить заявку?',
                                    'method' => 'post',
                                ]]);
                            },
                        ]
                    ],
                ],
            ]); ?>
            <?php Pjax::end() ?>
            </div>
        </div>
    </div>

</div>
