<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 03.08.2018
 * Time: 20:05
 * @var array $params
 */

return [
    'class'=> 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'manage/users/admin/index',
        'viewed' => 'site/viewed-email',
        'points' => 'points/points/index',
        '<_a:about>' => 'site/<_a>',
        'contact' => 'contact/index',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'reset/<_a:[\w-]+>' => 'auth/reset/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ]
];