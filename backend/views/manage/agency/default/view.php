<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $model \core\entities\agency\Agency */
$this->title = Yii::t('backend','Детальная карточка агентства');
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead"><?= Yii::t('backend','Управление:') ?></p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a(Yii::t('backend','К списку'), ['index'], ['class' => 'btn btn-primary']) ?>
                            <?php if(Yii::$app->user->getIdentity()->isAdmin()){ ?>
                            <?= Html::a(Yii::t('backend','Редактировать'), ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Удалить'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить агентство?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?php } ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a(Yii::t('backend','К списку'), ['index'], ['class' => 'btn btn-info']) ?>
                            <?php if(Yii::$app->user->getIdentity()->isAdmin()){ ?>
                            <?= Html::a(Yii::t('backend','Редактировать'), ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Удалить'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить агентство?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('backend','О агентстве'); ?></h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'web_site',
                    [
                        'label' => Yii::t('backend','Дата регистрации'),
                        'value' => function($model){
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    [
                        'label' => Yii::t('backend','Цена агента'),
                        'value' => function($model){
                            if($model->agent_price){
                                return $model->agent_price.'/'.Yii::t('backend',\core\entities\agency\Agency::AGENCY_METRIK_LIST[$model->agent_metrik]);
                            }
                            return Yii::t('backend','Агент может назначать свою цену');
                        }
                    ],
                    [
                        'label' => Yii::t('backend','Статус'),
                        'value' => function($model){
                            if($model->status){
                                return Yii::t('backend','Вкл.');
                            }
                            return Yii::t('backend','Выкл.');
                        }
                    ],
                ],
            ]) ?>

        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('backend','Администраторы агентства') ?></h3>
        </div>
        <div class="box-body">
            <table class="table-condensed table">
                <thead>
                <th><?= Yii::t('backend','Пользователь'); ?></th>
                <th><?= Yii::t('backend','Время назначения') ?></th>
                </thead>
                <tbody>
                <?php foreach ($model->getUserAssn()->andFilterWhere(['role'=>\core\entities\user\User::ROLE_AGENCY_ADMIN])->all() as $assn){ ?>
                    <tr>
                        <td><?= $assn->user->name; ?></td>
                        <td><?= date(Yii::$app->params['dateFormat']." H:i",(int)$assn->created_at); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
