<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Здравствуйте <?= $user->fullName ?>,

Пройдите по ссылке для сброса пароля

<?= $resetLink ?>
