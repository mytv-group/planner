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
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
        'pointInfo'=>array(
            'class'=>'PointInfoRequester',
        ),
        'contentManager'=>array(
            'class'=>'ContentManager',
        ),
        'tvSchedule'=>array(
            'class'=>'TvScheduleManager',
        ),
    ),
    'params'=>array(
        'adminEmail'=>'webmaster@example.com',
        'weatherApiKey'=>'1be0ed3547cbc0a415058b881eac9494',
    ),
);
