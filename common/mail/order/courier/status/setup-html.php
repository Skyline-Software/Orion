<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.09.2018
 * Time: 00:39
 * @var $order \core\entities\order\Order
 */
use yii\helpers\Html;
?>
<div class="password-reset">
    <p>Здравствуйте!</p>
    <p>Заказ номер <?= $order->id; ?> назачен Вам на доставку.</p>
    <p>Адрес доставки <?= $order->delivery_address; ?></p>
    <p>Просмотреть заказ можно по ссылке <?= Html::a(Html::encode($order->absoluteCourierUrlToView()), $order->absoluteCourierUrlToView()) ?></p>
</div
