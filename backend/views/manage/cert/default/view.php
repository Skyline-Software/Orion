<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
/* @var $model \core\entities\user\User */
$this->title = 'Детальная карточка сертификата';
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
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'number',
                    'value',
                    [
                        'label' => 'Владелец',
                        'format'=>'raw',
                        'value' => function($model){
                            if($model->user){
                                return Html::a($model->user->name,['/manage/users/customer/view','id'=>$model->user->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => 'Использован в заведении',
                        'format'=>'raw',
                        'value' => function($model){
                            if($model->partner){
                                return Html::a($model->partner->name,['/manage/partner/default/view','id'=>$model->partner->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => 'Дата использования',
                        'value' => function($model){
                            if(is_null($model->used)){
                                return '';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",strtotime($model->used));
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>


</div>
