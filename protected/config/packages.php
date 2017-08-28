<?php
return [
    'jquery' => [
        'baseUrl' => '/',
        'js' => ['js/lib/jquery-1.11.0.js'],
    ],
    'jquery-ui' => [
        'baseUrl' => '/',
        'js' => ['js/lib/jquery-ui-1.10.4.min.js'],
        'css' => ['css/ui-lightness/jquery-ui-1.10.4.css'],
        'image' => [
            'css/ui-lightness/images/animated-overlay.gif',
            'css/ui-lightness/images/ui-bg_diagonals-thick_18_b81900_40x40.png',
            'css/ui-lightness/images/ui-bg_diagonals-thick_20_666666_40x40.png',
            'css/ui-lightness/images/ui-bg_flat_10_000000_40x100.png',
            'css/ui-lightness/images/ui-bg_glass_100_f6f6f6_1x400.png',
            'css/ui-lightness/images/ui-bg_glass_100_fdf5ce_1x400.png',
            'css/ui-lightness/images/ui-bg_glass_65_ffffff_1x400.png',
            'css/ui-lightness/images/ui-bg_gloss-wave_35_f6a828_500x100.png',
            'css/ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png',
            'css/ui-lightness/images/ui-bg_highlight-soft_75_ffe45c_1x100.png',
            'css/ui-lightness/images/ui-icons_222222_256x240.png',
            'css/ui-lightness/images/ui-icons_228ef1_256x240.png',
            'css/ui-lightness/images/ui-icons_ef8c08_256x240.png',
            'css/ui-lightness/images/ui-icons_ffd27a_256x240.png',
            'css/ui-lightness/images/ui-icons_ffffff_256x240.png'
        ],
        'depends' => ['jquery'],
    ],
    'bootstrap' => [
        'baseUrl' => '/',
        'js' => ['js/bootstrap/bootstrap.min.jss'],
        'css' => [
            'css/bootstrap/bootstrap.min.css',
            'css/bootstrap/bootstrap-theme.min.css'
        ],
        'depends' => ['jquery'],
    ]
];
