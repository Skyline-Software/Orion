<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\user\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->fullName) ?>,</p>

    <p>Пройдите по ссылке для сброса пароля:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
