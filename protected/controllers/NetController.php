<?php

class NetController extends Controller
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
      /*'postOnly + delete',*/ // we only allow deletion via POST request
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
        'actions'=>array('index','view'),
        'users'=>array('*'),
      ),
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('create','update'),
        'users'=>array('@'),
      ),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('admin','delete'),
        'users'=>array('admin'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $this->render('view',array(
      'model'=>$this->loadModel($id),
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Net;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Net']))
    {
      $model->attributes=$_POST['Net'];
      if($model->save())
      {
        $pointsAttached = $_POST['Net']['pointsattached'];
        $pointToNet = new PointToNet();
        $pointToNet::model()->deleteAll('net_id = ' . $model->id);
        foreach ($pointsAttached as $attached)
        {
          $pointToNet->attributes = array(
              'net_id' => $model->id,
              'point_id' => $attached
          );
          $pointToNet->save();
          $PointModel = Point::model()->findByPk($attached);
          $PointModel->CreateChannelsForWindows($model->screen_id, $attached);
          unset($PointModel);
          $pointToNet = new PointToNet();
        }

        $model->CreateChannelsForWindows($model->screen_id, $model->id);
        $this->redirect(array('update','id'=>$model->id));
      }
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Net']))
    {
      $model->attributes=$_POST['Net'];
      if($model->save()){
        $pointsAttached = $_POST['Net']['pointsattached'];
        $pointToNet = new PointToNet();
        $pointToNet::model()->deleteAll('net_id = ' . $model->id);
        foreach ($pointsAttached as $attached)
        {
          $pointToNet->attributes = array(
              'net_id' => $model->id,
              'point_id' => $attached
          );
          $pointToNet->save();
          $PointModel = Point::model()->findByPk($attached);
          $PointModel->CreateChannelsForWindows($model->screen_id, $attached);
          unset($PointModel);
          $pointToNet = new PointToNet();
        }

        $model->CreateChannelsForWindows($model->screen_id, $model->id);
        $this->redirect(array('view','id'=>$model->id));
      }

    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $model=new Net('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Net']))
      $model->attributes=$_GET['Net'];

    $this->render('index',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Net the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Net::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Net $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='net-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function beforeAction($action) {
    if( parent::beforeAction($action) ) {
      /* @var $cs CClientScript */
      $cs = Yii::app()->clientScript;

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-multiselect.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-multiselect-collapsible-groups.js' );

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/proto/ChannelManager.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/net/net.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/net/netChannels.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/net/netScreen.js' );
      $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/net/playlistChooseDialog.js' );

      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap.css.map');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/custom-theme/jquery-ui-1.10.4.custom.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');
      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-multiselect.css');

      Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/net.css');

      return true;
    }
    return false;
  }
}
