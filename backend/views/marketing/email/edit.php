<?php
/* @var $model \core\forms\manage\card\CardTypeForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Редактирование шаблона';
?>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'title')->textInput(); ?>
            <?= $form->field($model,'type')->dropDownList(
                [
                    0 => 'E-mail',
                    1 => 'Push'
                ]
            ); ?>
            <?= $form->field($model,'subject')->textInput(); ?>
            <?= $form->field($model,'message')->textarea(); ?>
            <?= $form->field($model,'card_types')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\card\CardType::find()->all(),'id','name'),
                ['prompt'=>'Выберите тип карты','multiple'=>true]
            ); ?>

            <?= $form->field($model,'age')->textInput(['type'=>'number']); ?>
            <?= $form->field($model,'sex')->dropDownList(
                [
                    0 => 'Мужской',
                    1 => 'Женский',
                ],
                [
                    'multiple'=>true
                ]
            ); ?>
            <?php
            echo \common\widgets\treeDrop\Widget::widget([
                'id' => 'organizationsList', //<-- id контейнера выпадающего списка (ВНИМАНИЕ! Обязателен, если на странице несколько DropdownTreeWidget)
                'form' => $form, // <-- ActiveForm (форма, для генерации скрытого input который будет отправлен в контроллер после submit формы)
                'model' => $model, // <-- Model  (модель, для генерации скрытого input в который и будут подставляться выбранные значения)
                'attribute' => 'partner_categories', //<-- Model attribute  (атрибут модели, для генерации скрытого input)
                'label' => \Yii::t('app', 'Категории'), //Заголовок выпадающего списка
                'multiSelect' => true, //Если true, то из списка можно будет выбрать более одного значения
                'searchPanel' => [
                    'visible' => false, //Если true, то будет отображена панели с полем для поиска по дереву
                    'label' => \Yii::t('app', 'Выберите категорию'), //Заголовок для панели
                    'placeholder' => '',  //Текст-подсказка внутри поля для поиска
                    'searchCaseSensivity' => false //Если True, то поиск по дереву будет регистрозависимый
                ],
                'rootNode' => [
                    'visible' => false, //Отображать корневой узел или нет
                    'label' => \Yii::t('app', 'Root Node') //Название корневого узла
                ],
                'expand' => false, //Распахнуть выпадающий список сразу после отображения
                //Список узлов дерева с под-узлами
                'items' => \core\helpers\PartnerCategoryHelper::getCategoryTree()
            ]);
            ?>
            <?= $form->field($model,'partners')->dropDownList(
                \yii\helpers\ArrayHelper::map(\core\entities\partner\Partner::find()->all(),'id','name'),
                ['prompt'=>'Выберите тип карты','multiple'=>true]
            ); ?>

            <?= $form->field($model,'is_for_already_has_sale')->checkbox(); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>