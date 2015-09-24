<?php

Yii::import('application.vendor.*');
require_once('getid3/getid3.php');

class AdminController extends Controller
{

    public $layout='//layouts/column1';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {    	
    		return array(
    				array('allow',
    						'actions'=>array('index','view','getfoldercontent'),
    						//'expression'=>"{$allowView} == true",
    						'users'=>array('@'),
    						'roles'=>array('heapViewUser'/*, 'heapUser', 'moderator', 'admin'*/),
    				),
    				array('allow',
    						'actions'=>array('uploadfile', 'createnewfolder', 'rename', 'move'),
    						//'expression'=>"{$allowCreate} == true",
    						'users'=>array('@'),
    						'roles'=>array('heapEditUser'/*, 'heapUser', 'moderator', 'admin'*/),
    				),
    				array('allow',
    						'actions'=>array('delete', 'dropAllContent'),
    						//'expression'=>"{$allowDelete} == true",
    						'users'=>array('@'),
    						'roles'=>array('heapUser'/*, 'moderator', 'admin'*/),
    				),
    				array('deny',  // deny all users
    						'users'=>array('*'),
    				),
    		);
    }
    
    public function actionIndex(){
    	$this->render("index");
    }
    
   public function actionGetfoldercontent() {
    	if(isset($_GET['id']) && isset($_GET['type']))
    	{
    		$connection = Yii::app()->db;
    		$connection->active=true;
    		$userName = Yii::app()->user->name;
    		$type = $_GET['type'];
    		
	    	$folderid = $_GET['id'];
	    	$folderName = "Root node";
	    	if($folderid == '#')
	    	{
	    		$folderid = 0;
	    		
	    		if($type == 'treeGeneral')
	    		{
	    			$folderName = "General content";
	    		}
	    		else if($type == 'treePrivate')
	    		{
	    			$folderName = "Private content";
	    		}
	    	}
	    	else
	    	{
	    		$sql = "SELECT `name` FROM `folder` WHERE `id` = " . $folderid . ";";
	    		$command = $connection->createCommand($sql);
	    		$dataReader=$command->query();
	    		
	    		if(($row=$dataReader->read())!==false)
	    		{
	    			$folderName = $row['name'];
	    		}
	    	}
	    	
	    	$tree = array();
	    	
	    	if($type == 'treeGeneral')
	    	{
	    		$adminName = 'admin';
		    	$sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `author` = '".$adminName."';";
		    	$command = $connection->createCommand($sql);
		    	$dataReader=$command->query();
		    	$relatedNodes = array();
		    	$d = array();
	
		    	while(($row=$dataReader->read())!==false)
		    	{
		    		$d[] = array(
		    				'id' => intval($row['id']),
		    				'text' => $row['name'],
		    				//'type' => 'file',
		    				'type' => 'folder',
		    				'parent' => intval($row['path']),
		    		);
		    	}
		    		    	
		    	$sql = "SELECT `requester`, `target` FROM `relations` WHERE `type` = '".FILE_TO_FOLDER."' " .
		    		"AND `allowedBy` = '".$adminName."';";
		    	$command = $connection->createCommand($sql);
		    	$dataReader=$command->query();
		    	
		    	while(($row=$dataReader->read())!==false)
		    	{
		    		$sql = "SELECT `name` FROM `file` WHERE `id` = ".$row['requester'].";";
		    		$command = $connection->createCommand($sql);
		    		$dataReader1=$command->query();
		    		
		    		if(($row1=$dataReader1->read())!==false)
		    		{
		    			$name = substr($row1['name'], 13, strlen($row1['name']) - 13);
			    		$d[] = array(
			    				'id' => intval($row['requester']),
			    				'text' => $name,
			    				'type' => 'file',
			    				//'type' => 'folder',
			    				'parent' => intval($row['target']),
			    		);
		    		}
		    	}
		    	
		    	/*$d = array(
				  array('id' => 5273, 'parent' => 0,    'name' => 'John Doe'),  
				  array('id' => 6032, 'parent' => 5273, 'name' => 'Sally Smith'),
				  array('id' => 6034, 'parent' => 6032, 'name' => 'Mike Jones'),
				  array('id' => 6035, 'parent' => 6034, 'name' => 'Jason Williams'),
				  array('id' => 6036, 'parent' => 5273, 'name' => 'Sara Johnson'),
				  array('id' => 6037, 'parent' => 5273, 'name' => 'Dave Wilson'),
				  array('id' => 6038, 'parent' => 6037, 'name' => 'Amy Martin'),
				);
		    	error_log(json_encode($d));*/
		    	
	    		if(count($d) > 0)
		    	{
		    		$relatedNodes = $this->makeRecursive($d);
		    	}
		    	else 
		    	{
		    		$relatedNodes = false;
		    	}
		    	/*error_log(json_encode($relatedNodes));
		    	
		    	//we need to remove 'parent' values from nodes because jstree // no we dont:)
		    	$relatedNodes = json_encode($relatedNodes);
		    	$pattern = '/.[parent].[:]i/';
		    	$relatedNodes =  preg_replace($pattern, '', $relatedNodes);
		    	$relatedNodes = json_decode($relatedNodes, true);
		    	
		    	error_log(json_encode($relatedNodes));*/
		    	
		    	$connection->active=false;
		    	$tree[] = array(
		    		"id" => (string)$folderid,
		    		"text" => $folderName,
		    		"state" => array(
		    				"opened" => true
		    		),
		    		'type' => 'folder',
		    		'children' => $relatedNodes
		    	);

	    	}
	    	else if($type == 'treePrivate')
	    	{
	    		$sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `author` = '".$userName."';";
		    	$command = $connection->createCommand($sql);
		    	$dataReader=$command->query();
		    	$relatedNodes = array();
		    	$d = array();
	
		    	while(($row=$dataReader->read())!==false)
		    	{
		    		$d[] = array(
		    				'id' => intval($row['id']),
		    				'text' => $row['name'],
		    				'type' => 'folder',
		    				'parent' => intval($row['path']),
		    		);
		    	}
		    		    	
		    	$sql = "SELECT `requester`, `target` FROM `relations` WHERE `type` = '".FILE_TO_FOLDER."' " .
		    		"AND `allowedBy` = '".$userName."';";
		    	$command = $connection->createCommand($sql);
		    	$dataReader=$command->query();
		    	
		    	while(($row=$dataReader->read())!==false)
		    	{
		    		$sql = "SELECT `name` FROM `file` WHERE `id` = ".$row['requester'].";";
		    		$command = $connection->createCommand($sql);
		    		$dataReader1=$command->query();
		    		
		    		if(($row1=$dataReader1->read())!==false)
		    		{
		    			$name = substr($row1['name'], 13, strlen($row1['name']) - 13);
			    		$d[] = array(
			    				'id' => intval($row['requester']),
			    				'text' => $name,
			    				'type' => 'file',
			    				'parent' => intval($row['target']),
			    		);
		    		}
		    	}
		    	
		    	if(count($d) > 0)
		    	{
		    		$relatedNodes = $this->makeRecursive($d);
		    	}
		    	else 
		    	{
		    		$relatedNodes = false;
		    	}
		    	
		    	$connection->active=false;
		    	$tree[] = array(
		    		"id" => (string)$folderid,
		    		"text" => $folderName,
		    		"state" => array(
		    				"opened" => true,
		    				"selected" => true
		    		),
		    		'type' => 'folder',
		    		'children' => $relatedNodes
		    	);
	    	}
			
	    	//error_log(json_encode($tree));
	    	echo json_encode($tree);
    	}
    	else 
    	{
    		echo false;
    	}
    }
    
    public function actionView() {
    	if(isset($_POST['id']) && isset($_POST['type']))
    	{
    		$folderid = $_POST['id'];
    		$type = $_POST['type'];
    		
    		$connection = Yii::app()->db;
    		$connection->active=true;

    		if($folderid == '#')
    		{
    			$folderid = 0;
    		}
    		
    		$userName = Yii::app()->user->name;
    		$adminName = 'admin';
    
    		if($type == 'treeGeneral')
    		{
	    		$sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `path` = ".$folderid." AND ".
	    			"`author` = '".$adminName."';";
	    		$command = $connection->createCommand($sql);
	    		$dataReader=$command->query();
	    		$relatedNodes = array();
	    		$d = array();
	    
	    		while(($row=$dataReader->read())!==false)
	    		{
	    			$d[] = array(
	    					'id' => intval($row['id']),
	    					'text' => $row['name'],
	    					'type' => 'folder',
	    					'parent' => intval($row['path']),
	    			);
	    		}
	    
	    
	    		$sql = "SELECT `requester`, `target` FROM `relations` WHERE `type` = '".FILE_TO_FOLDER."' AND " . 
	    				"`target` = '".$folderid."' AND ".
	    				"`allowedBy` = '".$adminName."';";
	    		$command = $connection->createCommand($sql);
	    		$dataReader=$command->query();
	    
	    		while(($row=$dataReader->read())!==false)
	    		{
	    			$sql = "SELECT `name`, `mime`, `link` FROM `file` WHERE `id` = ".$row['requester'].";";
	    			$command = $connection->createCommand($sql);
	    			$dataReader1=$command->query();
	    	   
	    			if(($row1=$dataReader1->read())!==false)
	    			{
	    				$name = substr($row1['name'], 13, strlen($row1['name']) - 13);
	    				$d[] = array(
	    						'id' => intval($row['requester']),
	    						'text' => $name,
	    						'type' => 'file',
	    						'mime' => $row1['mime'],
	    						'link' => $row1['link'],
	    						'parent' => intval($row['target']),
	    				);
	    			}
	    		}
	    
	    		$connection->active=false; 
	    		$answ = array();
	    		$answ['status'] = 'ok';
    		}
    		else if($type == 'treePrivate')
	    	{
	    		$sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `path` = ".$folderid." AND ".
	    				"`author` = '".$userName."';";
	    		$command = $connection->createCommand($sql);
	    		$dataReader=$command->query();
	    		$relatedNodes = array();
	    		$d = array();
	    		 
	    		while(($row=$dataReader->read())!==false)
	    		{
	    			$d[] = array(
	    					'id' => intval($row['id']),
	    					'text' => $row['name'],
	    					'type' => 'folder',
	    					'parent' => intval($row['path']),
	    			);
	    		}
	    		 
	    		 
	    		$sql = "SELECT `requester`, `target` FROM `relations` WHERE `type` = '".FILE_TO_FOLDER."' AND " .
	    				"`target` = '".$folderid."' AND ".
	    				"`allowedBy` = '".$userName."';";
	    		$command = $connection->createCommand($sql);
	    		$dataReader=$command->query();
	    		 
	    		while(($row=$dataReader->read())!==false)
	    		{
	    			$sql = "SELECT `name`, `mime`, `link` FROM `file` WHERE `id` = ".$row['requester'].";";
	    			$command = $connection->createCommand($sql);
	    			$dataReader1=$command->query();
	    			 
	    			if(($row1=$dataReader1->read())!==false)
	    			{
	    				$name = substr($row1['name'], 13, strlen($row1['name']) - 13);
	    				$d[] = array(
	    						'id' => intval($row['requester']),
	    						'text' => $name,
	    						'type' => 'file',
	    						'mime' => $row1['mime'],
	    						'link' => $row1['link'],
	    						'parent' => intval($row['target']),
	    				);
	    			}
	    		}
	    		 
	    		$connection->active=false;
	    		$answ = array();
	    		$answ['status'] = 'ok';
	    	}
    		
    		if(empty($d))
    		{
    			$answ['data'] = 0;
    		}
    		else
    		{
    			$answ['data'] = $d;
    		}
    		echo json_encode($answ);
    	}
    	else
    	{
    		echo false;
    	}
    }
    
    function makeRecursive($d, $r = 0, $pk = 'parent', $k = 'id', $c = 'children') {
    	$m = array();
    	foreach ($d as $e) {
    		isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
    		isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
    		$m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
    	}
    
    	return $m[$r];//[0]; // remove [0] if there could be more than one root nodes
    }
      
    public function actionCreatenewfolder() {
    	if(isset($_POST['foldername']) && isset($_POST['folderpath']))
    	{
    		$foldername = $_POST['foldername'];
    		$folderpath = $_POST['folderpath'];
    	    		
    		$sql = "SELECT MAX(`id`) FROM `folder` WHERE 1";
    		$connection = Yii::app()->db;
    		$connection->active=true;
    		$command = $connection->createCommand($sql);
    		$dataReader=$command->query();
    		$id = 0;
    		 
    		if(($row=$dataReader->read())!==false)
    		{
    			$id = $row['MAX(`id`)'];
    			$id++;
    		}
    		 
    		$sql = "SELECT MAX(`id`) FROM `file` WHERE 1";
    		$connection = Yii::app()->db;
    		$connection->active=true;
    		$command = $connection->createCommand($sql);
    		$dataReader=$command->query();
    		
    		if(($row=$dataReader->read())!==false)
    		{
    			$MAXid = $row['MAX(`id`)'];
    			$MAXid++;
    			if($MAXid > $id)
    			{
    				$id = $MAXid;
    			}
    		}
    		
    		$userName = Yii::app()->user->name;
    		$sql = "INSERT INTO `folder` (`id`,`name`, `path`, `author`) " .
    			"VALUES (".$id.", '" . $foldername . "', ".$folderpath.", '".$userName."');";
    		$command = Yii::app()->db->createCommand($sql);
    		$command->execute();
    
    		$insertId = Yii::app()->db->getLastInsertID();
    		$answ = array();
    		$answ['status'] = 'ok';
    		$answ['nodeid'] = $insertId;
    		echo json_encode($answ);    		
    	}
    	else
    	{
    		$answ = array();
    		$answ['status'] = 'err';
    		$answ['error'] = 'Incorrect params input. Page AdminController';
    		echo json_encode($answ);   
    	}
    }
    
    public function actionDelete() {
    	
    	if(isset($_POST['type']) && isset($_POST['id']))
    	{
    		$type = $_POST['type'];
    		$id = $_POST['id'];
    		 
    		if($type == 'file')
    		{
				$connection = Yii::app()->db;
				$connection->active=true;
				
				$sql = "SELECT `path` FROM `file` WHERE `id` = ".$id.";";
				$command = $connection->createCommand($sql);
				$dataReader=$command->query();
				
				if(($row=$dataReader->read())!==false)
				{
					unlink($row['path']);
				}
				
				$sql = "DELETE FROM `file` WHERE `id` = " . $id . ";";
				
				$command = $connection->createCommand($sql);
				$command->execute();
				
				$sql = "DELETE FROM `relations` WHERE `type` = '" . FILE_TO_FOLDER . "' " .
						"AND `requester` = ".$id.";";
				
				$command = $connection->createCommand($sql);
				$command->execute();
				
				$connection->active=false;
    		}
    		else if($type == 'folder')
    		{
    			$children = $_POST['children'];
    			
    			$connection = Yii::app()->db;
    			$connection->active=true;
				
				$sql = "DELETE FROM `folder` WHERE `id` = " . $id . ";";

				$command = $connection->createCommand($sql);
				$command->execute();
				
				if($children != 0)
				{
					foreach ($children as $key => $id)
					{
						$sql = "SELECT `id` FROM `folder` WHERE `id` = ".$id.";";
						$connection = Yii::app()->db;
						$connection->active=true;
						$command = $connection->createCommand($sql);
						$dataReader=$command->query();
						
						if(($row=$dataReader->read())!==false)
						{
							$sql = "DELETE FROM `folder` WHERE `id` = " . $id . ";";
							$command = $connection->createCommand($sql);
							$command->execute();
						}	
						else 
						{
							$sql = "DELETE FROM `file` WHERE `id` = " . $id . " AND `visibility` = '1';";
							$command = $connection->createCommand($sql);
							$command->execute();
							
							$sql = "DELETE FROM `relations` WHERE `type` = '" . FILE_TO_FOLDER . "' " .
									"AND `requester` = ".$id.";";
							
							$command = $connection->createCommand($sql);
							$command->execute();
						}				
					}
				}

    			$connection->active=false;
    		}
    		
    		$answ = array();
    		$answ['status'] = 'ok';
    		echo json_encode($answ);
    	}
    	else
    	{
    		$answ = array();
    		$answ['status'] = 'err';
    		$answ['error'] = 'Incorrect params input. Page AdminController';
    		echo json_encode($answ);
    	}
    }
    
    public function actionRename() {
    	if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['name']))
    	{
    		$type = $_POST['type'];
    		$id = $_POST['id'];
    		$name = $_POST['name'];
    		 
    		if($type == 'file')
    		{
    			$connection = Yii::app()->db;
    			$connection->active=true;
    
    			$sql = "SELECT `name` FROM `file` WHERE `id` = '".$id."'";

				$command = $connection->createCommand($sql);
				$dataReader=$command->query();
				$prevName = '';
				if(($row=$dataReader->read())!==false)
				{
					$prevName = $row['name'];
				}
				
				$nameUID = substr($prevName, 0, 13);
				$name = $nameUID . $name;		
    
				$sql = "UPDATE `file` SET `name` = '" . $name . "' WHERE `id` = '".$id."';";
    			$command = $connection->createCommand($sql);
    			$command->execute();
     
    			$connection->active=false;
    		}
    		else if(($type == 'folder') || ($type == 'default'))
    		{    			 
    			$connection = Yii::app()->db;
    			$connection->active=true;
    
    			$sql = "UPDATE `folder` SET `name` = '" . $name . "' WHERE `id` = '".$id."';";
    			$command = $connection->createCommand($sql);
    			$command->execute();

    			$connection->active=false;
    		}
    
    		$answ = array();
    		$answ['status'] = 'ok';
    		echo json_encode($answ);
    	}
    	else
    	{
    		$answ = array();
    		$answ['status'] = 'err';
    		$answ['error'] = 'Incorrect params input. Page AdminController';
    		echo json_encode($answ);
    	}
    }
    
    public function actionMove() {
    	if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['parent']))
    	{
    		$type = $_POST['type'];
    		$id = $_POST['id'];
    		$parent = $_POST['parent'];
    		 
    		if($type == 'file')
    		{
    			$connection = Yii::app()->db;
    			$connection->active=true;
        
    			$sql = "UPDATE `relations` SET `target` = '" . $parent . "' WHERE " .
      					"`type` = '".FILE_TO_FOLDER."' AND ".
    					"`requester` = '".$id."';";
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			 
    			$connection->active=false;
    		}
    		else if(($type == 'folder') || ($type == 'default'))
    		{
    			$connection = Yii::app()->db;
    			$connection->active=true;
    
    			$sql = "UPDATE `folder` SET `path` = '" . $parent . "' WHERE `id` = '".$id."';";
    			$command = $connection->createCommand($sql);
    			$command->execute();
    
    			$connection->active=false;
    		}
    
    		$answ = array();
    		$answ['status'] = 'ok';
    		echo json_encode($answ);
    	}
    	else
    	{
    		$answ = array();
    		$answ['status'] = 'err';
    		$answ['error'] = 'Incorrect params input. Page AdminController';
    		echo json_encode($answ);
    	}
    }
    
    /**
     * Proccessing uploaded file
     */
    public function actionUploadfile()
    {
    	$answ = array();
    	$answ['status'] = 'err';
    	if(isset($_POST['data']))
    	{
    		$folderId = $_POST['data']['folderId'];
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
    			$insertedStatus1 = $this->InsertFileFolderRelation($fileId, $folderId);
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
    			$insertedStatus1 = $this->InsertFileFolderRelation($fileId, $folderId);
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
    			$insertedStatus1 = $this->InsertFileFolderRelation($fileId, $folderId);
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
    			$insertedStatus1 = $this->InsertFileFolderRelation($fileId, $folderId);
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
    			". Page AdminController.php";
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
    
    	$sql = "SELECT MAX(`id`) FROM `folder` WHERE 1";
    	$connection = Yii::app()->db;
    	$connection->active=true;
    	$command = $connection->createCommand($sql);
    	$dataReader=$command->query();
    	$id = 0;
    	
    	if(($row=$dataReader->read())!==false)
    	{
    		$id = $row['MAX(`id`)'];
    		$id++;
    	}
    	
    	$sql = "SELECT MAX(`id`) FROM `file` WHERE 1";
    	$connection = Yii::app()->db;
    	$connection->active=true;
    	$command = $connection->createCommand($sql);
    	$dataReader=$command->query();
    	 
    	if(($row=$dataReader->read())!==false)
    	{
    		$MAXid = $row['MAX(`id`)'];
    		$MAXid++;
    		if($MAXid > $id)
    		{
    			$id = $MAXid;
    		}
    	}
    			
    	$visibility = 1; //0 - means not to show in heap (only for playlist)
    	$execution = array();
    	$sql = "INSERT INTO `file` (`id`, `name`, `duration`, `mime`, `path`, `link`, `visibility`, `author`) " .
    			"VALUES (".$id.", '" . $fileName . "', '" . $duration . "', " .
    			"'" . $fileMime . "', '" . $filePath . "', '" . $fileLink . "', " .
    			"" . $visibility . ", '" . $author . "');";
    	$execution['query'] = $sql;

    
    	$command = Yii::app()->db->createCommand($sql);
    	$execution['status'] = $command->execute();
    	$execution['id'] = Yii::app()->db->getLastInsertID();
    	$connection->active=false;
    	return $execution;
    }
    
    private function InsertFileFolderRelation($extFileId, $extFolderId)
    {
    	$fileId = $extFileId;
    	$folderId = $extFolderId;
    
    	$execution = array();
    	$sql = "INSERT INTO `relations` (`type`, `requester`, `target`, `allowedBy`) " .
    			"VALUES ('" . FILE_TO_FOLDER . "', ".$fileId.", ".$folderId.", '".Yii::app()->user->name."');";
    	
    	$execution['query'] = $sql;
    	$connection = Yii::app()->db;
    	$connection->active=true;
    	$command = Yii::app()->db->createCommand($sql);
    	$execution['status'] = $command->execute();
    	$connection->active=false;
    	return $execution;
    }
    
    public function actionDropAllContent()
    {
    	if(isset($_GET['pass']))
    	{
    		$pass = $_GET['pass'];
    		if($pass = '124578QwertyDropAllContent'){
    			$connection = Yii::app()->db;
    			$connection->active=true;    			
    			
    			$sql = "DELETE FROM `file` WHERE 1;";
    			
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			
    			$sql = "DELETE FROM `relations` WHERE 1;";
    			
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			
    			$sql = "DELETE FROM `folder` WHERE 1;";
    			 
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			
    			$sql = "DELETE FROM `user` WHERE 1;";
    			 
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			
    			$sql = "DELETE FROM `point` WHERE 1;";
    			
    			$command = $connection->createCommand($sql);
    			$command->execute();
    			
    			$connection->active=false;
    			
    			printf("Dropped");
    		}
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
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/vendor/jquery.ui.widget.js' );
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/jquery.fileupload.js' );
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/fileuploader/jquery.iframe-transport.js' );
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/jsTree/jstree.min.js');
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/jPlayer/jquery.jplayer.min.js' );
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
    			
    		$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/contentHeap.js' );
    		
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fileupload.bootstrap.css');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap.css.map');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fileupload.css');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jTreeThemes/default/style.min.css');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jPlayerSkin/blue.monday/jplayer.blue.monday.css');
    		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/heap.css');
    			
    		return true;
    	}
    	return false;
    }

    /*public function actionUsers(){

        $users_grid = new CActiveDataProvider('User', array(
            'pagination'=>array(
                'pageSize'=>10
            )
        ));

        $this->render("users", array(
            'users_grid' => $users_grid
        ));

    }*/

    /*public function actionUser($id){

        if (is_numeric($id))
            $model = User::model()->findByPk($id);
        elseif ($id == 'add')
            $model = new User;

        if ($model === null) throw new CHttpException(404,'Query error');

        $this->render("user", array(
            'model' => $model
        ));

    }*/

    /*public function actionContent(){

        $file = new CActiveDataProvider('File', array(
            'pagination'=>array(
                'pageSize'=>10
            )
        ));

        $this->render("content", array(
            'files' => $file
        ));

    }*/

    /*public function actionContent_grid()
    {
        //if(!Yii::app()->request->isAjaxRequest) throw new CHttpException('Url should be requested via ajax only');
        $model = new CActiveDataProvider('File', array(
            //'criteria' => array(
            //    'condition' => 'id_camp=0 AND id_net=0'
            //),
            'pagination'=>array(
                'pageSize'=>2
            )
        ));
        //if(isset($_GET['Product']))
            //$model->attributes=$_GET['Product'];

        $this->renderPartial('_grid',array(
            'files'=>$model,
        ));
    }*/

    /*public function actionUpload()
    {

            $tempFolder="../spool/bg_special";//Yii::getPathOfAlias('webroot').'/upload/';

            Yii::import("ext.EFineUploader.qqFileUploader");

            $uploader = new qqFileUploader();
            $uploader->allowedExtensions = array('zip','jpg','jpeg','mov','mkv','avi','mp4','mp3');
            $uploader->sizeLimit = 200 * 1024 * 1024;//maximum file size in bytes
            $uploader->chunksFolder = $tempFolder.'chunks';

            $result = $uploader->handleUpload($tempFolder);
            $result['filename'] = $uploader->getUploadName();
            $result['basename'] = $uploader->getName();
            $result['type'] = $uploader->getUploadType();
            $result['folder'] = $tempFolder;

            $uploadedFile=$tempFolder.'/'.$result['filename'];

            require_once(Yii::app()->basePath . '/vendor/getid3/getid3.php');
            $getID3 = new getID3;
            $file = $getID3->analyze($uploadedFile);
            $playtime_string = (isset($file['playtime_string'])) ? $file['playtime_string'] : '';

            $fileTypes = array(
                'video'=>array('mov','mkv','mp4','avi'),
                'audio'=>array('mp3'),
                'image'=>array('jpg','jpeg','gif','png')
            );

            if (isset($result['success']) && $result['success'] == 1){
                $extension = array_reverse(explode('.', $result['filename']));
                $extension = $extension[0];
                $file = new File;
                $file->name = $result['basename'];
                $file->mime = $result['type'];
                $file->extension = $extension;
                $fileLink = 'bg_special/'.$result['filename'];
                $file->duration = $playtime_string;

                symlink(realpath(Yii::app()->basePath."/../../spool/").'/'.$fileLink, Yii::app()->basePath."/../../spool/preview/".$result['filename']);

                $file->link = $result['filename'];

                if (in_array($extension, $fileTypes['video'])){
                    $file->type = 'v';
                    $dur = exec("ffmpeg -i ".$uploadedFile." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
                    //$file->duration = $dur;
                }
                elseif (in_array($extension, $fileTypes['audio'])){
                    $file->type = 'a';
                }
                elseif (in_array($extension, $fileTypes['image'])){
                    $file->type = 'i';
                }
                else {
                    $file->type = 'f';
                }

                $file->save(false);
            }

            header("Content-Type: text/plain");
            $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
            echo $result;

        Yii::app()->end();
    }*/


}
