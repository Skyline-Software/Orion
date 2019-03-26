<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $model \core\entities\agency\Agency */
$this->title = 'Детальная карточка заказа';
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead">Управление:</p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-primary']) ?>
                            <?php if(Yii::$app->user->getIdentity()->isAdmin()){ ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить заказ?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?php } ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-info']) ?>
                            <?php if(Yii::$app->user->getIdentity()->isAdmin()){ ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить заказ?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">О заказе</h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Агенство',
                        'value' => function($model){
                            if($model->agency){
                                return $model->agency->name;
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => 'Агент',
                        'value' => function($model){
                            if($model->agent){
                                return $model->agent->name;
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => 'Клиент',
                        'value' => function($model){
                            if($model->user){
                                return $model->user->name;
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => 'Дата создания',
                        'value' => function($model){
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    'price',
                    'start_time',
                    'end_time',
                    [
                        'label' => 'Комментарий заказчика',
                        'value' => function($model){
                            return nl2br($model->comment);
                        }
                    ],
                    'rating',
                    [
                        'label' => 'Статус',
                        'value' => function($model){
                            return \core\entities\agency\Order::STATUS_LIST[$model->status];
                        }
                    ],
                ],
            ]) ?>
            <h3>Начальное положение</h3>
            <?php
            $coords = explode(',',$model->start_coordinates);
            echo \pigolab\locationpicker\LocationPickerWidget::widget([
                'key' => 'AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0',	// require , Put your google map api key
                'options' => [
                    'style' => 'width: 100%; height: 400px', // map canvas width and height
                ] ,
                'clientOptions' => [
                    'location' => [
                        'latitude'  => \yii\helpers\ArrayHelper::getValue($coords,0) ,
                        'longitude' => \yii\helpers\ArrayHelper::getValue($coords,1) ,
                    ],
                    'radius'    => 300,
                    'addressFormat' => 'street_number',
                ]
            ]);
            ?>
            <h3>Конечное положение</h3>
            <?php
            $coords = explode(',',$model->end_coordinates);
            echo \pigolab\locationpicker\LocationPickerWidget::widget([
                'key' => 'AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0',	// require , Put your google map api key
                'options' => [
                    'style' => 'width: 100%; height: 400px', // map canvas width and height
                ] ,
                'clientOptions' => [
                    'location' => [
                        'latitude'  => \yii\helpers\ArrayHelper::getValue($coords,0) ,
                        'longitude' => \yii\helpers\ArrayHelper::getValue($coords,1) ,
                    ],
                    'radius'    => 300,
                    'addressFormat' => 'street_number',
                ]
            ]);
            ?>

        </div>
    </div>
</div>
