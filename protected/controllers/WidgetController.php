<?php

class WidgetController extends Controller
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
                'actions'=>array('index','preview'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model=new Widget('search');

        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Widget']))
            $model->attributes=$_GET['Widget'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function actionPreview($id)
    {
        $model = $this->loadModel($id);

        if(!is_file(Yii::getPathOfAlias('application.widgets.' . ucfirst($model->name) . 'Widget') . '.php')) {
            throw new CHttpException(404, 'The requested widget does not exist.');
        }

        $this->widget('application.widgets.' . ucfirst($model->name) . 'Widget', [
            'type' => 'preview'
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Widget the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Widget::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Widget $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='widget-form')
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

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/widget/widget.js' );

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap.css.map');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/widget.css');

            return true;
        }
        return false;
    }
}
