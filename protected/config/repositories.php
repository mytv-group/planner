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
    ],
    'fileRepository' => [
        'class'=>'FileRepository',
        'fileToFolder' => function() {
            return FileToFolder::model();
        }
    ],
    'folderRepository' => [
        'class'=>'FolderRepository',
        'fileToFolder' => function() {
            return FileToFolder::model();
        }
    ]
];
