<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
/* @var $model \core\entities\user\User */
$this->title = 'Детальная карточка клиента';
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead">Управление:</p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a('Карты клиента', ['/manage/card/card/index','CardSearch[client_id]'=>$model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Полученные скидки', ['/site/sale-history','SaleSearch[user_id]'=>$model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить пользователя?',
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
                                    'confirm' => 'Вы уверены что хотите удалить пользователя?',
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
                    [
                        'label' => 'Зарегистрирован',
                        'value' => function($model){
                            if(is_null($model->created_at)){
                                return 'Не зарегистрирован';
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    'email:email',
                    'name',
                    'phone',
                    [
                        'label' => 'День рождения',
                        'value' => function($model){
                            if(is_null($model->created_at)){
                                return 'Не указано';
                            }
                            return date(Yii::$app->params['dateFormat'],strtotime($model->birthday));
                        }
                    ],
                    [
                            'label'=>'Пол',
                            'value' =>function($model){
                             if($model->sex = 1){
                                 return 'Мужской';
                             }else{
                                 return 'Женский';
                             }
                            }
                    ],[
                            'label'=>'Статус',
                            'format'=>'raw',
                            'value' =>function($model){
                                return \core\helpers\user\UserHelper::statusLabel($model->status);
                            }
                    ],
                ],
            ]) ?>
        </div>
    </div>


</div>
