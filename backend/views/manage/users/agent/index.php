<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Агенты';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">
                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Добавить агента', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a('Добавить агента', ['create'], ['class' => 'btn btn-primary']) ?>
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
                    'email',
                    'name',
                    [
                        'label' => 'Телефон',
                        'attribute' => 'phone',
                        'content' => function($model){
                            return $model->phone;
                        },
                        'filter' => \yii\widgets\MaskedInput::widget(['name'=>'AdminSearch[phone]','mask'=>'+9(999) 999 99 99'])
                    ],
                    [
                        'label' => 'Рабочий статус',
                        'attribute' => 'working_status',
                        'content' => function($model){
                            return \core\helpers\user\AgentHelper::statusLabel($model->working_status);
                        },
                        'filter' => \core\helpers\user\AgentHelper::roleList()
                    ],
                    [
                        'label' => 'Статус',
                        'attribute' => 'status',
                        'content' => function($model){
                            return \core\helpers\user\UserHelper::statusLabel($model->status);
                        },
                        'filter' => \core\helpers\user\UserHelper::statusList()
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{activate}{view}{edit}{delete}',
                        'buttons' => [
                            'activate' => function ($url, $model, $key) {
                                if($model->status === \core\entities\user\User::STATUS_BANNED_BY_ADMIN){
                                    return Html::a('<i class="fa fa-play"></i>',['/manage/users/change/activate','id'=>$model->id],['class'=>'btn']);
                                }
                                if($model->status !== \core\entities\user\User::STATUS_ACTIVE){
                                    return Html::a('<i class="fa fa-play"></i>',['/manage/users/change/activate','id'=>$model->id],['class'=>'btn']);
                                }
                                return Html::a('<i class="fa fa-stop"></i>',['/manage/users/change/deactivate','id'=>$model->id],['class'=>'btn']);
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="Детальная информация"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-edit" title="Редактирование"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => 'Вы уверены что хотите удалить пользователя?',
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
