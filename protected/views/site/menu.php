<?php

$mm = $this->widget ( 'zii.widgets.CMenu', array (
    'activeCssClass'=>'active',
    'items' => array (
        array (
            'label' => 'Login',
            'url' => array (
                '/site/login'
            ),
            'visible' => Yii::app ()->user->isGuest,
        ),

        array (
            'label' => 'Monitoring',
            'url' => array (
                '/monitoring'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "pointViewUser" ) &&
              Yii::app ()->user->checkAccess ( "playlistViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/monitoring'
            ),
        ),

        array (
            'label' => 'Content Heap',
            'url' => array (
                '/heap'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "heapViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/heap'
            )
        ),

        array (
            'label' => 'Playlist',
            'url' => array (
                '/playlists'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "playlistViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/playlists'
            )
        ),

        array (
            'label' => 'Network',
            'url' => array (
                '/net'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
                      Yii::app ()->user->checkAccess ( "netViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/net'
            )
        ),

        array (
            'label' => 'Point',
            'url' => array (
                '/point'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "pointViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/point'
            )
        ),

        array (
            'label' => 'Screen',
            'url' => array (
                '/screen'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
                    Yii::app ()->user->checkAccess ( "screenViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/screen'
            )
        ),

        array (
            'label' => 'Widget',
            'url' => array (
                '/widget'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "widgetViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/widget'
            )
        ),

        array (
            'label' => 'Statistics',
            'url' => array (
                '/statistic'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "statisticsViewUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/statistic'
            )
        ),

        array (
            'label' => 'User',
            'url' => array (
                '/user'
            ),
            'visible' => ! Yii::app ()->user->isGuest &&
              Yii::app ()->user->checkAccess ( "userEditorUser" ),
            'itemOptions' => array(
                'class' => 'list-group-item',
                'data-url'=> '/user'
            )
        ),
    )
));

$this->widget ('zii.widgets.CMenu', array (
    'items' => $this->menu,
    'htmlOptions'=>array('class'=>'OperationsMenu list-group HiddenOnLoad'),
));
