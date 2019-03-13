<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini"><img style="width: 110px" src="/logo_inverted.png"></span><span class="logo-lg"><img style="width: 110px" src="/logo_inverted.png"></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

    </nav>
</header>
