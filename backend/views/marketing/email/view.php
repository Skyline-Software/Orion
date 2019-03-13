<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use core\entities\marketing\RecipientList;
/* @var $model \core\entities\marketing\EmailConfig */
$this->title = 'Детальная карточка шаблона';
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead">Управление:</p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a(
                                    'Разослать',
                                    ['#'],
                                    [
                                        'class' => 'btn btn-success',
                                        'onclick'=>
                                            "
                                                 $.ajax({
                                                    type     :'GET',
                                                    cache    : false,
                                                    url  : '/marketing/email/send?id=".$model->id."',
                                                    success  : function(response) {
                                                        $.pjax.reload({container: '#emails'});
                                                    }
                                                });
                                                return false;
                                            "
                                    ]
                            ) ?>
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить шаблон?',
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
                                    'confirm' => 'Вы уверены что хотите удалить шаблон?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'subject',
                    'message',
                    [
                            'label' =>'Всего получателей',
                            'value' => RecipientList::find()->where(['config_id'=>$model->id])->count()
                    ],
                    [
                        'label' =>'Всего отправлено',
                        'value' => RecipientList::find()->where(['config_id'=>$model->id,'status'=>RecipientList::SEND])->count()
                    ],
                    [
                        'label' =>'Всего прочитано',
                        'value' => RecipientList::find()->where(['config_id'=>$model->id,'status'=>RecipientList::WATCHED])->count()
                    ]

                ],
            ]) ?>
        </div>
    </div>

    <div class="box">

        <div class="box-body">
            <?php Pjax::begin(['id' => 'emails','timeout' => 5000]) ?>
            <?php
                $dataProvider = new \yii\data\ActiveDataProvider([
                        'query' => \core\entities\marketing\RecipientList::find()->joinWith('user')->where(['config_id'=>$model->id])
                ]);
                echo \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                                'user.email',
                                [
                                    'label' => 'Статус',
                                    'value' => function($model){
                                        return \core\entities\marketing\RecipientList::LIST[$model->status];
                                    }
                                ]
                        ]
                ]);
            ?>
            <?php Pjax::end() ?>
        </div>
    </div>


</div>
