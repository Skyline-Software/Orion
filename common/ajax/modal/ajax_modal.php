<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 05.09.2018
 * Time: 19:51
 */

use yii\bootstrap\Modal;
#Script
$this->registerJs("
$(function(){
    $('.modelButton').click(function(e){
        e.preventDefault();
        $('#model').modal('show')
        .find('#modelContent')
        .load($(this).attr('value'));   
    });
});
",\yii\web\View::POS_END);

Modal::begin([
    'header' => '<h4></h4>',
    'id'     => 'model',
    'size'   => 'model-sm',
]);

echo "<div id='modelContent'></div>";

Modal::end();

?>