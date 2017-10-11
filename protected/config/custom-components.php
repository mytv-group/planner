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
