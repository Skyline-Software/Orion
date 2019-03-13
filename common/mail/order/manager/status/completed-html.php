<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */
/* @var $order \core\entities\order\Order */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Заказ на услугу печать выполнен</p>
    <p>Номер заказа <?= $order->id; ?></p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteManagerUrlToView()), $order->absoluteManagerUrlToView()) ?></p>
</div>
