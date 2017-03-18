<?php

class PointController extends Controller
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
                'roles'=>array('pointViewUser'),
            ),
            array('allow',
                'actions'=>array('create',
                    'update', 'addChannel', 'removeChannel',
                    'attachScreen', 'addPlaylistToChannel',
                    'attachScreenToPoint'
                ),
                'users'=>array('@'),
                'roles'=>array('pointEditUser'),
            ),
            array('allow',
                'actions'=>array('delete', 'removePlaylistFromChannel'),
                'users'=>array('@'),
                'roles'=>array('pointUser'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Point('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Point'])) {
            $model->attributes = $_GET['Point'];
        }

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('view',array(
            'model'=>$model,
            'screens' => Screen::model()->findAllByAttributes(['user_id' => Yii::app()->user->id])
        ));
    }

    public function actionCreate()
    {
        $model = new Point;
        $model->unsetAttributes();

        $renderCreate = function($model) {
            $this->render('create',array(
                'model'=>$model,
                'screens' => Screen::model()->findAllByAttributes(['user_id' => Yii::app()->user->id]),
                'widgets' => Widget::model()->findAll()
            ));
        };

        if(!isset($_POST['Point'])) {
            $renderCreate($model);
            return;
        }

        $model->attributes = $_POST['Point'];
        $model->username = Yii::app()->user->username;

        if($model->save()) {
            Yii::app()->tvSchedule->updatePointTable($model->id, $_POST['Point']);
            $model->SendRequestForUpdate($model->ip);
            $model->PrepareFilesForSync($model->id);
            $model->CreateChannelsForWindows($model->screen_id, $model->id);

            $this->redirect(['point/update','id'=>$model->id]);
        } else {
            $renderCreate($model);
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $renderUpdate = function($model) {
            $playlists = [];
            foreach ($model->playlists as $playlist) {
                $playlists[$playlist['type']][] = $playlist;
            };

            $this->render('update',array(
                'model'=>$model,
                'playlists' => $playlists,
                'screens' => Screen::model()->findAllByAttributes(['user_id' => Yii::app()->user->id]),
                'widgets' => Widget::model()->findAll()
            ));
        };

        if(!isset($_POST['Point'])) {
            $renderUpdate($model);
            return;
        }

        $author = $model->username;
        $model->attributes = $_POST['Point'];
        if ($author) {
            $model->username = $author;
        }

        if($model->validate() && $model->save()) {
            Yii::app()->tvSchedule->updatePointTable($model->id, $_POST['Point']);
            Point::model()->SendRequestForUpdate($model->ip);
            Point::model()->PrepareFilesForSync($model->getPrimaryKey());

            $this->render('view',array(
                  'model'=>$model,
                  'screens' => Screen::model()->findAllByAttributes(['user_id' => Yii::app()->user->id])
            ));
        } else {
            $renderUpdate($model);
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        $userId = Yii::app()->user->id;
        TVSchedule::model()->deleteAll(
            "`id_point` = :id_point AND `id_user` = :id_user",
            [':id_point' => $id, ':id_user' => $userId]
        );

        Point::model()->RemovePointSpoolPath($id);

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
        {
            //$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            $model=new Point('search');
            $model->unsetAttributes();  // clear any default values

            $this->redirect(array('point/index'),array(
                'model'=>$model
            ));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Playlist the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Point::model()->findByPk($id);

        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionAddPlaylistToChannel()
    {
        if(isset($_POST['channelType']) &&
              isset($_POST['plId']) &&
              isset($_POST['pointId']))
        {
            $channelType = intval($_POST['channelType']);
            $plId = intval($_POST['plId']);
            $pointId = intval($_POST['pointId']);

            $model = new PlaylistToPoint();
            $model->attributes = array(
                'id_point' => $pointId,
                'id_playlist' => $plId,
                'channel_type' => $channelType,
            );

            if ($model->save()) {
                echo json_encode(
                    array(
                            'status' => 'ok'
                    ));
            }
            else
            {
                echo json_encode(
                        array(
                                'status' => 'err',
                                'error' => 'Error during PlaylistToPoint model saving'
                        ));
            }
        }
        else
        {
            echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Incorrect POST data during PlaylistToPoint model saving'
                    ));
        }
    }

    public function actionRemovePlaylistFromChannel()
    {
        if(isset($_POST['channelType']) &&
              isset($_POST['plId']) &&
              isset($_POST['pointId'])
        ) {
            $channelType = intval($_POST['channelType']);
            $plId = intval($_POST['plId']);
            $pointId = intval($_POST['pointId']);

            if(PlaylistToPoint::model()->deleteAllByAttributes(array(
                'id_point' => $pointId,
                'id_playlist' => $plId,
                'channel_type' => $channelType,
            ))) {
                echo json_encode(
                    array(
                            'status' => 'ok'
                    ));
            }
            else
            {
                echo json_encode(
                    array(
                        'status' => 'err',
                        'error' => 'Error during PlaylistToPoint model deleting'
                    )
                );
            }
        }
        else
        {
            echo json_encode(
                array(
                    'status' => 'err',
                    'error' => 'Incorrect POST data during PlaylistToPoint model deleting'
                )
            );
        }
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery.datetimepicker.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/point.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/pointVolume.js' );
            $cs->registerCssFile(Yii::app()->baseUrl.'/css/custom-theme/jquery-ui-1.10.4.custom.css');
            $cs->registerCssFile(Yii::app()->baseUrl.'/css/jquery.datetimepicker.css');
            $cs->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            $cs->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');

            $cs->registerCssFile(Yii::app()->baseUrl.'/css/pages/point.css');

            return true;
        }
        return false;
    }
}
