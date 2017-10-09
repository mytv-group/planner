<?php

return [
    'pointInfo'=>[
        'class'=>'PointInfoRequester',
    ],
    'contentManager'=>[
        'class'=>'ContentManager',
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
    ]
];
