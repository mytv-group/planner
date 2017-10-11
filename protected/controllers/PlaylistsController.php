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
                'allow',
                'actions'=>['index', 'view'],
                'users'=>['@'],
                'roles'=>['playlistViewUser'],
            ],
            [
                'allow',
                'actions'=>['create', 'update', 'upload', 'addFileFromHeap', 'setFileOrder'],
                'users'=>['@'],
                'roles'=>['playlistEditUser'],
            ],
            [
                'allow',
                'actions'=>['delete', 'removeFile'],
                'users'=>['@'],
                'roles'=>['playlistUser'],
            ],
            [
                'deny',
                'users'=>['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $stream = new Stream();

        if ($model->stream) {
            $stream = $model->stream;
        }

        $this->render('view',[
            'model' => $model,
            'stream' => $stream,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Playlists();
        $model->type = 1; // for radioButtonList default value
        $stream = new Stream();

        if (isset($_POST['Playlists'])
            && ($_POST['Playlists']['type'] != 3)
        ) {
            $model->attributes = array_merge(
                $_POST['Playlists'],
                ['id_user' => Yii::app()->user->id]
            );
            
            if ($model->save()) {
                $this->redirect(['update','id'=>$model->id]);
            }
        }

        if (isset($_POST['Playlists'])
            && ($_POST['Playlists']['type'] == 3)
        ) {
            $model->attributes = array_merge(
                $_POST['Playlists'],
                ['id_user' => Yii::app()->user->id]
            );

            $stream->attributes = [
                'playlist_id' => 0, // stub because $model->id unexist until $model->save()
                'url' => $_POST['Stream']['url'],
            ];

            if ($model->validate() && $stream->validate()) {
                $model->save();

                $stream->attributes = [
                    'playlist_id' => $model->id,
                    'url' => $_POST['Stream']['url'],
                ];

                $stream->save();
                $this->redirect(['update','id'=>$model->id]);
            }
        }

        $this->render('create',[
            'model' => $model,
            'stream' => $stream
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $stream = $model->stream;

        if (empty($stream)) {
            $stream = new Stream;
        }

        if (isset($_POST['Playlists'])) {
            $model->attributes = $_POST['Playlists'];

            if (isset($_POST['Stream']['url'])
                 && ($_POST['Playlists']['type'] == 3)
            ) {

                $stream->attributes = [
                    'playlist_id' => $model->id,
                    'url' => $_POST['Stream']['url']
                ];

                if ($model->validate() && $stream->validate()) {
                    $model->save();
                    $stream->save();
                    $this->redirect(['view','id'=>$model->id]);
                }

            } else {
                if ($model->stream) {
                    $stream->delete();
                }

                if ($model->save()) {
                    $this->redirect(['view','id'=>$model->id]);
                }
            }
        }

        $this->render('update',[
            'model' => $model,
            'stream' => $stream,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        Yii::app()->playlistsRepository->deletePlaylistFiles($id);

        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(['playlists/index'],[
                'model'=>$model
            ]);
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

        $this->render('index',[
            'model'=>$model,
        ]);
    }

    /**
     * Proccessing uploaded file
     */
    public function actionUpload()
    {
        $answ = [];
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
    public function actionRemoveFile()
    {
        $answ["status"] = 'err';
        if (isset($_POST["data"])
            && isset($_POST["data"]["file"])
            && isset($_POST["data"]["plid"])
            && ($_POST["data"]["file"] != '')
            && ($_POST["data"]["plid"] != '')
        ) {
            $fileId = intval($_POST["data"]["file"]);
            $playlistId = intval($_POST["data"]["plid"]);

            $model = $this->loadModel($playlistId);
            $filesToPlaylist = $model->filesToPlaylist;

            foreach ($filesToPlaylist as $fileToPlaylist) {
                if (!isset($fileToPlaylist->file)) {
                    $fileToPlaylist->delete();
                }

                $file = $fileToPlaylist->file;

                if ((intval($file->id) === $fileId) && (intval($file->visibility) === 0)) {
                    $file->delete();
                    $fileToPlaylist->delete();

                    if (file_exists($file->path)) {
                        try {
                            unlink($file->path);
                        } catch (Exception $e) {
                            throw new CHttpException(500,'Internal Server Error');
                        }
                    }
                } else if ((intval($file->id) === $fileId) && (intval($file->visibility) === 1)) {
                    $fileToPlaylist->delete();
                }
            }
            $answ["status"] = 'ok';
        }
        else
        {
            $answ["error"] = 'Bad post data. ' . json_encode($_POST);
        }

        echo(json_encode($answ));
    }

    public function actionAddFileFromHeap()
    {
        $answ = [];
        $answ['status'] = 'err';
        if (isset($_POST['id']) && isset($_POST['playlistId'])) {
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
                'order' => ++$order
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
        echo (json_encode($answ));
    }

    public function actionSetFileOrder()
    {
        if (!isset($_POST['files']) || !isset($_POST['playlistId'])) {
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }

        $playlistId = $_POST['playlistId'];
        $files = $_POST['files'];

        $criteria = new CDbCriteria;
        $criteria->addCondition("id_playlist=".$playlistId);
        $criteria->addInCondition('id_file', $files);
        FileToPlaylist::model()->deleteAll($criteria);

        for ($ii = 0; $ii < count($files); $ii++) {
            $fileToPlaylist = new FileToPlaylist;
            $fileToPlaylist->attributes = [
                'id_file' => $files[$ii],
                'id_playlist' => $playlistId,
                'order' => $ii
            ];

            if ($fileToPlaylist->validate()) {
                 $fileToPlaylist->save();
            } else {
                echo json_encode(CHtml::errorSummary($model));
            }
        }

        echo json_encode(['status' => 'ok']);
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $assets = Yii::app()->assets;

        $assets->registerPackage('bootstrap-switch');
        $assets->registerPackage('datetimepicker');
        $assets->registerPackage('fileuploader');
        $assets->registerPackage('j-player');
        $assets->registerPackage('js-tree');

        $assets->register('/js/pages/playlists/order.js' );
        $assets->register('/js/pages/playlists/file-upload.js' );
        $assets->register('/js/pages/playlists/heap-and-preview.js' );

        $assets->register('/css/common/playlists-list.css');

        return true;
    }
}
