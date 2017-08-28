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
            array('allow',
                'actions'=>array('index','preview'),
                'users'=>array('@'),
                'roles'=>array('widgetViewUser'),
            ),
            array('allow',
                'actions'=>array('update', 'copy'),
                'users'=>array('@'),
                'roles'=>array('widgetEditUser'),
            ),
            array('allow',
                'actions'=>array('delete'),
                'users'=>array('@'),
                'roles'=>array('widgetUser'),
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

        $config = [];
        if($model->config) {
            $config = json_decode($model->config);
        }

        $this->widget('application.widgets.' . ucfirst($model->name) . 'Widget', [
            'id' => $model->id,
            'type' => 'preview',
            'config' => $config
        ]);
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST['Widget']) && isset($_POST['WidgetConfig'])) {
            $data = $_POST['Widget'];
            $data['config'] = json_encode($_POST['WidgetConfig']);
            $model->attributes = $data;
            if ($model->save()) {
                $this->redirect('/widget');
            }
        }

        $this->render('update',array(
            'model' => $model,
            'action' => 'Update'
        ));
    }

    public function actionCopy($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST['Widget']) && isset($_POST['WidgetConfig'])) {
            $data = $_POST['Widget'];
            $data['name'] = $model->name;
            $data['config'] = json_encode($_POST['WidgetConfig']);
            $newModel = new Widget;
            $newModel->unsetAttributes();
            $newModel->attributes = $data;

            if ($newModel->save()) {
                $this->redirect('/widget');
            }
        }

        $this->render('update',array(
            'model' => $model,
            'action' => 'Copy'
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        $this->redirect('/widget');
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
        if ($model===null)
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::app()->assets->register('/js/pages/widget/widget.js');
        Yii::app()->assets->register('/css/pages/widget.css');

        return true;
    }
}
