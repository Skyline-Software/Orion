<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
/* @var $model \core\entities\user\User */
$this->title = Yii::t('backend','Детальная карточка администратора');
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead"><?= Yii::t('backend','Управление:'); ?></p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a(Yii::t('backend','К списку'), ['index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a(Yii::t('backend','Редактировать'), ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Удалить'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить пользователя?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a(Yii::t('backend','К списку'), ['index'], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Редактировать'), ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Удалить'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить пользователя?'),
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
                        'label' => Yii::t('backend','Зарегистрирован'),
                        'value' => function($model){
                            if(is_null($model->created_at)){
                                return Yii::t('backend','Не зарегистрирован');
                            }
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    'email:email',
                    'name',
                    'phone',
                ],
            ]) ?>
        </div>
    </div>


</div>
