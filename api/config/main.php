<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'name' => 'Card',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json'
            ]
        ]
    ],
    'components' => [
        'fileStorage'=>[
            'class' => '\trntv\filekit\Storage',
            'baseUrl' => '@storageUrl/source',
            'filesystem' => [
                'class' => 'common\filesystem\LocalFlysystemBuilder',
                'path' => '@storage/web/source'
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'asdasdasdasd',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'response' =>[
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ]
            ],
            'format' => \yii\web\Response::FORMAT_JSON
        ],
        'user' => [
            'identityClass' => 'core\entities\user\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => require __DIR__ . '/urlManager.php',
    ],
    /*'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],*/
    'params' => $params,
];
