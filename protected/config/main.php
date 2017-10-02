<?php

return [
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'RTV Web-server 2.0',
    'preload'=>['log'],
    'import'=>[
        'application.models.*',
        'application.components.*',
        'application.modules.rbac.controllers.RbacController',
        'application.widgets.*',
        'application.widgets-internal.*',
        'application.widgets-internal.tv-schedule.*',
        'application.widgets-internal.point-channels.*',
        'application.widgets-internal.screen-selector.*',
        'application.widgets-internal.choose-widget-dialog.*',
        'application.widgets-internal.choose-playlist-dialog.*',
    ],
    'theme'=>'basic',
    'modules'=>[
        'gii'=>[
            'class'=>'system.gii.GiiModule',
            'password'=>'ass',
            'ipFilters'=>['127.0.0.1','::1'],
            'generatorPaths'=>[
                'bootstrap.gii',
            ],
        ],
    ],
    'components'=>[
        'user'=>[
            'class' => 'WebUser',
            'allowAutoLogin'=>true,
        ],
        'authManager' => [
            'class' => 'PhpAuthManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager'=>[
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'caseSensitive'=>true,
            'rules'=>[
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
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
        ],
        'pointInfo'=>[
            'class'=>'PointInfoRequester',
        ],
        'contentManager'=>[
            'class'=>'ContentManager',
        ],
        'playlistService' => [
            'class'=>'PlaylistService',
            'playlist' => function() {
                return Playlists::model();
            },
        ],
        'pointService' => [
            'class'=>'PointService',
            'user' => function() {
                return Yii::app()->user;
            },
            'tvSchedule' => function() {
                return TvSchedule::model();
            },
            'showcase' => function() {
                return Showcase::model();
            },
            'playlistToPoint' => function() {
                return PlaylistToPoint::model();
            },
        ],
        'spool' => [
            'class'=>'Spool',
            'spoolPointsPath' => 'spool'. DIRECTORY_SEPARATOR . 'points' . DIRECTORY_SEPARATOR,
            'spoolAudioPath' => 'spool'. DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR,
            'spoolVideoPath' => 'spool'. DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR,
            'spoolImagesPath' => 'spool'. DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR,
            'spoolOtherPath' => 'spool'. DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR,
            'playlistToPoint' => function() {
                return PlaylistToPoint::model();
            },
            'file' => function() {
                return File::model();
            }
        ],
        'heap' => [
            'class'=>'Heap',
            'file' => function() {
                return File::model();
            },
            'fileToFolder' => function() {
                return FileToFolder::model();
            },
            'folder' => function() {
                return Folder::model();
            },
        ],
        'assets' => [
            'class'=>'AssetManagerHelper',
            'assetManager' => function() {
                return Yii::app()->assetManager;
            },
            'clientScript' => function() {
                return Yii::app()->clientScript;
            },
            'basePath' => function() {
                return INDEX_PATH;
            }
        ],
    ],
    'params'=>[
        'adminEmail'=>'webmaster@example.com',
        'weatherApiKey'=>'1be0ed3547cbc0a415058b881eac9494',
        'interactionUrl' => 'http://127.0.0.1:1337'
    ],
];
