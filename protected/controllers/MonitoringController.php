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
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
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

        Yii::app()->assets->registerPackage('bootstrap-switch');
        Yii::app()->assets->registerPackage('switch');

        Yii::app()->assets->register('/js/common/point-screen.js');

        Yii::app()->assets->register('/css/common/points-list.css');
        Yii::app()->assets->register('/css/common/playlists-list.css');
        Yii::app()->assets->register('/css/common/screen-shot-box.css');

        return true;
    }
}
