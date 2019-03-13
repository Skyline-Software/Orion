<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */
/* @var $order \core\entities\order\Order */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Ваш заказ на печать номер <?= $order->id; ?> готов и передан курьеру. Заказ будет доставлен <?= $order->delivery_time; ?></p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteUrlToView()), $order->absoluteUrlToView()) ?></p>
</div>
