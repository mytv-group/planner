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
        'actions'=>array('index','ajaxGetPointScreen'),
        'users'=>array('@'),
          'roles'=>array('pointViewUser', 'playlistViewUser'),
      ),
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('deletePlaylist'),
        'users'=>array('@'),
          'roles'=>array('playlistUser'),
      ),
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('deletePoint'),
        'users'=>array('@'),
          'roles'=>array('pointUser'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  public function actionIndex()
  {
    $pointModelGrid = new CActiveDataProvider('Point', array(
        'criteria' => array(
          'condition' => "username='".Yii::app()->user->username."'",
          'order'=>'sync_time ASC',
        ),
        'pagination'=>array(
          'pageSize'=>10
        ),
    ));

    $playlistModelGrid = new CActiveDataProvider('Playlists', array(
        'criteria' => array(
          'condition' => "author='".Yii::app()->user->username."'",
          'order'=>'toDatetime ASC',
        ),
        'pagination'=>array(
            'pageSize'=>10
        ),
    ));

    $this->render('index',array(
        'pointModel'=> $pointModelGrid,
        'playlistModel' => $playlistModelGrid
    ));
  }

  public function actionDeletePlaylist($id)
  {
      $model = Playlists::model()->findByPk($id);
      $files = trim($model->files);
      $model->delete();
      if(isset($files) && ($files != '')) {
          $filesArr = explode(",", $files);
          Yii::app()->playlistService->deleteALLFilesFromPlaylist($id, $filesArr);
      }

      PlaylistToPoint::model()->deleteAllByAttributes([
          'id_playlist' => $id,
      ]);

      if (!isset($_GET['ajax'])) {
          $this->redirect(array('monitoring/index'),array(
                  'model'=>$model
          ));
      }
  }

  public function actionDeletePoint($id)
  {
      $model = Point::model()->findByPk($id);
      $model->delete();
      Yii::app()->pointService->deleteRelations(intval($id));

      if (!isset($_GET['ajax'])) {
          $this->redirect(array('monitoring/index'), array(
              'model' => $model
          ));
      }
  }

  public function actionAjaxGetPointScreen()
  {
    error_reporting(E_ALL);
    $answ = array('err', '');
    if (isset($_POST['pointId']) && isset($_POST['pointIp']))
    {
      $pointId = $_POST['pointId'];
      $pointIp = $_POST['pointIp'];
      $monitoring = new Monitoring();

      //$url = 'http://local.planner.rtvgroup.com.ua/images/screenshot.jpg';
      $url = 'http://'.$pointIp.'/screenshot.jpg';
      $appendPath = '/spool/points/'.$pointId;
      $contentPath = YiiBase::getPathOfAlias('webroot');
      $imgPath = $contentPath.$appendPath;

      //maybe this dir doesnt exist
      $pathAppendix = explode("/", $appendPath);
      foreach($pathAppendix as $folder)
      {
        $contentPath .= "/" . $folder;
        if (!file_exists($contentPath) && !is_dir($contentPath))
        {
          mkdir($contentPath);
        }
      }

      $files = scandir($imgPath);
      if(($files) > 0)
      {
        $entrance = false;
        foreach ($files as $file)
        {
          error_log($file);
          $res = $monitoring->prapareScreenshot($file, $imgPath, $url);
          if($res != false)
          {
            error_log("res1 ". json_encode($entrance));
            $entrance = true;
            $answ = array('ok', $res);
          }
        }

        if(!$entrance)
        {
          error_log("res2 ". json_encode($entrance));
          $res = $monitoring->receiveScreenshot($imgPath, $url);
          error_log(json_encode($res));
          if($res != false)
          {
            $answ = array('ok', $res);
          }
        }
      }
      else
      {
        $res = $monitoring->receiveScreenshot($imgPath, $url);
        if($res != false)
        {
          $answ = array('ok', $res);
        }
      }
    }

    echo json_encode($answ);
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Monitoring the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Monitoring::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Monitoring $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='monitoring-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function beforeAction($action) {
    if( parent::beforeAction($action) ) {
      $cs = Yii::app()->clientScript;


      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/monitoring/monitoring.js' );

      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/monitoring.css');

      return true;
    }
    return false;
  }
}
