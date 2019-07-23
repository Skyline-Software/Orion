<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=zpj83vpaccjer3ah.chr7pe7iynqr.eu-west-1.rds.amazonaws.com;dbname=sugoyznuhswf4z5j;port=3306',
            'username' => 'pgw2ni8kx3ihi26q',
            'password' => 'znd7087imsarb744',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
    ],
];
