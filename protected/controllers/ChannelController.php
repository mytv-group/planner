<?php

class ChannelController extends Controller
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
            //  'postOnly + delete', // we only allow deletion via POST request
        );
    }

    function hasAccess(){
        return true;
    }

    public function accessRules()
    {
        return array(
                array('allow',
                        'actions'=>array('addChannel','addPlaylistToChannel'),
                        'users'=>array('@'),
                        'roles'=>array('playlistEditUser', 'pointEditUser'),
                ),
                array('allow',
                        'actions'=>array('attachWidget'),
                        'users'=>array('@'),
                        'roles'=>array('widgetEditUser'),
                ),
                array('allow',
                        'actions'=>array('removeChannel','removePlaylistFromChannel'),
                        'users'=>array('@'),
                        'roles'=>array('pointUser', 'playlistUser'),
                ),
                array('allow',
                        'actions'=>array('detachWidget'),
                        'users'=>array('@'),
                        'roles'=>array('widgetUser'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Point the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Point::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Point $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='point-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAttachWidget()
    {
        $request = Yii::app()->request;
        if(($channelId = $request->getParam('channelId')) &&
            ($widgetId = $request->getParam('widgetId'))) {

            $model = new WidgetToChannel();
            $model->attributes = array(
                'channel_id' => $channelId,
                'widget_id' => $widgetId
            );

            if($model->save()) {
                echo json_encode(
                    array(
                            'status' => 'ok'
                    ));
            } else {
                echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Error during attaching widget'
                    ));
            }
        } else {
            echo json_encode(
                array(
                'status' => 'err',
                'error' => 'Incorrect POST data during AttachWidget action'
            ));
        }
    }

    public function actionDetachWidget()
    {
        $request = Yii::app()->request;
        if($channelId = $request->getParam('channelId')) {
            if(WidgetToChannel::model()->deleteAll("channel_id = :channel_id", array("channel_id" => $channelId))) {
                echo json_encode(
                        array(
                                'status' => 'ok'
                        ));
            } else {
                echo json_encode(
                        array(
                                'status' => 'err',
                                'error' => 'Error during detaching widget'
                        ));
            }
        } else {
            echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Incorrect POST data during DetachWidget action'
                    ));
        }
    }

    public function actionAddPlaylistToChannel()
    {
        if(isset($_POST['channelId']) &&
                isset($_POST['plId']))
        {
            $channelId = $_POST['channelId'];
            $plId = $_POST['plId'];

            if(isset($_POST['isNet'])){

            } else {
                if(PlaylistToChannel::model()->AddPlaylistToChannel($channelId, $plId))
                {
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
                                    'error' => 'Error during PlaylistToChannel model saving'
                            ));
                }
            }
        }
        else
        {
            echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Incorrect POST data during PlaylistToChannel model saving'
                    ));
        }
    }

    public function actionRemovePlaylistFromChannel()
    {
        if(isset($_POST['channelId']) &&
                isset($_POST['plId']))
        {
            $channelId = $_POST['channelId'];
            $plId = $_POST['plId'];

            if(isset($_POST['isNet'])){

            } else {
                if(PlaylistToChannel::model()->RemovePlaylistFromChannel($channelId, $plId))
                {
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
                                    'error' => 'Error during PlaylistToChannel model deleting'
                            ));
                }
            }
        }
        else
        {
            echo json_encode(
                    array(
                            'status' => 'err',
                            'error' => 'Incorrect POST data during PlaylistToChannel model deleting'
                    ));
        }
    }

    public function actionAddChannel()
    {
        if(isset($_POST['itemId']))
        {
            $itemId = $_POST['itemId'];

            $res = Channel::model()->AddChannelToPoint($itemId);
            if($res["status"])
            {
                echo json_encode(
                    array(
                        "status" => 'ok',
                        "id" => $res['id'],
                        "internalId" => $res['internalId']
                    ));
            }
            else
            {
                echo json_encode(
                    array(
                        'status' => 'err',
                        'error' => $res['error']
                    ));
            }
        }
        else
        {
            echo json_encode(
                array(
                    'status' => 'err',
                    'error' => 'Incorrect POST data during Channel model creating'
                ));
        }
    }

    public function actionRemoveChannel()
    {
        if(isset($_POST['channelId']))
        {
            $channelId = $_POST['channelId'];

            $res = Channel::model()->RemoveChannel($channelId);
            echo json_encode(
                array(
                        "status" => 'ok'
                ));
        }
        else
        {
            echo json_encode(
                array(
                        'status' => 'err',
                        'error' => 'Incorrect POST data during Channel model creating'
                ));
        }
    }
}
