<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История скидок';
?>
<div class="orders-index">
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label'=>'Номер карты',
                        'attribute' => 'card_number',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->card){
                                return Html::a($model->card->number,['manage/card/card/view','id'=>$model->card->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'label'=>'Заведение',
                        'attribute'=>'partner_id',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->partner){
                                return Html::a($model->partner->name,['manage/partner/default/view','id'=>$model->partner->id]);
                            }
                            return '';
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
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                                ]
                            ]
                        ),
                    ],
                    [
                        'label'=>'Клиент',
                        'attribute'=>'user_id',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->user){
                                return Html::a($model->user->name,['manage/users/customer/view','id'=>$model->user->id]);
                            }
                            return '';
                        },
                        'filter' => Select2::widget([
                            'model'=>$searchModel,
                            'attribute' => 'user_id',
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
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ]
                        ]
                        ),
                    ],
                    [
                        'label'=>'Дата операции',
                        'attribute'=>'created_at',
                        'filter' => \kartik\widgets\DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight'=>true,
                                'autoclose' => false,
                                'format'=>Yii::$app->params['datepickerFormat']
                            ],
                        ]),
                        'value' => function($model){
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    [
                        'label'=>'Размер скидки',
                        'attribute'=>'saved',
                    ],
                    [
                        'label'=>'Категория',
                        'attribute'=>'category_id',
                        'format' => 'raw',
                        'value' => function($model){
                                    if($model->partner){
                                        if($model->partner->category){
                                            if($model->partner->category->parent){
                                                return Html::a($model->partner->category->parent->name,['manage/partner/category/view','id'=>$model->partner->category->parent->id]).'/'. Html::a($model->partner->category->name,['manage/partner/category/view','id'=>$model->partner->category->id]);
                                            }
                                            Html::a($model->partner->category->name,['manage/partner/category/view','id'=>$model->partner->category->id]);
                                        }
                                        return 'Категория не указана';
                                    }
                                    return '';


                        },
                        'filter' => \yii\helpers\ArrayHelper::map(\core\entities\partner\PartnerCategory::find()->all(),'id','name')
                    ],
                    /*[
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
                                    'confirm' => 'Вы уверены что хотите удалить пользователя?',
                                    'method' => 'post',
                                ]]);
                            },
                        ]
                    ],*/
                ],
            ]); ?>
            </div>
        </div>
    </div>

</div>
