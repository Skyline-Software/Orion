<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 04.09.2018
 * Time: 15:44
 * @var $this \yii\web\View
 * @var $invoice \core\entities\order\Invoice
 */
?>
<div class="row">
    <div class="col-md-12">
        <ul id="article-attachments">
            <?php foreach ($invoice->files as $attachment): ?>
                <li>
                    <?php echo \yii\helpers\Html::a(
                        $attachment['name'],
                        ['/order/ajax/download', 'path' => $attachment['path'],'name'=>$attachment['name']])
                    ?>
                    (<?php echo Yii::$app->formatter->asSize($attachment['size']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

