<?php
$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@core', dirname(dirname(__DIR__)) . '/core');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');

Yii::setAlias('@storageUrl', \yii\helpers\ArrayHelper::getValue($params,'storageUrl'));



