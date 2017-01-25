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
                'actions'=>array('create', 'receiveTVBlocks',
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

        if(isset($_GET['Point']))
        {
            $model->attributes=$_GET['Point'];
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
        ));
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

    public function actionCreate()
    {
        $model = new Point;
        $model->unsetAttributes();

        if(isset($_POST['Point']))
        {
            $model->attributes = $_POST['Point'];
            $model->tv_schedule_blocks = '';
            $TVshceduleFromDatetime = array();
            $TVshceduleToDatetime = array();
            if(isset($_POST['Point']['TVshceduleFromDatetime']) &&
                    isset($_POST['Point']['TVshceduleToDatetime']))
            {
                $TVshceduleFromDatetime = $_POST['Point']['TVshceduleFromDatetime'];
                $TVshceduleToDatetime = $_POST['Point']['TVshceduleToDatetime'];
            }

            if($model->save())
            {
                $modelId = $model->getPrimaryKey();
                $this->CreateTVBlocks($modelId, $TVshceduleFromDatetime, $TVshceduleToDatetime);

                $model->SendRequestForUpdate($model->ip);
                $model->PrepareFilesForSync($model->getPrimaryKey());
                $model->CreateChannelsForWindows($model->screen_id, $model->id);

                $this->redirect(['point/update','id'=>$model->id]);
            }
            else
            {
                $this->render('create',array(
                        'model'=>$model,
                ));
            }
        }
        else
        {
            $this->render('create',array(
                    'model'=>$model,
            ));
        }
    }

    private function CreateTVBlocks($pointId, $fromTimeArr, $toTimeArr)
    {
        $generalDatatimeArr = array();

        if(!is_array($fromTimeArr))
        {
            $tmpArr[] = $fromTimeArr;
            $fromTimeArr = $tmpArr;
        }

        if(!is_array($toTimeArr))
        {
            $tmpArr = array();
            $tmpArr[] = $toTimeArr;
            $toTimeArr = $tmpArr;
        }

        for($ii = 0; $ii < count($fromTimeArr); $ii++)
        {
            if(isset($fromTimeArr[$ii]) && isset($toTimeArr[$ii]) &&
                    ($fromTimeArr[$ii] != '') && ($toTimeArr[$ii] != ''))
            {
                $dataFrom = $fromTimeArr[$ii];
                $dataTo = $toTimeArr[$ii];

                if(($dataFrom != false) && ($dataTo != false))
                {
                    $generalDatatimeArr[] = array($dataFrom, $dataTo);
                }
            }
        }

        if(count($generalDatatimeArr) > 0)
        {
            $connection = Yii::app()->db;
            $connection->active=true;
            $sql = '';

            foreach ($generalDatatimeArr as $key)
            {
                $sql .= "INSERT INTO `tvschedule` (`point_id`,`from`, `to`, `author`) VALUES " .
                        "('" . $pointId . "','".$key[0]."','".$key[1]."','".Yii::app()->user->name."');";
            }

            $command = $connection->createCommand($sql);
            $command->execute();

            $sql = "SELECT `id` FROM `tvschedule` WHERE `point_id` = '" . $pointId . "';";

            $command = $connection->createCommand($sql);
            $dataReader=$command->query();

            $tvscheduleBlocksIds = array();
            while(($row=$dataReader->read())!==false)
            {
                $tvscheduleBlocksIds[] = $row['id'];
            }

            $sql = "UPDATE `point` SET `tv_schedule_blocks` = '" . implode("," , $tvscheduleBlocksIds) . "' WHERE " .
                    "`id` = '" . $pointId . "';";

            $command = $connection->createCommand($sql);
            $command->execute();

            $connection->active = false;
        }
    }

    private function DeleteTVBlocks($pointId)
    {
        $connection = Yii::app()->db;
        $connection->active = true;
        $sql = "DELETE FROM `tvschedule` WHERE `point_id` = '" . $pointId . "';";
        $command = $connection->createCommand($sql);
        $command->execute();
        $connection->active = false;
    }

    public function actionReceiveTVBlocks()
    {
        if(isset($_POST['tvScheduleBlocks']) && ($_POST['tvScheduleBlocks'] != ''))
        {
            $tvScheduleBlocks = str_replace(",", "','", $_POST['tvScheduleBlocks']);
            $TVshceduleDatetimeArr = array();

            $connection = Yii::app()->db;
            $connection->active=true;
            $sql = "SELECT `from`, `to` FROM `tvschedule` WHERE `id` IN ('" . $tvScheduleBlocks . "');";

            $command = $connection->createCommand($sql);
            $dataReader=$command->query();

            $tvscheduleBlocksIds = array();
            $connection->active=false;
            while(($row=$dataReader->read())!==false)
            {
                $TVshceduleDatetimeArr[] = array($row['from'], $row['to']);
            }

            $answ = array(
                    'status'=> 'ok',
                    'TVshceduleDatetimeArr' => $TVshceduleDatetimeArr
            );

            echo json_encode($answ);
        }
        else
        {
            echo "err";
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
        $author = $model->username;

        if(isset($_POST['Point']))
        {
            $model->attributes = $_POST['Point'];
            if ($author) {
                $model->username = $author;
            }

            $TVshceduleFromDatetime = array();
            $TVshceduleToDatetime = array();
            if(isset($_POST['Point']['TVshceduleFromDatetime']) &&
                    isset($_POST['Point']['TVshceduleToDatetime']))
            {
                $TVshceduleFromDatetime = $_POST['Point']['TVshceduleFromDatetime'];
                $TVshceduleToDatetime = $_POST['Point']['TVshceduleToDatetime'];
            }

            if($model->validate()) {
                if($model->save()) {
                    $this->DeleteTVBlocks($id);
                    $this->CreateTVBlocks($id, $TVshceduleFromDatetime, $TVshceduleToDatetime);

                    Point::model()->SendRequestForUpdate($model->ip);
                    Point::model()->PrepareFilesForSync($model->getPrimaryKey());

                    $this->render('view',array(
                            'model'=>$model,
                    ));
                } else {
                    $this->render('update',array(
                            'model'=>$model,
                    ));
                }
            } else {
                $this->render('update',array(
                    'model'=>$model,
                ));
            }

        } else {
            $this->render('update',array(
                    'model'=>$model,
            ));
        }
    }

    /**
     *
     */
    public function actionAttachScreenToPoint()
    {
        $pointId = Yii::app()->request->getPost('pointId');
        $screenId = Yii::app()->request->getPost('screenId');

        $Point = new Point();
        $Channel = new Channel();
        $Point->CreateChannelsForWindows($screenId, $pointId);
        $channels = $Channel->findAll('id_point=:p AND window_id IS NOT NULL', array('p'=>$pointId));

        $channelsToSend = array();
        foreach ($channels as $item){
            $channelsToSend[] = [
                'id' => $item->id,
                'internalId' => $item->internalId,
                'windowId' => $item->window->id,
                'windowName' => $item->window->name,
            ];
        }

        echo (json_encode($channelsToSend));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        $this->DeleteTVBlocks($id);

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
                        ));
            }
        }
        else
        {
            echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Incorrect POST data during PlaylistToPoint model deleting'
                    ));
        }
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;
            //$cs->registerPackage('jquery');
            //$cs->registerPackage('history');

            //Yii::app()->clientScript->registerCoreScript('jquery');

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery.datetimepicker.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/proto/ChannelManager.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/proto/WidgetManager.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/point.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/pointTV.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/pointVolume.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/pointScreen.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/playlistChooseDialog.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/point/widgetChooseDialog.js' );

            /*$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/datetimePicker/jquery-ui-timepicker-addon.js' );*/

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/custom-theme/jquery-ui-1.10.4.custom.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.datetimepicker.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/point.css');

            return true;
        }
        return false;
    }
}
