<?php

return [
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'RTV Web-server 2.0',
    'preload'=>['log'],
    'import'=>[
        'application.models.*',
        'application.components.*',
        'application.repositories.*',
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
    'components'=>array_merge(
        include('components.php'),
        include('custom-components.php'),
        include('repositories.php')
    ),
    'params'=>[
        'adminEmail'=>'webmaster@example.com',
        'weatherApiKey'=>'1be0ed3547cbc0a415058b881eac9494',
        'interactionUrl' => 'http://127.0.0.1:1337'
    ],
];
