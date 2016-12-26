<?php

return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'heapViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'heapEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => array(
            'heapViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'heapUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => array(
            'heapEditUser'
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'playlistViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'playlistEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => array(
            'playlistViewUser'
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'playlistUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => array(
            'playlistEditUser'
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'netViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'netEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => array(
            'netViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'netUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => array(
            'netEditUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'pointViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'pointEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => array(
            'pointViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'pointUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => array(
            'pointEditUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'screenViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'screenEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => array(
            'screenViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'screenUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => array(
            'screenEditUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'widgetViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'widgetEditUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => array(
            'widgetViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'widgetUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => array(
            'widgetEditUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'statisticsViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Statistics user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'statisticsUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Statistics user',
        'children' => array(
            'statisticsViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'userViewUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User editor user',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'userEditorUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User editor user',
        'children' => array(
            'userViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ),
    'demo' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Demo',
        'children' => array(
            'heapViewUser',
            'playlistViewUser',
            'netViewUser',
            'pointViewUser',
            'screenViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ),
    'monitoring' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => array(
            'playlistViewUser',
            'pointViewUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ),
   'moderator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => array(
            'heapUser',
            'playlistUser',
            'pointUser',
            'screenUser',
            'widgetUser',
            'statisticsUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ),
    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => array(
            'moderator',
            'netUser',
            'userEditorUser',
        ),
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ),
);
