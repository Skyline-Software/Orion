<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'name' => 'Orion',
    'language' => 'en-EN',
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'manage/users/admin/index',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'emailSender' => [
            'class' => 'backend\modules\emailSender\Module'
        ]
    ],
    'components' => [
        'fileStorage'=>[
            'class' => '\core\services\storage\Storage',
            'baseUrl' => '@storageUrl/source',
            'filesystem' => [
                'class' => 'common\filesystem\LocalFlysystemBuilder',
                'path' => '@storage/web/source'
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => $params['cookieValidationKey']
        ],
        'user' => [
            'identityClass' => 'core\entities\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain']
            ],
            'loginUrl' => ['auth/auth/login']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => '_session-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'backendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager' => function(){
            return Yii::$app->get('backendUrlManager');
        },

    ],
    'params' => $params,
    'on beforeAction' => function($event) {
        if(!Yii::$app->user->isGuest){
            Yii::$app->language = Yii::$app->user->identity->getLang();
        }
    } ,
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow' => true,
                'actions' => ['login','viewed'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],

        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['auth/auth/login']);
        },
    ]
];
