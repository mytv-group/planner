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
                'users'=>array('@'),
                'roles'=>array('heapViewUser'),
            ),
            array('allow',
                'actions'=>array('upload', 'createnewfolder', 'rename', 'move'),
                'users'=>array('@'),
                'roles'=>array('heapEditUser'),
            ),
            array('allow',
                'actions'=>array('delete', 'dropAllContent'),
                'users'=>array('@'),
                'roles'=>array('heapUser'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->render("index");
    }

   public function actionGetfoldercontent()
   {
        if (!isset($_GET['id']) || !isset($_GET['type'])) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. Get: ".json_encode($_GET) . ".";
            echo(json_encode($answ));
            exit;
        }

        $userId = Yii::app()->user->id;
        $type = $_GET['type'];

        $folderId = 0;

        if ($_GET['id'] !== '#') {
            $folderId = intval($_GET['id']);
        }

        if ($type == 'treeGeneral') {
            $folderName = "General content";
        } else if($type == 'treePrivate') {
            $folderName = "Private content";
        }

        $tree = [];

        $author = 'admin';
        if ($type == 'treePrivate') {
            $author = Yii::app()->user->username;
        }

        $folders = Folder::model()->findAllByAttributes(['author' => $author]);

        $d = [];

        foreach ($folders as $folder) {
            $d[] = array(
                    'id' => intval($folder->id),
                    'text' => $folder->name,
                    'type' => 'folder',
                    'parent' => intval($folder->path),
            );

            $files = $folder->files;
            foreach ($files as $file) {
                $name = substr($file->name, 13, strlen($file->name) - 13);
                $d[] = array(
                        'id' => intval($file->id),
                        'text' => $name,
                        'type' => 'file',
                        'parent' => intval($folder->id),
                );
            }
        }

        if (count($d) > 0) {
            $relatedNodes = $this->makeRecursive($d);
        } else {
            $relatedNodes = false;
        }

        $tree[] = array(
            "id" => (string)$folderId,
            "text" => $folderName,
            "state" => array(
                "opened" => true
            ),
            'type' => 'folder',
            'children' => $relatedNodes
        );

        echo json_encode($tree);
    }

    public function actionView()
    {
        if(isset($_POST['id']) && isset($_POST['type'])) {
            $folderid = $_POST['id'];
            $type = $_POST['type'];

            $connection = Yii::app()->db;
            $connection->active=true;

            if($folderid == '#') {
                $folderid = 0;
            }

            $userName = Yii::app()->user->name;
            $adminName = 'admin';

            if ($type == 'treeGeneral') {
                $sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `path` = ".$folderid." AND ".
                    "`author` = '".$adminName."';";
                $command = $connection->createCommand($sql);
                $dataReader=$command->query();
                $relatedNodes = array();
                $d = array();

                while(($row=$dataReader->read())!==false) {
                    $d[] = array(
                            'id' => intval($row['id']),
                            'text' => $row['name'],
                            'type' => 'folder',
                            'parent' => intval($row['path']),
                    );
                }

                $sql = "SELECT `id_file`, `id_folder` FROM `file_to_folder` WHERE " .
                        "`id_folder` = '".$folderid."' AND ".
                        "`id_author` = '".$adminName."';";
                $command = $connection->createCommand($sql);
                $dataReader=$command->query();

                while(($row = $dataReader->read()) !== false) {
                    $sql = "SELECT `name`, `mime`, `link` FROM `file` WHERE `id` = ".$row['id_file'].";";
                    $command = $connection->createCommand($sql);
                    $dataReader1=$command->query();

                    if(($row1 = $dataReader1->read()) !== false) {
                        $name = substr($row1['name'], 13, strlen($row1['name']) - 13);
                        $d[] = array(
                            'id' => intval($row['id_file']),
                            'text' => $name,
                            'type' => 'file',
                            'mime' => $row1['mime'],
                            'link' => $row1['link'],
                            'parent' => intval($row['id_folder']),
                        );
                    }
                }

                $connection->active=false;
                $answ = array();
                $answ['status'] = 'ok';
            } else if($type == 'treePrivate') {
                $sql = "SELECT `id`, `name`, `path` FROM `folder` WHERE `path` = ".$folderid." AND ".
                        "`author` = '".$userName."';";
                $command = $connection->createCommand($sql);
                $dataReader=$command->query();
                $relatedNodes = array();
                $d = array();

                while(($row = $dataReader->read()) !== false) {
                    $d[] = array(
                        'id' => intval($row['id']),
                        'text' => $row['name'],
                        'type' => 'folder',
                        'parent' => intval($row['path']),
                    );
                }

                $sql = "SELECT `id_file`, `id_folder` FROM `file_to_folder` WHERE " .
                    "`id_folder` = '".$folderid."' AND ".
                    "`id_author` = '".$userName."';";
                $command = $connection->createCommand($sql);
                $dataReader=$command->query();

                while(($row = $dataReader->read()) !== false) {
                    $sql = "SELECT `name`, `mime`, `link` FROM `file` WHERE `id` = ".$row['id_file'].";";
                    $command = $connection->createCommand($sql);
                    $dataReader1=$command->query();

                    if(($row1 = $dataReader1->read()) !== false) {
                        $name = substr($row1['name'], 13, strlen($row1['name']) - 13);
                        $d[] = array(
                            'id' => intval($row['id_file']),
                            'text' => $name,
                            'type' => 'file',
                            'mime' => $row1['mime'],
                            'link' => $row1['link'],
                            'parent' => intval($row['id_folder']),
                        );
                    }
                }

                $connection->active=false;
                $answ = array();
                $answ['status'] = 'ok';
            }

            if(empty($d)) {
                $answ['data'] = 0;
            } else {
                $answ['data'] = $d;
            }
            echo json_encode($answ);
        } else {
            echo false;
        }
    }

    function makeRecursive($d, $r = 0, $pk = 'parent', $k = 'id', $c = 'children')
    {
        $m = array();
        foreach ($d as $e) {
            isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
            isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
            $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
        }

        return $m[$r];//[0]; // remove [0] if there could be more than one root nodes
    }

    public function actionCreatenewfolder() {
        if(isset($_POST['foldername']) && isset($_POST['folderpath'])) {
            $foldername = $_POST['foldername'];
            $folderpath = $_POST['folderpath'];

            $sql = "SELECT MAX(`id`) FROM `folder` WHERE 1";
            $connection = Yii::app()->db;
            $connection->active=true;
            $command = $connection->createCommand($sql);
            $dataReader=$command->query();
            $id = 0;

            if(($row = $dataReader->read()) !== false) {
                $id = $row['MAX(`id`)'];
                $id++;
            }

            $sql = "SELECT MAX(`id`) FROM `file` WHERE 1";
            $connection = Yii::app()->db;
            $connection->active=true;
            $command = $connection->createCommand($sql);
            $dataReader=$command->query();

            if(($row = $dataReader->read())!==false) {
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
        } else {
            $answ = array();
            $answ['status'] = 'err';
            $answ['error'] = 'Incorrect params input. Page AdminController';
            echo json_encode($answ);
        }
    }

    public function actionDelete()
    {
        if (isset($_POST['type']) && isset($_POST['id'])) {
            $type = $_POST['type'];
            $id = $_POST['id'];

            if ($type == 'file') {
                $connection = Yii::app()->db;
                $connection->active=true;

                $sql = "SELECT `path` FROM `file` WHERE `id` = ".$id.";";
                $command = $connection->createCommand($sql);
                $dataReader=$command->query();

                $sql = "DELETE FROM `file` WHERE `id` = " . $id . ";";

                $command = $connection->createCommand($sql);
                $command->execute();

                $sql = "DELETE FROM `file_to_folder` WHERE `id_file` = ".$id.";";

                $command = $connection->createCommand($sql);
                $command->execute();

                $connection->active=false;

                if ((($row=$dataReader->read()) !== false)
                  && (file_exists($row['path']))
                ) {
                    unlink($row['path']);
                }
            } else if($type == 'folder') {
                $children = $_POST['children'];

                $connection = Yii::app()->db;
                $connection->active=true;

                $sql = "DELETE FROM `folder` WHERE `id` = " . $id . ";";

                $command = $connection->createCommand($sql);
                $command->execute();

                if($children != 0) {
                    foreach ($children as $key => $id) {
                        $sql = "SELECT `id` FROM `folder` WHERE `id` = ".$id.";";
                        $connection = Yii::app()->db;
                        $connection->active = true;
                        $command = $connection->createCommand($sql);
                        $dataReader=$command->query();

                        if(($row=$dataReader->read()) !== false) {
                            $sql = "DELETE FROM `folder` WHERE `id` = " . $id . ";";
                            $command = $connection->createCommand($sql);
                            $command->execute();
                        } else {
                            $sql = "DELETE FROM `file` WHERE `id` = " . $id . " AND `visibility` = '1';";
                            $command = $connection->createCommand($sql);
                            $command->execute();

                            $sql = "DELETE FROM `file_to_folder` WHERE `id_file` = ".$id.";";

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
        } else {
            $answ = array();
            $answ['status'] = 'err';
            $answ['error'] = 'Incorrect params input. Page AdminController';
            echo json_encode($answ);
        }
    }

    public function actionRename()
    {
        if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['name'])) {
            $type = $_POST['type'];
            $id = $_POST['id'];
            $name = $_POST['name'];

            if($type == 'file') {
                $connection = Yii::app()->db;
                $connection->active=true;

                $sql = "SELECT `name` FROM `file` WHERE `id` = '".$id."'";

                $command = $connection->createCommand($sql);
                $dataReader=$command->query();
                $prevName = '';

                if(($row=$dataReader->read())!==false) {
                    $prevName = $row['name'];
                }

                $nameUID = substr($prevName, 0, 13);
                $name = $nameUID . $name;

                $sql = "UPDATE `file` SET `name` = '" . $name . "' WHERE `id` = '".$id."';";
                $command = $connection->createCommand($sql);
                $command->execute();

                $connection->active=false;
            } else if(($type == 'folder') || ($type == 'default')) {
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
        } else {
            $answ = array();
            $answ['status'] = 'err';
            $answ['error'] = 'Incorrect params input. Page AdminController';
            echo json_encode($answ);
        }
    }

    public function actionMove()
    {
        if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['parent'])) {
            $type = $_POST['type'];
            $id = $_POST['id'];
            $parent = $_POST['parent'];

            if($type == 'file') {
                $connection = Yii::app()->db;
                $connection->active=true;

                $sql = "UPDATE `file_to_folder` SET `id_folder` = '" . $parent . "' WHERE `id_file` = '".$id."';";
                $command = $connection->createCommand($sql);
                $command->execute();

                $connection->active=false;
            } else if(($type == 'folder') || ($type == 'default')) {
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
    public function actionUpload()
    {
        $answ = array();
        $answ['status'] = 'err';

        if (!isset($_POST['data'])) {
            echo json_encode($answ);
            Yii::app()->end();
        }

        $folderId = intval($_POST['data']['folderId']);
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

        $fileToFolderInstance = new FileToFolder;
        $fileToFolderInstance->attributes = [
            'id_file' => $fileInstance->id,
            'id_folder' => $folderId,
            'id_author' => Yii::app()->user->username
        ];
        $fileToFolderInstance->save();

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

    private function CyrillicToTransLite($textcyr)
    {
        iconv("utf-8", "cp1251//IGNORE", $textcyr);

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
                'ф','х','ц','ч','ш','щ','ъ','ь', 'ы', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
                'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ы', 'Ю','Я' );

        $lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
                'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y', 'u', 'yu' ,'ya','A','B','V','G','D','E','Zh',
                'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
                'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y', 'U', 'Yu' ,'Ya' );

        $translit = str_replace($cyr, $lat, $translit);

        return $translit;
    }

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            $cs = Yii::app()->clientScript;

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
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fileupload.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jTreeThemes/default/style.min.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jPlayerSkin/blue.monday/jplayer.blue.monday.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/pages/heap.css');

            return true;
        }
        return false;
    }
}
