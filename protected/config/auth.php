<?php

return [
    'guest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'heapViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'heapEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => [
            'heapViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'heapUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => [
            'heapEditUser'
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'playlistViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'playlistEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => [
            'playlistViewUser'
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'playlistUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => [
            'playlistEditUser'
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'netViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'netEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => [
            'netViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'netUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => [
            'netEditUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'pointViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'pointEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => [
            'pointViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'pointUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => [
            'pointEditUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'screenViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'screenEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => [
            'screenViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'screenUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => [
            'screenEditUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'widgetViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'widgetEditUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => [
            'widgetViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'widgetUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Widget user',
        'children' => [
            'widgetEditUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'statisticsViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Statistics user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'statisticsUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Statistics user',
        'children' => [
            'statisticsViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'userViewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User editor user',
        'children' => [
            'guest',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'userEditorUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User editor user',
        'children' => [
            'userViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => false,
        ],
    ],
    'demo' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Demo',
        'children' => [
            'heapViewUser',
            'playlistViewUser',
            'netViewUser',
            'pointViewUser',
            'screenViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ],
    'monitoring' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => [
            'playlistViewUser',
            'pointViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ],
   'moderator' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => [
            'heapUser',
            'playlistUser',
            'netUser',
            'pointUser',
            'screenUser',
            'widgetViewUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ],
    'admin' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => [
            'moderator',
            'userEditorUser',
            'statisticsUser',
            'widgetUser',
        ],
        'bizRule' => null,
        'data' => [
            'avaliable' => true,
        ],
    ],
];
