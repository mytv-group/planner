<?php

class ScreenController extends Controller
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
            //'postOnly + delete', // we only allow deletion via POST request
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
                'users'=>array('@'),
                'roles'=>array('screenViewUser'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
                'roles'=>array('screenEditUser'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('delete'),
                'users'=>array('screenUser'),
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
        $model=new Screen;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Screen']))
        {
            $attributes = $_POST['Screen'];
            $attributes['user_id'] = Yii::app()->user->getId();

            $model->attributes=$attributes;
            if($model->save())
                $this->redirect(array('update','id'=>$model->id));
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

        if(isset($_POST['Screen']))
        {
            $model->attributes = $_POST['Screen'];
            if($model->save()){
                if(isset($_POST['Blocks'])){
                    $blocks = $_POST['Blocks'];
                    Window::model()->deleteAllByAttributes(array('screen_id'=>$model->id));

                    foreach ($blocks as $name => $block)
                    {
                        $windowModel = new Window;
                        $windowParams = explode(",", $block);
                        $window = array(
                            'screen_id'=>$model->id,
                            'name'=>$name,
                            'height'=>$windowParams[1],
                            'width'=>$windowParams[0],
                            'top'=>$windowParams[3],
                            'left'=>$windowParams[2],
                            'authorId'=>Yii::app()->user->getId()
                        );
                        $windowModel->attributes = $window;
                        $windowModel->save();
                    }
                }
                $this->redirect(array('view','id'=>$model->id));
            }

        }

        $windows = Window::model()->findAllByAttributes(array('screen_id'=>$model->id));
        $this->render('update',array(
            'model'=>$model,
            'windows'=>$windows,
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
     * Manages all models.
     */
    public function actionIndex()
    {
        $model=new Screen('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Screen']))
            $model->attributes=$_GET['Screen'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Screen the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Screen::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Screen $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='screen-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            $cs = Yii::app()->clientScript;

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/screen/screen.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/screen/screenBlockProto.js' );

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/custom-theme/jquery-ui-1.10.4.custom.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/screen.css');

            return true;
        }
        return false;
    }
}
