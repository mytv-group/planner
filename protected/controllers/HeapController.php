<?php

class HeapController extends Controller
{

    public $layout='//layouts/column1';

    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions'=>['index','view','getFolderContent'],
                'users'=>['@'],
                'roles'=>['heapViewUser'],
            ],
            ['allow',
                'actions'=>['upload', 'createNewFolder', 'rename', 'move'],
                'users'=>['@'],
                'roles'=>['heapEditUser'],
            ],
            ['allow',
                'actions'=>['delete', 'dropAllContent'],
                'users'=>['@'],
                'roles'=>['heapUser'],
            ],
            ['deny',
                'users'=>['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->render('index');
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
            "state" => [
                "opened" => true
            ],
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
        $items = Yii::app()->folderRepository->getFolderContent($folderId, $author);

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
            Yii::app()->fileRepository->deleteFile($id, Yii::app()->user);
        } else if($type === 'folder') {
            $id = $id * -1;
            Yii::app()->folderRepository->deleteFolder($id, Yii::app()->user);
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

    public function actionUpload()
    {
        $tags = Yii::app()->request->getParam('tags') ?? '';
        $folderId = Yii::app()->request->getParam('folderId') ?? 0;

        $tags = preg_replace("/[^ \w]+/", "", $tags);
        $tags = explode(' ', $tags);
        $tags = array_filter($tags, function($value) { return $value !== ''; });

        $uid = uniqid();
        $tempFolder = Yii::app()->spool->getSpoolPath()
            . DIRECTORY_SEPARATOR
            . $uid;
        $chunksFolder = $tempFolder
            . DIRECTORY_SEPARATOR
            . 'chunks';

        mkdir($tempFolder, 0777, TRUE);
        mkdir($tempFolder.DIRECTORY_SEPARATOR.'chunks', 0777, TRUE);

        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $uploader = new qqFileUploader(
            Yii::app()->params['fileUploadAllowedExtensions'],
            Yii::app()->params['fileUploadMaxSize'] //maximum file size in bytes
        );

        $uploader->chunksFolder = $chunksFolder;
        $result = $uploader->handleUpload($tempFolder);

        Yii::app()->spool->deleteDir($tempFolder);

        /*extention works in that way - appends chunk folder name to file name*/
        $uploadFilePath = Yii::app()->spool->getSpoolPath()
            . DIRECTORY_SEPARATOR
            .$uid . $result['filename'];

        Yii::import('getid3.getid3', true);
        $getID3 = new getID3();

        $fileInfo = $getID3->analyze($uploadFilePath);
        unset($getID3);

        $mimeArr = explode("/", $fileInfo['mime_type']);
        $type = $mimeArr[0];

        $filesize = filesize($uploadFilePath);

        $movedFileInfo = Yii::app()->spool->putUploadedFile($type, $uploadFilePath);

        $fileInstance = new File();
        $fileInstance->attributes = [
          'name' => $result['filename'],
          'duration' => $fileInfo['playtime_seconds'],
          'mime' => $fileInfo['mime_type'],
          'size' => $filesize,
          'path' => $movedFileInfo['path'],
          'link' => $movedFileInfo['link'],
          'visibility' => 1,
          'id_user' => Yii::app()->user->id
        ];
        $fileInstance->save();

        $fileToFolderInstance = new FileToFolder;
        $fileToFolderInstance->attributes = [
            'id_file' => $fileInstance->id,
            'id_folder' => $folderId,
            'id_author' => Yii::app()->user->username
        ];
        $fileToFolderInstance->save();

        foreach ($tags as $tagName) {
            $tag = Tag::model()->findByAttributes([
                'name' => $tagName
            ]);

            if (!isset($tag)) {
                $tag = new Tag;
                $tag->attributes = [
                    'name' => $tagName,
                    'id_user' => Yii::app()->user->id
                ];
                $tag->save();
            }

            $tagToFile = new TagToFile;
            $tagToFile->attributes = [
                'id_tag' => $tag->id,
                'id_file' => $fileInstance->id,
            ];
            $tagToFile->save();
        }

        header("Content-Type: text/plain");
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        echo $result;
        Yii::app()->end();
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::app()->assets->registerPackage('js-tree');
        Yii::app()->assets->registerPackage('j-player');

        return true;
    }
}
