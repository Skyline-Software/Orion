<?php

use core\helpers\AgencyHelper;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;
/* @var $model \core\entities\user\User */
$this->title = Yii::t('backend','Детальная карточка агента');
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
                            <?= Html::a(Yii::t('backend','Заказы агента'), ['/manage/agency/orders/index', 'OrdersSearch[agent_id]' => $model->id], ['class' => 'btn btn-success']) ?>
                            <?= Html::a(Yii::t('backend','Удалить'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить пользователя?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a(Yii::t('backend','К списку'), ['index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a(Yii::t('backend','Редактировать'), ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a(Yii::t('backend','Заказы'), ['/manage/agency/orders/index', 'OrdersSearch[agent_id]' => $model->id], ['class' => 'btn btn-success']) ?>
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
                    'price',
                    [
                        'label'=>Yii::t('backend','Рабочий статус'),
                        'format'=>'raw',
                        'value' =>function($model){
                            return \core\helpers\user\AgentHelper::statusLabel($model->working_status);
                        }
                    ],
                    [
                        'label'=>Yii::t('backend','Статус'),
                        'format'=>'raw',
                        'value' =>function($model){
                            return \core\helpers\user\UserHelper::statusLabel($model->status);
                        }
                    ],
                ],
            ]) ?>
            <?php
            $coords = explode(',',$model->coordinates);
            echo \pigolab\locationpicker\LocationPickerWidget::widget([
                'key' => 'AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0',	// require , Put your google map api key
                'options' => [
                    'style' => 'width: 100%; height: 400px', // map canvas width and height
                ] ,
                'clientOptions' => [
                    'location' => [
                        'latitude'  => \yii\helpers\ArrayHelper::getValue($coords,0) ,
                        'longitude' => \yii\helpers\ArrayHelper::getValue($coords,1) ,
                    ],
                    'radius'    => 300,
                    'addressFormat' => 'street_number',
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('backend','Агентства и роли') ?></h3>
        </div>
        <div class="box-body">
            <table class="table-condensed table">
                <thead>
                <th><?= Yii::t('backend','Агентство') ?></th>
                <th><?= Yii::t('backend','Роль') ?></th>
                <th><?= Yii::t('backend','Время назначения') ?></th>
                <th><?= Yii::t('backend','Ценообразование') ?></th>
                </thead>
                <tbody>
                <?php if(!Yii::$app->user->identity->isAdmin()){
                        $assns = $model->getAgencyAssn()->where(['in','agency_id',AgencyHelper::getAllowedAgenciesIds()])->all();
                    }else{
                    $assns = $model->agencyAssn;
                    } ?>
                <?php foreach ($assns as $assn){ ?>
                    <tr>
                        <td><?= $assn->agency->name; ?></td>
                        <td><?= \core\helpers\user\UserHelper::roleName($assn->role); ?></td>
                        <td><?= date(Yii::$app->params['dateFormat']." H:i",(int)$assn->created_at); ?></td>
                        <td><?php
                            if($assn->agency->agent_price){
                                echo $assn->agency->agent_price.'/'.Yii::t('backend',\core\entities\agency\Agency::AGENCY_METRIK_LIST[$assn->agency->agent_metrik]);
                            }else{
                                echo $assn->agent_price.'/'.Yii::t('backend',\core\entities\agency\Agency::AGENCY_METRIK_LIST[$assn->agent_metrik]);
                            }
                            ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


</div>
