<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $model \core\entities\user\User */
$this->title = 'Детальная карточка карты';
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
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить карту?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить карту?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">О карте</h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'number',
                    [
                        'label' => 'Дата активации',
                        'value' => function($model){
                            if(is_null($model->activated)){
                                return 'Не активирована';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->activated);
                        }
                    ],
                    [
                        'format'=>'raw',
                        'label' => 'Данные о доставке',
                        'value' => function($model){
                            if(!empty($model->delivery)){
                                return implode('<br>',$model->delivery);
                            }
                            return '';
                        }
                    ],
                    'validity',
                    'status:boolean',
                    [
                            'label'=>'Владелец',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->client){
                                    return Html::a($model->client->name,['/manage/users/customer/view','id'=>$model->client->id]);
                                }
                            }

                    ]
                ],
            ]) ?>

        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Тип</h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model->type,
                'attributes' => [
                    'name',
                    'validity',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Зафиксированные скидки</h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => $model->getSales()->with('partner')
                ]),
                'columns' => [
                    [
                        'label'=>'Заведение',
                        'attribute'=>'partner_id',
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column){
                            if($model->partner){
                                return Html::a($model->partner->name,['/manage/partner/default/view','id'=>$model->partner->id]);
                            }

                        },
                    ],
                    [
                        'label' => 'Скидка/руб',
                        'value' => function($model){
                            return $model->saved;
                        }
                    ],
                    [
                        'label' => 'Время',
                        'value' => function($model){
                            if(is_null($model->created_at)){
                                return 'Не приобретен';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],

                ],
            ]); ?>
        </div>
    </div>


</div>
