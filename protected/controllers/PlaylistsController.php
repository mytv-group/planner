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
		$model=new Playlists();
		$model->type = 0; // for radioButtonList default value
		$stream = new Stream();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Playlists']))
		{
			$model->attributes=$_POST['Playlists'];
			if($model->save()) {
				if(isset($_POST['Stream']['url']) && 
						($_POST['Playlists']['type'] == 2)) { //2 - stream
					$stream->attributes = array(
						'playlist_id' => $model->id,
						'url' => $_POST['Stream']['url']
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
		$stream = new Stream();
		
		if(isset($_POST['Playlists']))
		{	
			$playlists = $_POST['Playlists'];
			if(isset($_POST['Stream']['url']) && 
					($playlists['type'] == 2)) { //2 - stream
				
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
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		if(count($model->stream) > 0) {
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
		$files = explode(",", $model->files);
		$model->delete();
		if($files != '') {
			$this->DeleteALLFilesFromPlaylist($id, $files);
		}
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {		
			$this->redirect(array('playlists/index'),array(
					'model'=>$model
			));
		}
	}
	
	private function DeleteALLFilesFromPlaylist($playlistId, $files)
	{
		$relatedFiles = $files;
	
		//remove net relations
		$connection = Yii::app()->db;
		$connection->active=true;
	
		foreach ($relatedFiles as $key => $fileId)
		{
			//if file visibilyty 0 (only for current pl), delete it
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
	
			if($visibility == 0)
			{
				$sql = "DELETE FROM `file` WHERE " .
						"`id` = " . $fileId . ";";
				$command = Yii::app()->db->createCommand($sql);
				$execution = $command->execute();
	
				if(file_exists($path)){
					try {
						unlink($path);
					} catch (Exception $e) {
						error_log("Unlink failed. Exception - " . json_encode($e).
						"Path - " . $path
						);
					}
				}
	
			}
		}
		$connection->active=false;
	
		return;
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

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=new Playlists('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Playlists']))
			$model->attributes=$_GET['Playlists'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/
	
	/**
	 * Proccessing uploaded file
	 */
	public function actionUpload()
	{
		$answ = array();
		$answ['status'] = 'err';
	
		if(isset($_POST['data']))
		{
			$playlistId = $_POST['data']['playlistId'];
			$uploadInfo = $_POST['data']['file'];
			$uploadFileUrl = $uploadInfo['url'];
			$uploadFileName = $uploadInfo['name'];
			$siteUrl = $this->CurrUrl();
			$siteDir = $_SERVER["DOCUMENT_ROOT"];
			$uploadFilePath = urldecode(str_replace($siteUrl, $siteDir, $uploadFileUrl));
				
			$mime = urldecode($uploadInfo['type']);
			$mimeArr = explode("/", $mime);
			$type = $mimeArr[0];
			$moved = false;
			$insertedStatus['status'] = false;
			$contentPath = "";
				
			if($type == "audio")
			{
				$pathAppendix = "spool/audio/bg_all";
				$contentPath = $this->PrepareSpoolPath($pathAppendix);
				$uploadFileName = str_replace(" ", "", $uploadFileName);
				$uploadFileName = uniqid() . $this->CyrillicToTransLite($uploadFileName);
				$moved = rename($uploadFilePath, $contentPath . $uploadFileName);
	
				$getID3 = new getID3;
				$audioFileInfo = $getID3->analyze($contentPath . $uploadFileName);
				unset($getID3);
	
				$duration = $audioFileInfo['playtime_seconds'];
	
				$insertedStatus = $this->InsertFile($uploadFileName,
						$contentPath . $uploadFileName, $siteUrl . "/" . $pathAppendix . "/" . $uploadFileName,
						$mime, $duration, Yii::app()->user->name);
				$fileId = $insertedStatus['id'];
				$insertedStatus1 = $this->InsertFilePlaylistRelation($fileId, $playlistId);
			}
			else if($type == "image")
			{
				$pathAppendix = "spool/images/bg_all";
				$contentPath = $this->PrepareSpoolPath($pathAppendix);
				$uploadFileName = str_replace(" ", "", $uploadFileName);
				$uploadFileName = uniqid() . $this->CyrillicToTransLite($uploadFileName);
				$moved = rename($uploadFilePath, $contentPath . $uploadFileName);
	
				$duration = 10;
	
				$insertedStatus = $this->InsertFile($uploadFileName,
						$contentPath . $uploadFileName, $siteUrl . "/" . $pathAppendix . "/" . $uploadFileName,
						$mime, $duration, Yii::app()->user->name);
				$fileId = $insertedStatus['id'];
				$insertedStatus1 = $this->InsertFilePlaylistRelation($fileId, $playlistId);
			}
			else if($type == "video")
			{
				$pathAppendix = "spool/video/bg_all";
				$contentPath = $this->PrepareSpoolPath($pathAppendix);
				$uploadFileName = uniqid() . $this->CyrillicToTransLite($uploadFileName);
				$uploadFileName = str_replace(" ", "", $uploadFileName);
				$moved = rename($uploadFilePath, $contentPath . $uploadFileName);
	
				$getID3 = new getID3;
				$videoFileInfo = $getID3->analyze($contentPath . $uploadFileName);
				unset($getID3);
				$duration = $videoFileInfo['playtime_seconds'];
	
				$insertedStatus = $this->InsertFile($uploadFileName,
						$contentPath . $uploadFileName, $siteUrl . "/" . $pathAppendix . "/" . $uploadFileName,
						$mime, $duration, Yii::app()->user->name);
				$fileId = $insertedStatus['id'];
				$insertedStatus1 = $this->InsertFilePlaylistRelation($fileId, $playlistId);
			}
			else
			{
				$pathAppendix = "spool/other/bg_all";
				$contentPath = $this->PrepareSpoolPath($pathAppendix);
				$uploadFileName = str_replace(" ", "", $uploadFileName);
				$uploadFileName = uniqid() . $this->CyrillicToTransLite($uploadFileName);
				$moved = rename($uploadFilePath, $contentPath . $uploadFileName);
	
				$duration = 0;
	
				$insertedStatus = $this->InsertFile($uploadFileName,
						$contentPath . $uploadFileName, $siteUrl . "/" . $pathAppendix . "/" . $uploadFileName,
						$mime, $duration, Yii::app()->user->name);
				$fileId = $insertedStatus['id'];
				$insertedStatus1 = $this->InsertFilePlaylistRelation($fileId, $playlistId);
			}
				
			$inserted = $insertedStatus['status'];
			if(($moved === true) && ($inserted == true))
			{
				$answ['status'] = 'ok';
			}
			else if(($moved === true)  && ($inserted === false))
			{
				$answ['status'] = 'err';
				$answ['error'] = 'Cant insert row to db: ' . $insertedStatus['query'] .
				". Page PlaylistController.php";
				error_log($answ['error']);
			}
			else if($moved === false)
			{
				if(($type != "audio") && ($type != "image") && ($type != "video"))
					$answ['status'] = 'err';
				$answ['error'] = 'Incorect file mime type. ' . $mime;
				error_log($answ['error']);
			}
		}
		echo(json_encode($answ));
	}
	
	private function CurrUrl()
	{
		return sprintf(
				"%s://%s",
				isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
				$_SERVER['SERVER_NAME']
		);
	}
	
	private function CyrillicToTransLite($textcyr)
	{
		$cyr  = array('Р°','Р±','РІ','Рі','Рґ','e','Рµ','С”','Р¶','Р·','Рё','С–','С—','Р№','Рє','Р»','Рј','РЅ','Рѕ','Рї','СЂ','СЃ','С‚','Сѓ',
				'С„','С…','С†','С‡','С€','С‰','СЉ','СЊ', 'СЋ','СЏ','Рђ','Р‘','Р’','Р“','Р”','Р•','Р–','Р—','Р�','Р™','Рљ','Р›','Рњ','Рќ','Рћ','Рџ','Р ','РЎ','Рў','РЈ',
				'Р¤','РҐ','Р¦','Р§','РЁ','Р©','РЄ','Р¬', 'Р®','РЇ' );
		$lat = array( 'a','b','v','g','d','e','e','e','zh','z','i','i','i','y','k','l','m','n','o','p','r','s','t','u',
				'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
				'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
				'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );
		$translit = str_replace($cyr, $lat, $textcyr);
	
		$unwanted_array = array('Е '=>'S', 'ЕЎ'=>'s', 'ЕЅ'=>'Z', 'Еѕ'=>'z', 'ГЂ'=>'A', 'ГЃ'=>'A', 'Г‚'=>'A', 'Гѓ'=>'A', 'Г„'=>'A', 'Г…'=>'A', 'Г†'=>'A', 'Г‡'=>'C', 'Г€'=>'E', 'Г‰'=>'E',
				'ГЉ'=>'E', 'Г‹'=>'E', 'ГЊ'=>'I', 'ГЌ'=>'I', 'ГЋ'=>'I', 'ГЏ'=>'I', 'Г‘'=>'N', 'Г’'=>'O', 'Г“'=>'O', 'Г”'=>'O', 'Г•'=>'O', 'Г–'=>'O', 'Г�'=>'O', 'Г™'=>'U',
				'Гљ'=>'U', 'Г›'=>'U', 'Гњ'=>'U', 'Гќ'=>'Y', 'Гћ'=>'B', 'Гџ'=>'Ss', 'Г '=>'a', 'ГЎ'=>'a', 'Гў'=>'a', 'ГЈ'=>'a', 'Г¤'=>'a', 'ГҐ'=>'a', 'Г¦'=>'a', 'Г§'=>'c',
				'ГЁ'=>'e', 'Г©'=>'e', 'ГЄ'=>'e', 'Г«'=>'e', 'Г¬'=>'i', 'Г­'=>'i', 'Г®'=>'i', 'ГЇ'=>'i', 'Г°'=>'o', 'Г±'=>'n', 'ГІ'=>'o', 'Гі'=>'o', 'Гґ'=>'o', 'Гµ'=>'o',
				'Г¶'=>'o', 'Гё'=>'o', 'Г№'=>'u', 'Гє'=>'u', 'Г»'=>'u', 'ГЅ'=>'y', 'ГЅ'=>'y', 'Гѕ'=>'b', 'Гї'=>'y' );
		$translit = strtr($translit, $unwanted_array);
	
		$translit = preg_replace('/[^\p{L}\p{N}\s\.]/u', '', $translit);
	
		$cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у',
				'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
				'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я' );
	
		$lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
				'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
				'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
				'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );
	
		$translit = str_replace($cyr, $lat, $translit);
	
		return $translit;
	}
	
	private function PrepareSpoolPath($extPathAppendix)
	{
		$pathAppendix = $extPathAppendix;
		$pathAppendix = explode("/", $pathAppendix);
	
		$contentPath = $_SERVER["DOCUMENT_ROOT"];
	
		foreach($pathAppendix as $folder)
		{
			$contentPath .= "/" . $folder;
			if (!file_exists($contentPath) && !is_dir($contentPath))
			{
				mkdir($contentPath);
			}
		}
	
		$contentPath .= "/";
		return $contentPath;
	}
	
	private function InsertFile($extFileName, $extFilePath, $extFileLink, $extFileMime, $extDuration, $extAuthor)
	{
		$fileName = $extFileName;
		$filePath = $extFilePath;
		$fileLink = $extFileLink;
		$fileMime = $extFileMime;
		$duration = $extDuration;
		$author = $extAuthor;
	
		$visibility = 0; //0 - means not to show in heap (only for playlist)
		$execution = array();
		$sql = "INSERT INTO `file` (`name`, `duration`, `mime`, `path`, `link`, `visibility`, `author`) " .
				"VALUES ('" . $fileName . "', '" . $duration . "', " .
				"'" . $fileMime . "', '" . $filePath . "', '" . $fileLink . "', " .
				"" . $visibility . ", '" . $author . "');";
		$execution['query'] = $sql;
		$connection = Yii::app()->db;
		$connection->active=true;
	
		$command = Yii::app()->db->createCommand($sql);
		$execution['status'] = $command->execute();
		$execution['id'] = Yii::app()->db->getLastInsertID();
		$connection->active=false;
		return $execution;
	}
	
	private function InsertFilePlaylistRelation($extFileId, $extPlaylistId)
	{
		$fileId = $extFileId;
		$playlistId = $extPlaylistId;
	
		$model = $this->loadModel($playlistId);
	
		if($model->files == "")
		{
			$model->files = $fileId;
		}
		else
		{
			$model->files .= "," . $fileId;
		}
	
		if($model->validate()){
			$model->save();
			if($model->save())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			error_log(json_encode(CHtml::errorSummary($model)));
			return false;
		}
	}
	
	/**
	 * Deletes file from playlist and from heap if nessesary
	 */
	public function actionDeletefilefrompl()
	{
		$answ["status"] = 'err';
		if(isset($_POST["data"]) && isset($_POST["data"]["file"]) && isset($_POST["data"]["plid"]) &&
				($_POST["data"]["file"] != '') && ($_POST["data"]["plid"] != ''))
		{
			$fileId = $_POST["data"]["file"];
			$playlistId = $_POST["data"]["plid"];
				
			$execution = $this->DeleteFileFromPlaylist($fileId, $playlistId);
				
			if($execution)
			{
				$answ["status"] = 'ok';
			}
			else
			{
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
				
			$insertedStatus = $this->InsertFilePlaylistRelation($heapItemId, $playlistId);
			$inserted = $insertedStatus;
				
			if($inserted == true)
			{
				$answ['status'] = 'ok';
			}
			else if($inserted === false)
			{
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
			//$cs->registerPackage('jquery');
			//$cs->registerPackage('history');
	
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
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
	
			Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap.css.map');
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
