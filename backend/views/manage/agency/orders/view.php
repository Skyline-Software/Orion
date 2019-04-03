<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $model \core\entities\agency\Agency */
$this->title = Yii::t('backend','Детальная карточка заказа');
$coords1 = explode(',',$model->start_coordinates);
$coords2 = explode(',',$model->end_coordinates);
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
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить заказ?'),
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
                                    'confirm' => Yii::t('backend','Вы уверены что хотите удалить заказ?'),
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
            <h3 class="box-title"><?= Yii::t('backend','О заказе') ?></h3>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'format'=>'raw',
                        'label' => Yii::t('backend','Агентство'),
                        'value' => function($model){
                            if($model->agency){
                                return Html::a($model->agency->name,['/manage/agency/default/view','id'=>$model->agency->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'format'=>'raw',
                        'label' => Yii::t('backend','Агент'),
                        'value' => function($model){
                            if($model->agent){
                                return Html::a($model->agent->name,['/manage/users/agent/view','id'=>$model->agent->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'format'=>'raw',
                        'label' => Yii::t('backend','Клиент'),
                        'value' => function($model){
                            if($model->user){
                                return Html::a($model->user->name,['/manage/users/customer/view','id'=>$model->user->id]);
                            }
                            return '';
                        }
                    ],
                    [
                        'label' => Yii::t('backend','Дата создания'),
                        'value' => function($model){
                            return date(Yii::$app->params['dateFormat']." H:i",(int)$model->created_at);
                        }
                    ],
                    'price',
                    'start_time',
                    'end_time',
                    [
                        'label' => Yii::t('backend','Комментарий заказчика'),
                        'value' => function($model){
                            return nl2br($model->comment);
                        }
                    ],
                    'rating',
                    [
                        'label' => Yii::t('backend','Статус'),
                        'value' => function($model){
                            return Yii::t('backend',\core\entities\agency\Order::STATUS_LIST[$model->status]);
                        }
                    ],
                ],
            ]) ?>
            <h3><?= Yii::t('backend','Map'); ?></h3>
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>
</div>
<?php $this->registerJs("
function initMap() {
        var myLatlng = {lat: $coords1[0], lng: $coords1[1]};
        var markers = [];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: myLatlng,
            mapTypeId: 'roadmap'
        });
        
        var preSetupedFrom = new google.maps.Marker({
                map: map,
                title: 'From',
                position: {lat: $coords1[0], lng: $coords1[1]},
        });
         var preSetapedTo = new google.maps.Marker({
                map: map,
                title: 'To',
                position: {lat: $coords2[0], lng: $coords2[1]},
        });
        bounds.extend(preSetupedFrom.position);
         bounds.extend(preSetapedTo.position);

         map.fitBounds(bounds);

        var listener = google.maps.event.addListener(map, \"idle\", function () {
            map.setZoom(3);
            google.maps.event.removeListener(listener);
        });
    }
",\yii\web\View::POS_BEGIN);
?>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0&libraries=places&&callback=initMap">
</script>