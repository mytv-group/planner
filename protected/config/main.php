<?php

return [
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'RTV Web-server 2.0',
    'preload'=>['log'],
    'import'=>array_merge([
            'application.models.*',
            'application.components.*',
            'application.repositories.*',
            'application.widgets.*'
        ],
        include('import-widgets.php')
    ),
    'aliases' => array(
        'getid3' => 'ext.vendor.james-heinrich.getid3.getid3'
    ),
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
        'interactionUrl' => 'http://127.0.0.1:1337',
        'fileUploadMaxSize' => 4 * 1024 * 1024 * 1024,
        'fileUploadAllowedExtensions' => [
            'gif', 'jpg', 'jpeg', 'png', 'webm', 'ogg',
            'wav', 'avi', 'mov', 'mkv', 'mp3', 'mp4', 'swf'
        ]
    ],
];
