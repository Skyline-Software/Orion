<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */
/* @var $order \core\entities\order\Order */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Ваш заказ на печать номер <?= $order->id; ?> согласован и ожидает оплаты.</p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteUrlToView()), $order->absoluteUrlToView()) ?></p>
    <p>После оплаты заказ будет передан в производство</p>
</div>
