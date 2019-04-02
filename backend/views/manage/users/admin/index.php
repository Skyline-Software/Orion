<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend','Администраторы');
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead"><?= Yii::t('backend','Управление:'); ?></p>
            </div>
            <div class="col-md-8 ">
                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a(Yii::t('backend','Добавить администратора'), ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a(Yii::t('backend','Добавить администратора'), ['create'], ['class' => 'btn btn-primary']) ?>
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
                        'label' => Yii::t('backend','Телефон'),
                        'attribute' => 'phone',
                        'content' => function($model){
                            return $model->phone;
                        },
                        'filter' => \yii\widgets\MaskedInput::widget(['name'=>'AdminSearch[phone]','mask'=>'+9(999) 999 99 99'])
                    ],
                    [
                        'label' => Yii::t('backend','Статус'),
                        'attribute' => 'status',
                        'content' => function($model){
                            return \core\helpers\user\UserHelper::statusLabel($model->status);
                        },
                        'filter' => \core\helpers\user\UserHelper::statusList()
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{edit}{delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="'.Yii::t('backend','Детальная информация').'"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-edit" title="'.Yii::t('backend','Редактирование').'"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash" title="'.Yii::t('backend','Удаление').'"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить пользователя?'),
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
