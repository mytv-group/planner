<?php

return [
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'RTV Web-server Console',
    'preload'=>['log'],
    'components'=>[
        'db' => include('db.php'),
        'log'=>[
            'class'=>'CLogRouter',
            'routes'=>[
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ],
            ],
        ],
    ],
    'params'=>[
        'partitionStartTimestamp' => 1483221600,
        'partitionStep' => 2419200 // 1 month
    ],
];
