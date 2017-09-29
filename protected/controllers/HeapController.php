<?php

Yii::import('application.vendor.*');
require_once('getid3/getid3.php');

class HeapController extends Controller
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
                'actions'=>array('index','view','getFolderContent'),
                'users'=>array('@'),
                'roles'=>array('heapViewUser'),
            ),
            array('allow',
                'actions'=>array('upload', 'createNewFolder', 'rename', 'move'),
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

   public function actionGetFolderContent()
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

        $items = Yii::app()->heap->getHeapContent($author);
        $content = [];

        foreach ($items as $item) {
            if ($item['type'] === 'folder') {
                $item['id'] *= -1;
            }

            $item['parent'] *= -1;

            $content[] = $item;
        }

        echo json_encode([[
            "id" => $folderId,
            "text" => $folderName,
            "state" => array(
                "opened" => true
            ),
            'type' => 'folder',
            'children' => (count($content) > 0)
                ? Yii::app()->heap->buildTree($content)
                : false
        ]]);

        Yii::app()->end();
    }

    public function actionView()
    {
        if (!isset($_POST['id']) || !isset($_POST['type'])) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. $_POST: ".json_encode($_GET) . ".";
            echo(json_encode($answ));
            exit;
        }

        $folderId = intval($_POST['id']);
        $type = $_POST['type'];

        if ($folderId == '#') {
            $folderId = 0;
        }

        $author = 'admin';
        if ($type == 'treePrivate') {
            $author = Yii::app()->user->username;
        }

        $folderId *= -1;
        $items = Yii::app()->heap->getFolderContent($folderId, $author);

        echo json_encode([
            'status' =>'ok',
            'data' => empty($items) ? 0 : $items
        ]);
        Yii::app()->end();
    }

    public function actionCreateNewFolder()
    {
        if (!isset($_POST['foldername']) || !isset($_POST['folderpath'])) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. POST: ".json_encode($_POST) . ".";
            echo(json_encode($answ));
            exit;
        }

        $folderName = $_POST['foldername'];
        $folderPath = intval($_POST['folderpath']) * -1;

        $folder = new Folder;
        $folder->attributes = [
            'name' => $folderName,
            'path' => $folderPath,
            'author' => Yii::app()->user->name
        ];

        $folder->save();

        $answ = [
            'status' => 'ok',
            'nodeid' => $folder->id  * -1
        ];
        echo json_encode($answ);
    }

    public function actionDelete()
    {
        if (!isset($_POST['type']) || !isset($_POST['id'])) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. POST: ".json_encode($_POST) . ".";
            echo(json_encode($answ));
            exit;
        }

        $type = $_POST['type'];
        $id = intval($_POST['id']);

        if ($type === 'file') {
            Yii::app()->heap->deleteFile($id, Yii::app()->user);
        } else if($type === 'folder') {
            $id = $id * -1;
            Yii::app()->heap->deleteFolder($id, Yii::app()->user);
        }

        echo json_encode(['status' => 'ok']);
    }

    public function actionRename()
    {
        if (!isset($_POST['type'])
            || !isset($_POST['id'])
            || !isset($_POST['name'])
        ) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. POST: ".json_encode($_POST) . ".";
            echo(json_encode($answ));
            exit;
        }

        $type = $_POST['type'];
        $id = intval($_POST['id']);
        $name = $_POST['name'];

        if ($type == 'file') {
            $prevName = uniqid();

            if ($file = File::model()->findByPk( $id )) {
                $prevName = $file->name;
            }

            $nameUID = substr($prevName, 0, 13);
            $name = $nameUID . $name;
            File::model()->updateByPk($id, array(
                'name'=> $name
            ));

        } else if(($type == 'folder') || ($type == 'default')) {
            $id = $id *-1;

            Folder::model()->updateByPk($id, array('name'=>$name));

        }

        echo json_encode(['status' => 'ok']);
    }

    public function actionMove()
    {
        if (!isset($_POST['type'])
            || !isset($_POST['id'])
            || !isset($_POST['parent'])
        ) {
            http_response_code(400);
            header("Status: 400 Bad Request");
            $answ = "Not all nessesary params sent. POST: ".json_encode($_POST) . ".";
            echo(json_encode($answ));
            exit;
        }

        $type = $_POST['type'];
        $id = intval($_POST['id']);
        $parent = $_POST['parent'];
        $parent = $parent * -1;

        if ($type === 'file') {
            $fileToFolder = FileToFolder::model()->findByAttributes([
                'id_file' => $id,
                'id_author' => Yii::app()->user->username
            ]);

            $fileToFolder->id_folder = $parent;
            $fileToFolder->save();
        } else if($type === 'folder') {
            $id = $id * -1;

            $folder = Folder::model()->findByAttributes([
                'id' => $id,
                'author' => Yii::app()->user->username
            ]);

            $folder->path = $parent;
            $folder->save();
        }

        echo json_encode(['status' => 'ok']);
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

        $folderId = intval($_POST['data']['folderId']) * -1;
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
          'visibility' => 1,
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
        iconv("utf-8", "ISO-8859-1//TRANSLIT", $text);

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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::app()->assets->registerPackage('js-tree');
        Yii::app()->assets->registerPackage('j-player');
        Yii::app()->assets->registerPackage('fileuploader');

        return true;
    }
}
