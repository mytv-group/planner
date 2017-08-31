<?php
return [
    'jq' => [
        'pathFromRoot' => '/protected/extensions/packages/jquery/',
        'js' => ['jquery-1.11.0.js'],
    ],
    'jquery-ui' => [
        'pathFromRoot' => '/protected/extensions/packages/jquery-ui/',
        'js' => ['jquery-ui-1.10.4.min.js'],
        'css' => ['jquery-ui-1.10.4.min.css'],
        'files' => [
            'images/animated-overlay.gif',
            'images/ui-bg_diagonals-thick_18_b81900_40x40.png',
            'images/ui-bg_diagonals-thick_20_666666_40x40.png',
            'images/ui-bg_flat_10_000000_40x100.png',
            'images/ui-bg_glass_100_f6f6f6_1x400.png',
            'images/ui-bg_glass_100_fdf5ce_1x400.png',
            'images/ui-bg_glass_65_ffffff_1x400.png',
            'images/ui-bg_gloss-wave_35_f6a828_500x100.png',
            'images/ui-bg_highlight-soft_100_eeeeee_1x100.png',
            'images/ui-bg_highlight-soft_75_ffe45c_1x100.png',
            'images/ui-icons_222222_256x240.png',
            'images/ui-icons_228ef1_256x240.png',
            'images/ui-icons_ef8c08_256x240.png',
            'images/ui-icons_ffd27a_256x240.png',
            'images/ui-icons_ffffff_256x240.png'
        ],
        'depends' => ['jq'],
    ],
    'bootstrap' => [
        'pathFromRoot' => '/protected/extensions/packages/bootstrap/',
        'js' => ['bootstrap.min.js'],
        'css' => [
            'css/bootstrap.min.css',
            'css/bootstrap-theme.min.css'
        ],
        'files' => [
            'fonts/glyphicons-halflings-regular.eot',
            'fonts/glyphicons-halflings-regular.svg',
            'fonts/glyphicons-halflings-regular.ttf',
            'fonts/glyphicons-halflings-regular.woff',
            'fonts/glyphicons-halflings-regular.woff2'
        ],
        'depends' => ['jq'],
    ],
    'menu-decorator' => [
        'pathFromRoot' => '/protected/extensions/packages/',
        'js' => ['menu-decorator/menu-decorator.js'],
        'css' => ['menu-decorator/menu-decorator.css'],
        'depends' => ['jq'],
    ],
    'js-tree' => [
        'pathFromRoot' => '/protected/extensions/packages/js-tree/',
        'js' => ['jstree.min.js'],
        'css' => ['style.min.css'],
        'files' => [
            '32px.png',
            '40px.png',
            'throbber.gif'
        ],
        'depends' => ['jq'],
    ],
    'j-player' => [
        'pathFromRoot' => '/protected/extensions/packages/j-player/',
        'js' => ['jquery.jplayer.min.js'],
        'css' => ['jplayer.blue.monday.css'],
        'files' => [
            'jplayer.blue.monday.jpg',
            'jplayer.blue.monday.seeking.gif',
            'jplayer.blue.monday.video.play.png'
        ],
        'depends' => ['jq'],
    ],
    'fileuploader' => [
        'pathFromRoot' => '/protected/extensions/packages/fileuploader/',
        'js' => [
            'vendor/jquery.ui.widget.js',
            'jquery.fileupload.js',
            'jquery.iframe-transport.js'
        ],
        'css' => [
            'jquery.fileupload.bootstrap.css',
            'jquery.fileupload.css'
        ],
        'depends' => ['jq', 'bootstrap'],
    ],
    'bootstrap-switch' => [
        'pathFromRoot' => '/protected/extensions/packages/bootstrap-switch/',
        'js' => ['bootstrap-switch.min.js'],
        'css' => ['bootstrap-switch.min.css'],
        'depends' => ['jq', 'bootstrap'],
    ],
    'datetimepicker' => [
        'pathFromRoot' => '/protected/extensions/packages/datetimepicker/',
        'js' => ['jquery.datetimepicker.js'],
        'css' => ['jquery.datetimepicker.css'],
        'depends' => ['jq'],
    ],
    'switch' => [
        'pathFromRoot' => '/protected/extensions/packages/switch/',
        'css' => ['switch.css'],
    ]
];
