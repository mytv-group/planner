<?php

Yii::import('application.vendor.*');
require_once('getid3/getid3.php');

class PlaylistsController extends Controller
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
            array('allow',
                'actions'=>array('index','view'),
                'users'=>array('@'),
                'roles'=>array('playlistViewUser'),
            ),
            array('allow',
                'actions'=>array('create','upload','addfilefromheap','setFileOrder', 'update','upload'),
                'users'=>array('@'),
                'roles'=>array('playlistEditUser'),
            ),
            array('allow',
                'actions'=>array('delete','deletefilefrompl'),
                'users'=>array('@'),
                'roles'=>array('playlistUser'),
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
        $model=$this->loadModel($id);
        $author = $model->author;
        $stream = new Stream();

        if(count($model->stream) > 0) {
            $stream = $model->stream[0];
        }

        $this->render('view',array(
            'model'=>$model,
            'stream'=>$stream,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Playlists();
        $model->type = 1; // for radioButtonList default value
        $stream = new Stream();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Playlists'])) {
            $model->attributes=$_POST['Playlists'];
            if($model->save()) {
                if(isset($_POST['Stream']['url']) &&
                        ($_POST['Playlists']['type'] == 3)) { //2 - stream
                    $stream->attributes = array(
                        'playlist_id' => $model->id,
                        'url' => $_POST['Stream']['url'],
                    );
                    $stream->save();
                    $this->redirect(array('view','id'=>$model->id));
                }

                $this->redirect(array('update','id'=>$model->id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'stream'=>$stream
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
        $author = $model->author;
        $stream = new Stream();

        if (isset($_POST['Playlists'])) {
            $playlists = $_POST['Playlists'];
            if (isset($_POST['Stream']['url']) &&
                    ($playlists['type'] == 3)) { //2 - stream

                $exitstStream = Stream::model()->findByAttributes(
                    array('playlist_id' => $id)
                );

                if(!empty($exitstStream)) {
                    $stream = $exitstStream;
                }

                $stream->attributes = array(
                    'playlist_id' => $id,
                    'url' => $_POST['Stream']['url']
                );
                $stream->save();
                $playlists['files'] = '';
            } else {
                $stream->deleteAll(
                     "`playlist_id` = :playlist_id",
                    array('playlist_id' => $id)
                );
            }
            $model->attributes=$playlists;

            if ($author) {
                $model->author = $author;
            }

            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        if (count($model->stream) > 0) {
            $stream = $model->stream[0];
        }

        $this->render('update',array(
            'model'=>$model,
            'stream'=>$stream,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $files = trim($model->files);
        $model->delete();
        if (isset($files) && ($files != '')) {
            $filesArr = explode(",", $files);
            Yii::app()->playlistService->deleteALLFilesFromPlaylist($id, $filesArr);
        }

        PlaylistToPoint::model()->deleteAllByAttributes([
            'id_playlist' => $id,
        ]);

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax'])) {
            $this->redirect(array('playlists/index'),array(
                    'model'=>$model
            ));
        }
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Playlists('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Playlists']))
            $model->attributes=$_GET['Playlists'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Proccessing uploaded file
     */
    public function actionUpload()
    {
        $answ = array();
        $answ['status'] = 'err';

        if (!isset($_POST['data'])) {
            echo json_encode($answ);
            Yii::app()->end();
        }

        $playlistId = intval($_POST['data']['playlistId']);
        $uploadInfo = $_POST['data']['file'];
        $uploadFileUrl = $uploadInfo['url'];
        $uploadFileName = $uploadInfo['name'];
        $siteUrl = $this->CurrUrl();
        $siteDir = $_SERVER["DOCUMENT_ROOT"];
        $uploadFilePath = urldecode(str_replace($siteUrl, $siteDir, $uploadFileUrl));

        $mime = urldecode($uploadInfo['type']);
        $mimeArr = explode("/", $mime);
        $type = $mimeArr[0];

        $userId = Yii::app()->user->id;
        $duration = 0;

        $movedFileInfo = Yii::app()->spool->putUploadedFile($type, $uploadFilePath, $uploadFileName);

        if (($type === "audio") || ($type == "video")) {
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($movedFileInfo['path']);
            unset($getID3);

            $duration = $fileInfo['playtime_seconds'];
        } else if($type == "image") {
            $duration = 10;
        }

        $fileInstance = new File();
        $fileInstance->attributes = [
          'name' => $movedFileInfo['name'],
          'duration' => $duration,
          'mime' => $mime,
          'path' => $movedFileInfo['path'],
          'link' => $movedFileInfo['link'],
          'visibility' => 0,
          'id_user' => $userId
        ];
        $fileInstance->save();

        $fileToPlaylist = Yii::app()->db->createCommand()
          ->select('MAX(`order`) as `maxOrder`')
          ->from('file_to_playlist ftp')
          ->where('id_playlist=:id', [':id'=>$playlistId])
          ->queryRow();

        $order = isset($fileToPlaylist['maxOrder']) ? $fileToPlaylist['maxOrder'] : 0;

        $fileToPlaylistInstance = new FileToPlaylist;
        $fileToPlaylistInstance->attributes = [
            'id_file' => $fileInstance->id,
            'id_playlist' => $playlistId,
            'order' => $order
        ];
        $fileToPlaylistInstance->save();

        $answ['status'] = 'ok';

        echo json_encode($answ);
        Yii::app()->end();
    }

    private function CurrUrl()
    {
        return sprintf(
                "%s://%s",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME']
        );
    }

    /**
     * Deletes file from playlist and from heap if nessesary
     */
    public function actionDeletefilefrompl()
    {
        $answ["status"] = 'err';
        if (isset($_POST["data"])
            && isset($_POST["data"]["file"])
            && isset($_POST["data"]["plid"])
            && ($_POST["data"]["file"] != '')
            && ($_POST["data"]["plid"] != '')
        ) {
            $fileId = $_POST["data"]["file"];
            $playlistId = $_POST["data"]["plid"];

            $execution = $this->DeleteFileFromPlaylist($fileId, $playlistId);

            if ($execution) {
                $answ["status"] = 'ok';
            } else {
                $answ["status"] = 'err';
                $answ["error"] = "Delete query failed. " . $execution . ". " .  $sql;
            }
        }
        else
        {
            $answ["error"] = 'Bad post data. ' . json_encode($_POST);
        }

        echo(json_encode($answ));
    }

    public function DeleteFileFromPlaylist($fileId, $playlistId)
    {
        $model = $this->loadModel($playlistId);

        $files = explode(",", $model->files);
        $fileIdArr[] = $fileId;
        $filesToSave = array_diff($files, $fileIdArr);
        $model->files = implode(",", $filesToSave);
        $model->save();

        //if file visibilyty 0 (only for current pl), delete it
        $connection = Yii::app()->db;
        $connection->active=true;
        $sql = "SELECT `path`, `visibility` FROM `file` WHERE `id` = ".$fileId.";";
        $command = $connection->createCommand($sql);
        $dataReader=$command->query();
        $visibility = 0;
        $path = '';
        if(($row=$dataReader->read())!==false)
        {
            $visibility = $row['visibility'];
            $path = $row['path'];
        }

        $execution = false;
        if($visibility == 0)
        {
            $sql = "DELETE FROM `file` WHERE " .
                    "`id` = " . $fileId . ";";
            $command = Yii::app()->db->createCommand($sql);
            $execution = $command->execute();

            try
            {
                unlink($path);
            }
            catch (Exception $e)
            {
                error_log("Unlink failed. Exception - " . json_encode($e).
                "Path - " . $path
                );
            }
        }
        else
        {
            $execution = true;
        }
        $connection->active=false;

        return $execution;
    }

    public function actionAddfilefromheap()
    {
        $answ = array();
        $answ['status'] = 'err';
        if(isset($_POST['id']) && isset($_POST['playlistId']))
        {
            $playlistId = $_POST['playlistId'];
            $heapItemId = $_POST['id'];

            $fileToPlaylist = Yii::app()->db->createCommand()
              ->select('MAX(`order`) as `maxOrder`')
              ->from('file_to_playlist ftp')
              ->where('id_playlist=:id', [':id'=>$playlistId])
              ->queryRow();

            $order = isset($fileToPlaylist['maxOrder']) ? $fileToPlaylist['maxOrder'] : 0;

            $fileToPlaylistInstance = new FileToPlaylist;
            $fileToPlaylistInstance->attributes = [
                'id_file' => $heapItemId,
                'id_playlist' => $playlistId,
                'order' => $order
            ];

            if ($fileToPlaylistInstance->save()) {
                $answ['status'] = 'ok';
            } else if($inserted === false) {
                $answ['status'] = 'err';
                $answ['error'] = 'Cant insert row to db: ' . $insertedStatus['query'] .
                ". Page PlaylistController.php";
                error_log($answ['error']);
            }
        }
        echo(json_encode($answ));
    }

    public function actionSetFileOrder()
    {
        $answ = [];
        $answ['status'] = 'err';
        if(isset($_POST['files']) && isset($_POST['playlistId']))
        {
            $playlistId = $_POST['playlistId'];
            $files = $_POST['files'];

            $model = $this->loadModel($playlistId);
            $model->files = implode(',', $files);

            if($model->validate()){
                $model->save();
                if($model->save())
                {
                    $answ['status'] = 'ok';
                }
            }
            else
            {
                error_log(json_encode(CHtml::errorSummary($model)));
            }

        }
        echo(json_encode($answ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Playlists the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Playlists::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Playlists $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='playlists-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-ui-1.10.4.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery.datetimepicker.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/vendor/jquery.ui.widget.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/jquery.fileupload.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/jquery.iframe-transport.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/jPlayer/jquery.jplayer.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/jsTree/jstree.min.js');
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/menuDecorator.js' );

            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/playlists/playlist.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/playlists/playlistOrder.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/playlists/playlistFileUpload.js' );
            $cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/playlists/playlistHeapAndPreview.js' );

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap-switch.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fileupload.bootstrap.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fileupload.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jPlayerSkin/blue.monday/jplayer.blue.monday.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jTreeThemes/default/style.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/custom-theme/jquery-ui-1.10.4.custom.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.datetimepicker.css');

            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/playlists.css');

            return true;
        }
        return false;
    }
}
