<?php

return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => null
    ),
	'heapViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Heap user',
		'children' => array(
			'guest', 
		),
		'bizRule' => null,
		'data' => null
	),
	'heapEditUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Heap user',
		'children' => array(
			'heapViewUser',
		),
		'bizRule' => null,
		'data' => null
	),
    'heapUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Heap user',
        'children' => array(
        	'heapEditUser'
        ),
        'bizRule' => null,
        'data' => null
    ),
	'playlistViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Playlist user',
		'children' => array(
			'guest',
		),
		'bizRule' => null,
		'data' => null
	),
	'playlistEditUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Playlist user',
		'children' => array(
			'playlistViewUser'
		),
		'bizRule' => null,
		'data' => null
	),
    'playlistUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Playlist user',
        'children' => array(
            'playlistEditUser'          
        ),
        'bizRule' => null,
        'data' => null
    ),
	'netViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Net user',
		'children' => array(
			'guest',
		),
		'bizRule' => null,
		'data' => null
	),
	'netEditUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Net user',
		'children' => array(
			'netViewUser',
		),
		'bizRule' => null,
		'data' => null
	),
    'netUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Net user',
        'children' => array(
            'netEditUser',          
        ),
        'bizRule' => null,
        'data' => null
    ),
	'pointViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Point user',
		'children' => array(
			'guest',
		),
		'bizRule' => null,
		'data' => null
	),
	'pointEditUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Point user',
		'children' => array(
			'pointViewUser',
		),
		'bizRule' => null,
		'data' => null
	),
    'pointUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Point user',
        'children' => array(
            'pointEditUser',          
        ),
        'bizRule' => null,
        'data' => null
    ),
	'screenViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Screen user',
		'children' => array(
			'guest',
		),
		'bizRule' => null,
		'data' => null
	),
	'screenEditUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Screen user',
		'children' => array(
			'screenViewUser',
		),
		'bizRule' => null,
		'data' => null
	),
    'screenUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Screen user',
        'children' => array(
            'screenEditUser',          
        ),
        'bizRule' => null,
        'data' => null
    ),
	'widgetViewUser' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Widget user',
			'children' => array(
					'guest',
			),
			'bizRule' => null,
			'data' => null
	),
	'widgetEditUser' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Widget user',
			'children' => array(
					'widgetViewUser',
			),
			'bizRule' => null,
			'data' => null
	),
	'widgetUser' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Widget user',
			'children' => array(
					'widgetEditUser',
			),
			'bizRule' => null,
			'data' => null
	),
	'userViewUser' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'User editor user',
		'children' => array(
			'guest',
		),
		'bizRule' => null,
		'data' => null
	),
    'userEditorUser' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'User editor user',
        'children' => array(
            'userViewUser',          
        ),
        'bizRule' => null,
        'data' => null
    ),
	'halfDemo' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Demo',
			'children' => array(
					'heapUser',
					'playlistUser',
					'pointUser',
			),
			'bizRule' => null,
			'data' => null
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
			'widgetViewUser',
		),
		'bizRule' => null,
		'data' => null
	),
   'moderator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => array(
            'heapUser', 
            'playlistUser',
            'netUser',  
            'pointUser', 
        	'widgetUser',   
            'screenUser'   
        ),
        'bizRule' => null,
        'data' => null
    ),
    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => array(
            'moderator',
            'userEditorUser'         
        ),
        'bizRule' => null,
        'data' => null
    ),
);