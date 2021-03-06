<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 15:42
 * @var $agents \core\entities\user\User[]
 * @var $clients \core\entities\user\User[]
 * @var $orders \core\entities\agency\Order[]
 * @var $summ int
 * @var $from string
 * @var $to string
 */

use yii\helpers\ArrayHelper;
$this->title = Yii::t('backend','Статистика');

?>
<div class="row">
    <form action="" method="GET">
        <div class="col-md-2">
            <?=
            \kartik\widgets\Select2::widget([
                    'data'=> array_merge([0=>Yii::t('backend','Все агентства')], \core\helpers\AgencyHelper::getAllowedAgencies()),
                    'name' => 'agency_id',
                    'value' => ArrayHelper::getValue($_GET,'agency_id'),
            ]);
            ?>
        </div>
        <div class="col-md-2">
            <?= \kartik\widgets\DatePicker::widget([
                'name' => 'from',
                'language'=>'ru',
                'value' => $from->format('d.m.y'),
                'options' => ['placeholder' => Yii::t('backend','Выберите дату')],
                'pluginOptions' => [
                    'format' => Yii::$app->params['datepickerFormat'],
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="col-md-2">
            <?= \kartik\widgets\DatePicker::widget([
                'name' => 'to',
                'value' => $to->format('d.m.y'),
                'options' => ['placeholder' => Yii::t('backend','Выберите дату')],
                'pluginOptions' => [
                    'format' => Yii::$app->params['datepickerFormat'],
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-success" value="<?= Yii::t('backend','Отфильтровать'); ?>">
        </div>

    </form>
</div>
<div class="row">
    <div class="col-md-8">
        <table class="table table-striped table-condensed table-pointer">
            <thead>
            <th><?= Yii::t('backend','Наименование'); ?></th>
            <th><?= Yii::t('backend','Значение'); ?></th>
            </thead>
            <tbody>
            <tr>
                <td><?= Yii::t('backend','Агентов'); ?></td>
                <td><?= count($agents); ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('backend','Клиентов'); ?></td>
                <td><?= count($clients); ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('backend','Заказов'); ?></td>
                <td><?= \yii\helpers\Html::a(count($orders),['/manage/agency/orders','OrdersSearch[status]'=>\core\entities\agency\Order::STATUS_PAYED,'OrdersSearch[agency_id]'=>ArrayHelper::getValue($_GET,'agency_id')]); ?></td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <?= \dosamigos\chartjs\ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => $dates,
                        'datasets' => [
                            [
                                'label' => Yii::t('backend',"Orders"),
                                'backgroundColor' => "rgba(179,181,198,0.2)",
                                'borderColor' => "rgba(179,181,198,1)",
                                'pointBackgroundColor' => "rgba(179,181,198,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                'data' => $ordersByDates
                            ],
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
        <table class="table table-striped table-condensed table-pointer">
            <tbody>
            <tr>
                <td><?= Yii::t('backend','Общая стоимость'); ?></td>
                <td><?= \yii\helpers\Html::a($summ,['/manage/agency/orders','OrdersSearch[status]'=>\core\entities\agency\Order::STATUS_PAYED,'OrdersSearch[agency_id]'=>ArrayHelper::getValue($_GET,'agency_id')]); ?></td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <?= \dosamigos\chartjs\ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => $dates,
                        'datasets' => [
                            [
                                'label' => Yii::t('backend',"Summary"),
                                'backgroundColor' => "rgba(179,181,198,0.2)",
                                'borderColor' => "rgba(179,181,198,1)",
                                'pointBackgroundColor' => "rgba(179,181,198,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                'data' => $summByDates
                            ],
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>


    </div>
</div>

