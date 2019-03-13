<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.09.2018
 * Time: 00:39
 */
use yii\helpers\Html;
?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Заказ номер <?= $order->id; ?>  выдан Вами клиенту.</p>
    <p>Передайте менеджеру документы и оплату.</p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteCourierUrlToView()), $order->absoluteCourierUrlToView()) ?></p>
</div
