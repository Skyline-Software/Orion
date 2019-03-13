<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление скидками карты типа '.$cardType->name;
?>
<div class="orders-index">
    <!--<div class="box">
        <div class="box-body">
            <div class="col-md-4">
                <p class="lead">Управление:</p>
            </div>
            <div class="col-md-8 ">
                <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                    <?/*= Html::a('Импорт', ['/manage/discount/csv/import'], ['class' => 'btn btn-info']) */?>
                    <?/*= Html::a('Экспорт', ['/manage/discount/csv/export'], ['class' => 'btn btn-success']) */?>
                </div>
                <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                    <?/*= Html::a('Импорт', ['/manage/discount/csv/import'], ['class' => 'btn btn-info']) */?>
                    <?/*= Html::a('Экспорт', ['/manage/discount/csv/export'], ['class' => 'btn btn-success']) */?>
                </div>
            </div>
        </div>
    </div>-->
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>'Заведения',
                        'attribute'=>'partner_id',
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column){
                            if($model->partner){
                                return Html::a($model->partner->name,['/manage/partner/default/view','id'=>$model->partner->id]);
                            }

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
                            ])


                    ],
                    [
                            'class' => \yii2mod\editable\EditableColumn::class,
                            'label' => 'Скидка/%',
                            'attribute' => 'discount',
                            'url' => ['change-discount'],
                    ],
                    [
                        'class' => \yii2mod\editable\EditableColumn::class,
                        'label' => 'Акция?',
                        'attribute' => 'hot',
                        'url' => ['change-discount'],
                        'type' => 'select',
                        'editableOptions' => function ($model) {
                            return [
                                'source' => [
                                    1 => 'Да.',
                                    0 => 'Нет.',
                                ],
                                'value' => $model->hot,
                            ];
                        },
                        'content' => function($model){
                            return $model->hot ? 'Да.' : 'Нет.';
                        },
                        'filter' => [
                            1 => 'Да.',
                            0 => 'Нет.',
                        ]
                    ],[
                        'class' => \yii2mod\editable\EditableColumn::class,
                        'label' => 'Описание скидки/%',
                        'attribute' => 'description',
                        'url' => ['change-discount'],
                        'type' => 'textarea',
                        'editableOptions' => function ($model) {
                            return [
                                'value' => $model->description ? $model->description : 'Без описания',
                            ];
                        },

                    ],
                    [
                        'class' => \yii2mod\editable\EditableColumn::class,
                        'label'=>'Статус',
                        'attribute'=>'status',
                        'url' => ['change-discount'],
                        'type' => 'select',
                        'editableOptions' => function ($model) {
                            return [
                                'source' => [
                                    1 => 'Вкл.',
                                    0 => 'Выкл.',
                                ],
                                'value' => $model->status,
                            ];
                        },
                        'content' => function($model){
                            return $model->status ? 'Вкл.' : 'Выкл';
                        },
                        'filter' => [
                            1 => 'Вкл.',
                            0 => 'Выкл.',
                        ]

                    ],
                ],
            ]); ?>
            </div>
        </div>
    </div>

</div>
