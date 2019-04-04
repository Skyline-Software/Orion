<?php
/* @var $model \core\forms\manage\agency\AgencyForm */
/* @var $this \yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use core\entities\user\User;
$this->title = Yii::t('backend','Создание нового заказа');
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'agency_id')->widget(Select2::class,[
                    'data' => \core\helpers\AgencyHelper::getAllowedAgencies(),
                    'options' => ['placeholder' => Yii::t('backend','Выберите агенство'),'id'=>'agency-id']
            ]);
             ?>
            <?= $form->field($model,'agent_id')
                ->widget(\kartik\depdrop\DepDrop::class,[
                    'options'=>['id'=>'agent-id'],
                    'type' => 2,
                    'pluginOptions'=>[
                        'depends'=>['agency-id'],
                        'placeholder'=>Yii::t('backend','Выберите агента...'),
                        'url'=>Url::to(['/manage/agency/orders/agents'])
                    ]
                ]); ?>
            <?= $form->field($model,'user_id')
                ->widget(\kartik\depdrop\DepDrop::class,[
                    'options'=>['id'=>'user-id'],
                    'type' => 2,
                    'pluginOptions'=>[
                        'depends'=>['agency-id'],
                        'placeholder'=>Yii::t('backend','Выберите клиента...'),
                        'url'=>Url::to(['/manage/agency/orders/customers'])
                    ]
                ]); ?>
            <div class="row">
                <div class="col-md-4">
                    <input id="from" class="controls" type="text" placeholder="From">
                    <input id="to" class="controls" type="text" placeholder="To">
                </div>
            </div>
            <div id="map" style="height: 500px;"></div>
            <?= $form->field($model,'lat1')->hiddenInput()->label(false); ?>
            <?= $form->field($model,'lon1')->hiddenInput()->label(false); ?>
            <?= $form->field($model,'lat2')->hiddenInput()->label(false); ?>
            <?= $form->field($model,'lon2')->hiddenInput()->label(false); ?>

            <?= $form->field($model,'start_time')->widget(\kartik\widgets\DateTimePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => Yii::$app->params['datepickerFormatWithTime']
                ]
            ]); ?>
            <?= $form->field($model,'end_time')->widget(\kartik\widgets\DateTimePicker::class,[
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => Yii::$app->params['datepickerFormatWithTime']
                ]
            ]); ?>
            <?= $form->field($model,'price')->textInput(); ?>
            <?= $form->field($model,'comment')->textarea(); ?>
            <?= $form->field($model,'rating')->dropDownList(range(0,5)); ?>
            <?= $form->field($model,'status')->dropDownList(
                [
                    \core\entities\agency\Order::STATUS_NOT_PAYED => Yii::t('backend','Не оплачен'),
                    \core\entities\agency\Order::STATUS_PAYED => Yii::t('backend','Оплачен'),
                ]
            ); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend','Сохранить'),['class'=>'btn btn-primary']); ?>
                <?= Html::a(Yii::t('backend','Cancel'), ['index'], ['class'=>'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php $this->registerJs("
function initMap() {
        var myLatlng = {lat: -25.363, lng: 131.044};
        var markers = [];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: myLatlng,
            mapTypeId: 'roadmap'
        });

        var from_addr = document.getElementById('from');
        var searchBox_from = new google.maps.places.SearchBox(from_addr);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(from_addr);

        var to_addr = document.getElementById('to');
        var searchBox_to = new google.maps.places.SearchBox(to_addr);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(to_addr);

        map.addListener('bounds_changed', function() {
            searchBox_from.setBounds(map.getBounds());
        });

        map.addListener('bounds_changed', function() {
            searchBox_to.setBounds(map.getBounds());
        });

        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
        });

        searchBox_from.addListener('places_changed', function() {
            if(markers.length >= 3){
                console.log(\"out of\");
            }else{
                var places = searchBox_from.getPlaces();

                if (places.length == 0) {
                    return;
                }



                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log(\"Returned place contains no geometry\");
                        return;
                    }

                    // Create a marker for each place.
                    var from_addr_markerk = new google.maps.Marker({
                        map: map,
                        title: 'From',
                        position: place.geometry.location,
                        draggable:true,
                    });


                    document.getElementById('orderform-lat1').value = place.geometry.location.lat();
                    document.getElementById('orderform-lon1').value = place.geometry.location.lng();

                    google.maps.event.addListener(from_addr_markerk, 'dragend', function(location){
                        document.getElementById('orderform-lat1').value = location.latLng.lat();
                        document.getElementById('orderform-lon1').value = location.latLng.lng();
                    });

                    markers.push(from_addr_markerk);


                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
                console.log(markers);
            }
        });

        searchBox_to.addListener('places_changed', function() {
            if(markers.length >= 3){
                console.log(\"out of\");
            }else{
                var places = searchBox_to.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log(\"Returned place contains no geometry\");
                        return;
                    }

                    var to_addr_markerk = new google.maps.Marker({
                        map: map,
                        title: 'To',
                        position: place.geometry.location,
                        draggable:true,

                    })


                    document.getElementById('orderform-lat2').value = place.geometry.location.lat();
                    document.getElementById('orderform-lon2').value = place.geometry.location.lng();

                    google.maps.event.addListener(to_addr_markerk, 'dragend', function(location){
                        document.getElementById('orderform-lat2').value = location.latLng.lat();
                        document.getElementById('orderform-lon2').value = location.latLng.lng();
                    });

                    markers.push(to_addr_markerk);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                console.log(markers);
                map.fitBounds(bounds);
            }

        });

        function placeMarker(location) {
            console.log(location.lat(),location.lng());
            markers.push(location);
            if(markers.length >= 3){
                console.log('out of');
            }else{
                if(markers.length >= 2){
                    var to = new google.maps.Marker({
                        position: location,
                        map: map,
                        draggable:true,
                        label: 'To'
                    });
                    document.getElementById('orderform-lat2').value = location.lat();
                    document.getElementById('orderform-lon2').value = location.lng();

                    google.maps.event.addListener(to, 'dragend', function(location){
                        document.getElementById('orderform-lat2').value = location.latLng.lat();
                        document.getElementById('orderform-lon2').value = location.latLng.lng();
                    });
                }else{
                    var from = new google.maps.Marker({
                        position: location,
                        map: map,
                        draggable:true,
                        label: 'From'
                    });
                    document.getElementById('orderform-lat1').value = location.lat();
                    document.getElementById('orderform-lon1').value = location.lng();

                    google.maps.event.addListener(from, 'dragend', function(location){
                        document.getElementById('orderform-lat1').value = location.latLng.lat();
                        document.getElementById('orderform-lon1').value = location.latLng.lng();
                    });

                }

            }

        }
    }
",\yii\web\View::POS_BEGIN);
?>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATAHGMoZ0B9U2akKcrFESRwETYlWC_4s0&libraries=places&&callback=initMap">
</script>