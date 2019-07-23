<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap'=> [
        'common\bootstrap\SetUp'
    ],
    'components' => [
        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@storage/web/source',
            'cachePath' => '@runtime/glide',
            'signKey' => false // "false" if you do not want to use HTTP signatures
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=zpj83vpaccjer3ah.chr7pe7iynqr.eu-west-1.rds.amazonaws.com;dbname=sugoyznuhswf4z5j;port=3306',
            'username' => 'pgw2ni8kx3ihi26q',
            'password' => 'znd7087imsarb744',
            'charset' => 'utf8',
        ],
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AAAAEPFFJ_o:APA91bEoU3Vz1z8-bH3wJ9hFrjp724Az4RCJq4CvNQPwJ1BTO1xLN6DMa9DAG48nKdHwXzvZnWIkwhzQXUDXElTrsxgwQOiG_7nrb-B9eoqg4ZmaymgV_obeR_uspwqmNn75Iw0ujiS9',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            #'viewPath' => '@common/mail',
            'useFileTransport' => false,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
];
