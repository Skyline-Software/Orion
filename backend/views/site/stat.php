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
$this->title = 'Статистика';



?>
<div class="row">
    <form action="" method="GET">
        <div class="col-md-4">
            <?= \kartik\widgets\DatePicker::widget([
                'name' => 'from',
                'language'=>'ru',
                'value' => ArrayHelper::getValue($_GET,'from'),
                'options' => ['placeholder' => 'Выберите дату'],
                'pluginOptions' => [
                    'format' => Yii::$app->params['datepickerFormat'],
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?= \kartik\widgets\DatePicker::widget([
                'name' => 'to',
                'value' => ArrayHelper::getValue($_GET,'to'),
                'options' => ['placeholder' => 'Выберите дату'],
                'pluginOptions' => [
                    'format' => Yii::$app->params['datepickerFormat'],
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <input type="submit" class="btn btn-success" value="Отфильтровать">
        </div>

    </form>
</div>
<div class="row">
    <div class="col-md-8">
        <table class="table table-striped table-condensed table-pointer">
            <thead>
            <th>Наименование</th>
            <th>Значение</th>
            </thead>
            <tbody>
            <tr>
                <td>Агентов</td>
                <td><?= count($agents); ?></td>
            </tr>
            <tr>
                <td>Клиентов</td>
                <td><?= count($clients); ?></td>
            </tr>
            <tr>
                <td>Заказов</td>
                <td><?= count($orders); ?></td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <?= \dosamigos\chartjs\ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'height' => 400,
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => $dates,
                        'datasets' => [
                            [
                                'label' => "Orders",
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
                <td>Общая стоимость</td>
                <td><?= $summ ?></td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <?= \dosamigos\chartjs\ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'height' => 400,
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => $dates,
                        'datasets' => [
                            [
                                'label' => "Orders",
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

