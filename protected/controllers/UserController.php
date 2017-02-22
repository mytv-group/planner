<?php

class UserController extends Controller
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
                'actions'=>array('index','view'),
                'users'=>array('@'),
                'roles'=>array('userViewUser'),
            ),
            array('allow',
                'actions'=>array('create', 'update'),
                'users'=>array('@'),
                'roles'=>array('userEditorUser'),
            ),
            array('allow',
                'actions'=>array('delete'),
                'users'=>array('@'),
                'roles'=>array('admin'),
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
        $model=new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $userData = $_POST['User'];

            $model->attributes=$userData;
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $rolesList = [];
        foreach (Yii::app()->authManager->getRoles() as $key => $val) {
            if($val->data['avaliable']) {
                $rolesList[$key] = $key;
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'rolesList'=>$rolesList,
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

        if(isset($_POST['User']))
        {
            $userData = $_POST['User'];

            $model->attributes=$userData;
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $rolesList = [];
        foreach (Yii::app()->authManager->getRoles() as $key => $val) {
            if($val->data['avaliable']) {
                $rolesList[$key] = $key;
            }
        }

        $this->render('update',array(
                'model'=>$model,
                'rolesList'=>$rolesList,
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
        if(!isset($_GET['ajax'])){
            $model=new User('search');
            $model->unsetAttributes();  // clear any default values
            $this->redirect(array('user/index'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new User('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['User']))
            $model->attributes=$_GET['User'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function RoleSrting($role)
    {
        $stringifyRole = '';
        if (is_object(json_decode($role)))
        {
            $role = json_decode($role);
            $stringifyRole = '';
            foreach ($role as $key => $val)
            {
                $stringifyRole .= strtoupper($key);
                $permCount = 0;
                foreach ($val as $permName => $permVal)
                {
                    if($permVal == 1)
                    {
                        $permCount++;
                    }
                }

                if($permCount > 0)
                {
                    $stringifyRole .= "(";
                    foreach ($val as $permName => $permVal)
                    {
                        if($permVal == 1)
                        {
                            $stringifyRole .= $permName . ",";
                        }
                    }
                    $stringifyRole = substr($stringifyRole, 0, -1);
                    $stringifyRole .= "); ";
                }
                else
                {
                    $stringifyRole .= "(none); ";
                }
            }
        }

        return    $stringifyRole;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=User::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            $cs = Yii::app()->clientScript;

            
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/user.js' );

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            return true;
        }
        return false;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
