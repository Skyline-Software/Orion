<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
/* @var $model \core\entities\partner\Partner */
$this->title = 'Детальная карточка заведения';
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
                                    'confirm' => 'Вы уверены что хотите удалить карточку заведения?',
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
                                    'confirm' => 'Вы уверены что хотите удалить карточку заведения?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Данные заведения</h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    [
                            'label' => 'Категория',
                            'value' => function($model){
                                if($model->category){
                                    $catNames = array_map(function ($category){
                                        /* @var \core\entities\partner\PartnerCategory $category */
                                        return $category->name;
                                    },$model->category);
                                    return implode(', ',$catNames);
                                }
                                return 'Категория не указана';
                            },
                    ],
                    'description',
                    'avg_invoice',
                    'token',
                    [
                            'format' => 'raw',
                            'label' => 'Ссылка на сайт',
                            'value' =>function($model)
                            {
                                /* @var \core\entities\partner\Partner $model*/
                                return Html::a($model->website,$model->website,['target'=>'_blank']);
                            }
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Ссылка на инстаграм',
                        'value' =>function($model)
                        {
                            /* @var \core\entities\partner\Partner $model*/
                            return Html::a($model->website,$model->website,['target'=>'_blank']);
                        }
                    ],
                    'can_buy_ur:boolean',
                    'can_accept_cert:boolean',
                ],
            ]) ?>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Время работы</h3>
        </div>
        <div class="box-body">
            <table class="table-condensed table">
                <thead>
                <th>День недели</th>
                <th>Время</th>
                <th>Перерыв</th>
                </thead>
                <tbody>
                <?php foreach ($model->work_time['config'] as $time){ ?>
                    <tr>
                        <td><?= \yii\helpers\ArrayHelper::getValue($time,'day'); ?></td>
                        <td><?= \yii\helpers\ArrayHelper::getValue($time,'from'); ?> - <?= \yii\helpers\ArrayHelper::getValue($time,'to'); ?></td>
                        <td><?= \yii\helpers\ArrayHelper::getValue($time,'break'); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Адреса</h3>
        </div>
        <div class="box-body">
            <table class="table-condensed table">
                <thead>
                <th>Адрес</th>
                <th>Телефон</th>
                </thead>
                <tbody>
                <?php foreach ($model->adresses as $adress){?>
                    <tr>
                        <td><?= $adress->address; ?></td>
                        <td><?= $adress->phone; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Скидки заведения</h3>
        </div>
        <div class="box-body">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => $model->getSales()
                ]),
                'layout' => '{items}',
                'columns' => [
                        [
                            'label' => 'Тип карты',
                            'value' => function($model){
                                if($model->type){
                                    return $model->type->name;
                                }
                            }
                        ],
                        [
                            'label' => 'Скидка',
                            'value' => function($model){
                                return $model->discount.'%';
                            }
                        ]
                ]
            ]) ?>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">История транзакций заведения</h3>
        </div>
        <div class="box-body">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => $model->getHistory()
                ]),
                'columns' => [
                    [
                        'label' => 'Карта',
                        'format' => 'raw',
                        'value' => function($model){
                            /* @var $model \core\entities\sales\Sales */
                            if($model->card){
                                return Html::a($model->card->number,['manage/card/card/view','id'=>$model->card->id]);
                            }
                            return 'Сертификат';

                        }
                    ],
                    [
                        'label' => 'Сумма/руб.',
                        'value' => function($model){
                            /* @var $model \core\entities\sales\Sales */
                            if(is_null($model->amount) && $model->saved > 0){
                                return $model->saved;
                            }
                            return $model->amount;
                        }
                    ],
                    [
                        'label' => 'Клиент',
                        'format' => 'raw',
                        'value' => function($model){
                            /* @var $model \core\entities\sales\Sales */
                            if($model->user){
                                return Html::a($model->user->name,['manage/users/customer/view','id'=>$model->user->id]);
                            }
                            return '';
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

                ]
            ]) ?>
        </div>
    </div>


</div>
