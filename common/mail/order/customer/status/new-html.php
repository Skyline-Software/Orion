<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */
/* @var $order \core\entities\order\Order */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Вы создали новый заказ на печать</p>
    <p>Номер заказа <?= $order->id; ?></p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteUrlToView()), $order->absoluteUrlToView()) ?></p>
</div>
