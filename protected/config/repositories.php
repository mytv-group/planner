<?php

return [
    'playlistsRepository' => [
        'class'=>'PlaylistsRepository',
        'user' => function() {
            return Yii::app()->user;
        },
    ],
    'pointsRepository' => [
        'class'=>'PointsRepository',
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
    ]
];
