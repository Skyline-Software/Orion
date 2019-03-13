<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'token' => $user->email_confirm_token]);
?>
Hello <?= $user->fullName ?>,

Follow the link below to confirm your email:

<?= $confirmLink ?>
