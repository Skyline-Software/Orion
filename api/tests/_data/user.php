<?php
return [
    'user1' =>
    [
        'id' => 1,
        'email' => 'test@test.com',
        'password_hash' => '$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC',
        'reset_token' => md5('$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC'),
        'created_at' => '1544785297',
        'status' => 0,
        'type' => 2,
        'name' => \Yii::$app->security->generateRandomString(4),
        'phone' => \Yii::$app->security->generateRandomString(11),
        'photo' => \Yii::$app->security->generateRandomString(11),
        'sex' => '1',
        'birthday' => '12.04.91',
        'language' => 'ru',
    ],
    'user2' =>
    [
            'id' => 2,
            'email' => 'test2@test.com',
            'password_hash' => '$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC',
            'reset_token' => md5('$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC'),
            'created_at' => '1544785297',
            'status' => 999555444,
            'type' => 2,
        'name' => \Yii::$app->security->generateRandomString(4),
        'phone' => \Yii::$app->security->generateRandomString(11),
        'sex' => '1',
        'birthday' => '12.04.91',
        'language' => 'ru',
    ],
    'user3' =>
    [
        'id' => 3,
        'email' => 'test3@test.com',
        'password_hash' => '$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC',
        'reset_token' => md5('$2y$13$oganQVq7MplFg3EU9AEsMOH5s21Qp3km0bMrEqVsnbG1S7imQ8FoC'),
        'created_at' => '1544785297',
        'status' => -1,
        'type' => 2,
        'name' => \Yii::$app->security->generateRandomString(4),
        'phone' => \Yii::$app->security->generateRandomString(11),
        'sex' => '1',
        'birthday' => '12.04.91',
        'language' => 'ru',
    ],
];
