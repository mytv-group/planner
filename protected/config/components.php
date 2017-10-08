<?php

return [
    'user'=>[
        'class' => 'WebUser',
        'allowAutoLogin'=>true,
    ],
    'authManager' => [
        'class' => 'AuthManager',
        'defaultRoles' => ['guest'],
    ],
    'urlManager'=>[
        'class' => 'UrlManager',
        'urlFormat'=>'path',
        'showScriptName'=>false,
        'caseSensitive'=>true,
        'rules'=>[
            '<controller:[\w-]+>'=>'<controller>/index',
            '<controller:[\w-]+>/<id:\d+>'=>'<controller>/view',
            '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>'=>'<controller>/<action>',
            '<controller:[\w-]+>/<action:[\w-]+>'=>'<controller>/<action>'
        ],
    ],
    'db' => include('db.php'),
    'clientScript'=>[
        'packages'=>include('packages.php'),
    ],
    'errorHandler'=>[
        'errorAction'=>'site/error',
    ],
    'sentry'=>[
        'enabled'=>in_array(
            $_SERVER['SERVER_NAME'], include('sentry-to-log-domains.php')
        ),
        'class'=>'ext.yii-sentry.components.RSentryClient',
        'dsn'=>'https://cfcf1647999740bda98522d36c9cf793:8943953122494a0fa72cba1e161f7b1f@sentry.io/207801',
        'options'=>[
            'tags'=>[
                'server'=>$_SERVER['SERVER_NAME']
            ]
        ]
    ],
    'log'=>[
        'class'=>'CLogRouter',
        'routes'=>[
            [
                'class'=>'ext.yii-sentry.components.RSentryLogRoute',
                'levels'=>'error, warning',
            ],
        ],
    ]
];
