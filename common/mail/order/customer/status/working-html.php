<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */
/* @var $order \core\entities\order\Order */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Ваш заказ на печать номер <?= $order->id; ?> передан в обработку менеджером.</p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteUrlToView()), $order->absoluteUrlToView()) ?></p>
    <p>В ближайшее время мы свяжемся с Вами</p>
</div>
