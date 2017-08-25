<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'RTV Web-server 2.0',
    'preload'=>array('log'),
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.rbac.controllers.RbacController',
        'application.widgets.*',
        'application.widgets-internal.tv-schedule.*',
        'application.widgets-internal.point-channels.*',
        'application.widgets-internal.screen-selector.*',
        'application.widgets-internal.choose-widget-dialog.*',
        'application.widgets-internal.choose-playlist-dialog.*',
    ),
    'theme'=>'basic',
    'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'ass',
            'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
        ),
    ),
    'components'=>array(
        'user'=>array(
            'class' => 'WebUser',
            'allowAutoLogin'=>true,
        ),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'caseSensitive'=>true,
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'admin/user/<id>' => 'admin/user',
            ),
        ),
        'db'=> include(dirname(__FILE__).'/db.php'),
        'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
<<<<<<< HEAD
=======
        'sentry'=>array(
            'class'=>'ext.yii-sentry.components.RSentryClient',
            'dsn'=>'https://cfcf1647999740bda98522d36c9cf793:8943953122494a0fa72cba1e161f7b1f@sentry.io/207801',
        ),
>>>>>>> 59920864a350bf9d020476949f393e35adb13b38
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
<<<<<<< HEAD
                    'class'=>'CFileLogRoute',
=======
                    'class'=>'ext.yii-sentry.components.RSentryLogRoute',
>>>>>>> 59920864a350bf9d020476949f393e35adb13b38
                    'levels'=>'error, warning',
                ),
            ),
        ),
        'clientScript'=>array(
            'packages'=>array(
                'jquery'=>array(
                    'baseUrl'=>'/',
                    'js'=>array('js/lib/jquery-1.11.0.js'),
                )
            ),
        ),
        'pointInfo'=>array(
            'class'=>'PointInfoRequester',
        ),
        'contentManager'=>array(
            'class'=>'ContentManager',
        ),
        'playlistService' => array(
            'class'=>'PlaylistService',
            'playlist' => function() {
                return Playlists::model();
            },
        ),
        'pointService' => array(
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
        ),
        'spool' => array(
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
        ),
        'heap' => array(
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
        ),
    ),
    'params'=>array(
        'adminEmail'=>'webmaster@example.com',
        'weatherApiKey'=>'1be0ed3547cbc0a415058b881eac9494',
<<<<<<< HEAD
=======
        'interactionUrl' => 'http://127.0.0.1:1337'
>>>>>>> 59920864a350bf9d020476949f393e35adb13b38
    ),
);
