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
        return [
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>['index'],
                'users'=>['@'],
                'roles'=>['pointViewUser', 'playlistViewUser'],
            ], [
                'deny',  // deny all users
                'users'=>['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::app()->assets->register('/js/bootstrap/bootstrap-switch.min.js');

        Yii::app()->assets->register('/js/pages/monitoring/monitoring.js');
        Yii::app()->assets->register('/js/pointScreen.js');

        Yii::app()->assets->register('/css/bootstrap/bootstrap-switch.min.css');
        Yii::app()->assets->register('/css/switch/switch.css');
        Yii::app()->assets->register('/css/partial/points-list.css');
        Yii::app()->assets->register('/css/partial/playlists-list.css');
        Yii::app()->assets->register('/css/partial/screen-shot-box.css');
        Yii::app()->assets->register('/css/pages/monitoring.css');

        return true;
    }
}
