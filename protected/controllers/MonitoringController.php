<?php

class MonitoringController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout='//layouts/column1';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('index'),
        'users'=>array('@'),
          'roles'=>array('pointViewUser', 'playlistViewUser'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  public function actionIndex()
  {
      $this->render('index');
  }

  public function beforeAction($action) {
    if (parent::beforeAction($action)) {
      $cs = Yii::app()->clientScript;

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/monitoring/monitoring.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pointScreen.js' );

      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/switch/switch.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/monitoring.css');

      return true;
    }
    return false;
  }
}
