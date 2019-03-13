<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заведения';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">
                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?= Html::a('Добавить заведение', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?= Html::a('Добавить заведение', ['create'], ['class' => 'btn btn-primary']) ?>
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
                    'name',
                    [
                        'label'=>'Категория',
                        'attribute'=>'category_id',
                        'value' => function($model, $key, $index, $column){
                                if($model->category){
                                    $catNames = array_map(function ($category){
                                        /* @var \core\entities\partner\PartnerCategory $category */
                                        return $category->name;
                                    },$model->category);
                                    return implode(', ',$catNames);
                                }
                                return 'Категория не указана';
                        },
                        'headerOptions' => ['style' => 'width:60%'],
                        'filter' => \yii\helpers\ArrayHelper::map(\core\entities\partner\PartnerCategory::find()->all(),'id','name')

                    ],
                    [
                        'label' => 'Статус',
                        'attribute' => 'status',
                        'content' => function($model){
                            return \core\helpers\PartnerHelper::statusLabel($model->status);
                        },
                        'filter' => \core\helpers\PartnerHelper::statusList()
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{activate}{view}{edit}{delete}',
                        'buttons' => [
                            'activate' => function ($url, $model, $key) {
                                if($model->status === \core\entities\partner\Partner::STATUS_HIDE){
                                    return Html::a('<i class="fa fa-play"></i>',['/manage/partner/change/activate','id'=>$model->id],['class'=>'btn']);
                                }
                                return Html::a('<i class="fa fa-stop"></i>',['/manage/partner/change/deactivate','id'=>$model->id],['class'=>'btn']);
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye" title="Детальная информация"></i>',$url,['class'=>'btn']);
                            },
                            'edit' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-edit" title="Редактирование"></i>',$url,['class'=>'btn']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash" title="Удаление"></i>',$url,['class'=>'btn','data' => [
                                    'confirm' => 'Вы уверены что хотите удалить карточку заведения?',
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
