<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 04.09.2018
 * Time: 15:44
 * @var $this \yii\web\View
 * @var $invoice \core\entities\order\Invoice
 */
use core\helpers\order\InvoiceHelper;
?>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('_additional_table',[
            'header'=>'Канцелярия',
            'invoice' => $invoice,
            'array' => InvoiceHelper::office(InvoiceHelper::additionals($invoice))
        ])?>
        <?php echo $this->render('_additional_table',[
            'header'=>'Пост обработка',
            'invoice' => $invoice,
            'array' => InvoiceHelper::postProduction(InvoiceHelper::additionals($invoice))
        ])?>
    </div>
</div>

