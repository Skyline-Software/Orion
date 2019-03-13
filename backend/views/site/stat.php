<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 11.12.2018
 * Time: 15:42
 * @var $users \core\entities\user\User[]
 * @var $usingAndroid \core\entities\user\UserAuth[]
 * @var $usingIOS \core\entities\user\UserAuth[]
 * @var $usingWeb \core\entities\user\UserAuth[]
 * @var $cardTypes \core\entities\card\CardType[]
 * @var $partners \core\entities\partner\Partner[]
 * @var $salesByCategory \core\entities\sales\Sales[]
 * @var $certs \core\entities\buy\Buy[]
 * @var $activatedCerts \core\entities\cert\UserCert[]
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
                <td>Число регистраций</td>
                <td><?= count($users); ?></td>
            </tr>
            <tr>
                <td>Число активированных карт по типам</td>
                <td>
                    <table class="table">
                        <thead>
                        <th>Категория</th>
                        <th>Кол-во</th>
                        </thead>
                        <tbody>
                        <?php foreach ($cardTypes as $type){?>
                            <?php
                            $cards = $type
                                ->getCards()
                                ->andWhere(['not', ['activated' => null]])
                                ->andFilterWhere(['>=', 'activated', $from ? $from->getTimestamp() : null])
                                ->andFilterWhere(['<=', 'activated', $to ? $to->getTimestamp() : null])

                            ?>
                            <tr>
                                <td><?= $type->name; ?></td>
                                <td><?= $cards->count(); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Число новых партнеров</td>
                <td><?= count($partners); ?></td>
            </tr>
            <tr>
                <td>Число проданных сертификатов</td>
                <td><?= count($certs); ?></td>
            </tr>
            <tr>
                <td>Число использованных сертификатов</td>
                <td><?= count($activatedCerts); ?></td>
            </tr>
            <tr>
                <td>Число использований приложений Android</td>
                <td><?= count($usingAndroid); ?></td>
            </tr>
            <tr>
                <td>Число использований приложений iOS</td>
                <td><?= count($usingIOS); ?></td>
            </tr>
            <tr>
                <td>Число использований приложений Web</td>
                <td><?= count($usingWeb); ?></td>
            </tr>
            <tr>
                <td>Число получений скидок по категориям</td>
                <td>
                    <table class="table">
                        <thead>
                            <th>Категория</th>
                            <th>Кол-во</th>
                        </thead>
                        <tbody>
                        <?php foreach ($salesByCategory as $category=>$array){?>
                                <tr>
                                    <td><?= $category; ?></td>
                                    <td><?= count($array); ?></td>
                                </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

