<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
/* @var $model \core\entities\stock\Stock */
$this->title = 'Детальная карточка скидки';
?>
<div class="user-view">
    <div class="box ">
                <div class="box-body">
                    <div class="col-md-4">
                        <p class="lead">Управление:</p>
                    </div>
                    <div class="col-md-8 ">
                        <div class="btn-group btn-group-lg btn-group btn-group-justified hidden-xs" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить скидку?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                        <div class="btn-group btn-group-sm hidden-lg btn-group btn-group-justified hidden-md hidden-sm" role="group">
                            <?= Html::a('К списку', ['index'], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены что хотите удалить скидку?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'discount',
                    'from',
                    'to',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Заведения</h3>
        </div>
        <div class="box-body">
            <?= \yii\grid\GridView::widget([
                'layout' => '{items} {pager}',
                'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $model->partner
                ]),
                'columns' => [
                        'partner.name'
                ]
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Категории заведений</h3>
        </div>
        <div class="box-body">
            <?= \yii\grid\GridView::widget([
                'layout' => '{items} {pager}',
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => $model->category
                ]),
                'columns' => [
                    'category.name'
                ]
            ]) ?>
        </div>
    </div>


</div>
