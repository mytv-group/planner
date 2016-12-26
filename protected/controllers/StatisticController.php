<?php

class StatisticController extends Controller
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
                'actions'=>array('index','view', 'export'),
                'users'=>array('@'),
                'roles'=>array('statisticsUser'),
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
    public function actionExport()
    {
        $inputs = array_filter($_GET['Statistic']);
        $fileName = 'statistics_'.date('Y-m-d');
        foreach ($inputs as $key => $value) {
            $fileName .= '_'.$key.'-'.$value;
        }
        $sanitized = preg_replace('/[^a-zA-Z0-9\-\._]/','', $fileName);
        $fileName .= '.csv';

        header("Content-Type: text/comma-separated-values; charset=utf-8");
        header("Content-Disposition: attachment; filename=$fileName");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        $model=new Statistic('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Statistic']))
            $model->attributes = $_GET['Statistic'];
        $provider = $model->search(false);
        $models = $provider->getData();

        $isFirstRow = true;
        $counter = 1;
        $output = '"sep=,"'.PHP_EOL;
        $output .= '#,Playback datetime,Channel,File,Point,Playlist'.PHP_EOL;

        foreach ($models as $model) {
            $output .= $counter.','
              . $model->dt_playback.',';

            if (isset(Channel::$types[$model->channel])) {
                $output .= strtoupper(Channel::$types[$model->channel]).',';
            } else {
                $output .= ''.',';
            }

            $output .= substr($model->file_name, 13, strlen($model->file_name)).',';

            if (isset($model->point->name)) {
                $output .= $model->point->name.',';
            } else {
                $output .= $model->id_point.',';
            }

            if(isset($data->playlist->name)) {
                $output .= $model->playlist->name.',';
            } else {
                $output .= $model->id_playlist.',';
            }

            $output .= PHP_EOL;

            $counter++;
        }

        echo $output;
        exit;
    }

    /**
     * Lists all models.
     */
     public function actionIndex()
     {
         $model=new Statistic('search');
         $model->unsetAttributes();  // clear any default values
         if(isset($_GET['Statistic']))
             $model->attributes = $_GET['Statistic'];

         if (isset($_GET['submit']) && ($_GET['submit'] === 'export')) {
              $this->actionExport();
              exit;
         }

         $this->render('index',array(
             'model'=>$model,
         ));
     }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Statistic the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Statistic::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Statistic $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='statistic-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            $cs = Yii::app()->clientScript;

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            //$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/statistic.js' );

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/statistic.css');
            return true;
        }
        return false;
    }
}
