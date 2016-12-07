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
                        'actions'=>array('attachWidget'),
                        'users'=>array('@'),
                        'roles'=>array('widgetEditUser'),
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
}
