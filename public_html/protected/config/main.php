<?php
//TODO rewrite defines via config option params attributes

define('SPOOL_PATH', dirname($_SERVER["DOCUMENT_ROOT"])."/public_html/spool/");
define('FILE_TO_FOLDER', "fileToFolder");

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'RTV Web-server 2.0',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		//'application.extensions.EFineUploader.*',
		//'application.extensions.EJuiDateTimePicker.*',
        'application.modules.rbac.controllers.RbacController'
	),

    'theme'=>'basic',

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'ass',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
            'class' => 'WebUser',
			'allowAutoLogin'=>true,
		),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
		'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'caseSensitive'=>true,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'admin/user/<id>' => 'admin/user',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=planner',
			'emulatePrepare' => true,
			'username' => 'planner',
			'password' => 'f47QwKYuw3e4txZx',
			'charset' => 'utf8',
		),
		'pointInfo'=>array(
			'class'=>'PointInfoRequester',
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);