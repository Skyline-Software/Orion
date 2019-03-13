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
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        'cards' => 'site/card',
        'viewed' => 'site/viewed',
        'network/attach' => 'network/attach/attach',

        'user/registration' => 'user/auth/registration',
        'user/upload' => 'user/auth/upload',
        'user/login' => 'user/auth/login',
        'user/logout' => 'user/auth/logout',
        'user/profile' => 'user/profile/view',
        'user/profile/edit' => 'user/profile/edit',
        'user/reset/request' => 'user/reset/request',
        'user/reset/confirm' => 'user/reset/confirm',
        'user/favorites/add' => 'user/favorites/add',
        'user/favorites/remove' => 'user/favorites/remove',
        'user/push-token' => 'user/auth/push-token',

        'user/buy/cert' => 'user/buy/cert',
        'user/buy/card' => 'user/buy/card',

        'partner/sale/register' => 'partner/sale/register',
        'partner/sale/use-cert' => 'partner/sale/use-cert',
        'partner/list' => 'partner/default/index',
        'partner/view' => 'partner/default/view',
        'partner/categories/list' => 'partner/categories/list',


        'card/list' => 'card/default/index',
        'cert/value' => 'cert/default/values',
        'cert/list' => 'cert/default/index',
        'card/activate' => 'card/default/activate',
        'card/deactivate' => 'card/default/delete',
        'card/discounts' => 'card/default/discounts',
        'card/types' => 'card/type/index',



    ],
];