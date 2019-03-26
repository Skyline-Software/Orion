<?php
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;
/* @var $model \core\entities\user\User */
$this->title = 'Детальная карточка агента';
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
                            <?= Html::a('Заказы агента', ['/manage/agency/orders/index', 'OrdersSearch[agent_id]' => $model->id], ['class' => 'btn btn-success']) ?>
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
                            <?= Html::a('Заказы', ['/manage/agency/orders/index', 'OrdersSearch[agent_id]' => $model->id], ['class' => 'btn btn-success']) ?>
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
                    'price',
                    [
                        'label'=>'Рабочий статус',
                        'format'=>'raw',
                        'value' =>function($model){
                            return \core\helpers\user\AgentHelper::statusLabel($model->working_status);
                        }
                    ],
                    [
                        'label'=>'Статус',
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
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Агентства и роли</h3>
        </div>
        <div class="box-body">
            <table class="table-condensed table">
                <thead>
                <th>Агентство</th>
                <th>Роль</th>
                <th>Время назначения</th>
                </thead>
                <tbody>
                <?php foreach ($model->agencyAssn as $assn){ ?>
                    <tr>
                        <td><?= $assn->agency->name; ?></td>
                        <td><?= \core\helpers\user\UserHelper::roleName($assn->role); ?></td>
                        <td><?= date(Yii::$app->params['dateFormat']." H:i",(int)$assn->created_at); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


</div>
