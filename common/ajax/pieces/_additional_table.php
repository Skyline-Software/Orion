<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 04.09.2018
 * Time: 16:33
 * @var string $header
 * @var array $array
 * @var Invoice $invoice
 */
use yii\helpers\ArrayHelper;
use core\entities\order\Invoice;
?>
<?php if(!empty($array)){ ?>
<h4><?= $header; ?></h4>
<table class="table table-condensed">
    <thead>
    <th>Позиция</th>
    <th>Стоимость</th>
    <th>Кол-во</th>
    <th>Сумма</th>
    </thead>
    <tbody>

        <?php foreach ($array as $item){ ?>
            <tr>
                <td><?= $label = ArrayHelper::getValue($item,'label'); ?></td>
                <td><?= $price = ArrayHelper::getValue($item,'price'); ?></td>
                <td><?= $count = ArrayHelper::getValue($invoice->config,'paperCount'); ?></td>
                <td><?= (float)$price * (int)$count; ?> руб.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php }else{ ?>
    <h4><?= $header; ?></h4>
    <p>Не заказано</p>
<?php } ?>
<hr>
