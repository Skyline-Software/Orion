<?php
/* @var $model \core\forms\manage\partner\PartnerForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use msvdev\widgets\mappicker\MapInput;
$this->title = 'Редактирование заведения';
?>
<style>
    .field-salesform-config .js-input-remove{
        display:none;
    }
</style>
<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin(); ?>
            <?= $form->field($model,'name')->textInput(); ?>
            <?= $form->field($model,'status')->dropDownList(
                [
                    \core\entities\partner\Partner::STATUS_ACTIVE => 'Активно',
                    \core\entities\partner\Partner::STATUS_HIDE => 'Не активно',
                ]
            ); ?>
            <?php
            echo \common\widgets\treeDrop\Widget::widget([
                'id' => 'organizationsList', //<-- id контейнера выпадающего списка (ВНИМАНИЕ! Обязателен, если на странице несколько DropdownTreeWidget)
                'form' => $form, // <-- ActiveForm (форма, для генерации скрытого input который будет отправлен в контроллер после submit формы)
                'model' => $model, // <-- Model  (модель, для генерации скрытого input в который и будут подставляться выбранные значения)
                'attribute' => 'category_id', //<-- Model attribute  (атрибут модели, для генерации скрытого input)
                'label' => \Yii::t('app', 'Категория'), //Заголовок выпадающего списка
                'multiSelect' => true,  //Если true, то из списка можно будет выбрать более одного значения
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

            <?= $form->field($model,'description')->textarea(); ?>

            <?= $form->field($model,'avg_invoice')->textInput(); ?>

            <?= $form->field($model,'website')->textInput(); ?>

            <?= $form->field($model,'instagram')->textInput(); ?>

            <?= $form->field($model,'can_buy_ur')->checkbox(); ?>

            <?= $form->field($model,'can_accept_cert')->checkbox(); ?>

            <?= $form->field($model->logo, 'config')->widget(\common\widgets\upload\Upload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'PartnerForm.LogoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Логотип'); ?>

            <?= $form->field($model->header_photo, 'config')->widget(\common\widgets\upload\Upload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'PartnerForm.HeaderPhotoForm'],
                    'maxFileSize' => 5000000,
                ]
            )->label('Фото в шапку'); ?>

            <?= $form->field($model->photos, 'config')->widget(\common\widgets\upload\Upload::className(),
                [
                    'url' => ['/file-storage/upload','type'=>'PartnerForm.PhotosForm'],
                    'maxFileSize' => 5000000,
                    'multiple' => true,
                    'maxNumberOfFiles' => 10

                ]
            )->label('Галерея'); ?>

            <?= $form->field($model->adresses, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                'min'=>1,
                'columnClass' => \common\widgets\mapPickerMulti\MultipleInputColumn::class,
                'columns' => [
                    [
                        'name'  => 'address',
                        'title' => 'Адрес',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'phone',
                        'title' => 'Номер телефона',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'coords',
                        'title' => 'Координаты',
                        'type' => \common\widgets\mapPickerMulti\MapInput::className(),
                        'options'=>[
                            'service' => 'yandex',
                        ],
                        'enableError' => true,
                    ],
                ]
            ])->label('Адреса'); ?>

            <?= $form->field($model->work_time, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => \unclead\multipleinput\MultipleInput::POS_FOOTER,
                'sortable' => false,
                #'data'=>$model->blocks[BlockTypes::ABOUT]->rows ,
                'min'=>1,
                'columns' => [
                    [
                        'name'  => 'day',
                        'title' => 'День',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'from',
                        'title' => 'С',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'to',
                        'title' => 'По',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'break',
                        'title' => 'Перерыв',
                        'enableError' => true,
                    ],
                ]
            ])->label('Рабочее время'); ?>

            <?= $form->field($model->sales, 'config')->widget(\unclead\multipleinput\MultipleInput::className(), [
                'addButtonPosition' => false,
                'sortable' => false,
                'removeButtonOptions' => [
                    'style'=>'display:none'
                ],
                'data'=>$model->sales->config,
                'min'=>false,
                'max' => count($model->sales->config),
                'columns' => [
                    [
                        'name'  => 'card_type_id',
                        'title' => 'Тип карты',
                        'type' => 'dropDownList',
                        'items'=> \yii\helpers\ArrayHelper::map(
                            \core\entities\card\CardType::find()->all(),'id','name'
                        ),
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'discount',
                        'title' => 'Скидка',
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'hot',
                        'title' => 'Акция?',
                        'type' => 'dropDownList',
                        'items'=> [
                            0 => 'Нет.',
                            1 => 'Да.'
                        ],
                        'enableError' => true,
                    ],
                    [
                        'name'  => 'description',
                        'title' => 'Описание скидки',
                        'type' => 'textArea',
                        'value' => '123'
                    ],
                    [
                        'name'  => 'status',
                        'title' => 'Статус',
                        'type' => 'dropDownList',
                        'items'=> [
                            0 => 'Выкл.',
                            1 => 'Вкл.'
                        ],
                        'enableError' => true,
                    ],
                ]
            ])->label('Скидки'); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class'=>'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>