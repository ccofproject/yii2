<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => '后台管理系统',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        // Add
        'rbac' => [
            'class' => 'rbac\Module',
        ],
        'system' => [
            'class' => 'system\Module',
        ],
        'backup' => [
            'class' => 'backup\Module',
        ],
    ],
    'aliases' => [
        '@rbac' => '@backend/modules/rbac',
        '@system' => '@backend/modules/system',
        '@backup' => '@backend/modules/backup',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],

            // Add
            'loginUrl' => ['/rbac/user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true, // 是否开启URL美化
            'showScriptName' => true, // 是否显示脚本名称，即index.php
            'enableStrictParsing' => false, // 是否开启严格解析
            'suffix' => '.html', // URL 后缀
            // URL规则
            'rules' => [
//                'dashboard' => 'site/index',
//                'POST <controller:[\w-]+>s' => '<controller>/create',
//                '<controller:[\w-]+>s' => '<controller>/index',
//                'PUT <controller:[\w-]+>/<id:\d+>'    => '<controller>/update',
//                'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',
//                '<controller:[\w-]+>/<id:\d+>'        => '<controller>/view',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ],
        ],
    ],
    'as access' => [
        'class' => 'rbac\components\AccessControl',
        'allowActions' => [
            'rbac/user/request-password-reset',
            'rbac/user/reset-password',
            '*'
        ]
    ],
    'params' => $params,
];
