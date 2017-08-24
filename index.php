<?php

date_default_timezone_set('Europe/Kiev');

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV',
        (getenv('APPLICATION_ENV') ?
                getenv('APPLICATION_ENV') : 'production'));

if((APPLICATION_ENV === 'dev')
  || (isset($_COOKIE['debug']) && ($_COOKIE['debug'] === '1'))
) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    ini_set('error_log','php_errors.log');

    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}

if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
    define('SYMLINK', 1);
    define('HTTP_REQUEST_TO_POINT', 1);
}

require_once($yii);

Yii::createWebApplication($config)->run();
