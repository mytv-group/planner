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
            ['allow',
                'actions'=>['index',
                    'view',
                    'ajaxGetPointScreen',
                    'getScreenshotUrl'
                ],
                'users'=>['@'],
                'roles'=>['pointViewUser'],
            ],
            ['allow',
                'actions'=>[
                    'create',
                    'update',
                    'sendReload'
                ],
                'users'=>['@'],
                'roles'=>['pointEditUser'],
            ],
            ['allow',
                'actions'=>['delete'],
                'users'=>['@'],
                'roles'=>['pointUser'],
            ],
            ['deny',  // deny all users
                'users'=>['*'],
            ],
        ];
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Point('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Point'])) {
            $model->attributes = $_GET['Point'];
        }

        $this->render('index',[
            'model'=>$model,
        ]);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderView($model, 'view');
    }

    public function actionCreate()
    {
        $model = new Point;
        $model->unsetAttributes();

        if(!isset($_POST['Point'])) {
            $this->renderView($model, 'create');
            return;
        }

        $model->attributes = array_merge($_POST['Point'], ['sync' => 0]);
        $model->username = Yii::app()->user->username;

        if($model->save()) {
            $postPoint = $_POST['Point'];
            Yii::app()->pointsRepository->updateRelations([
                'id' => intval($model->id),
                'tvScheduleFrom' => isset($postPoint["tvScheduleFrom"]) ? $postPoint["tvScheduleFrom"] : [],
                'tvScheduleTo' => isset($postPoint["tvScheduleTo"]) ? $postPoint["tvScheduleTo"] : [],
                'showcases' => isset($postPoint["showcases"]) ? $postPoint["showcases"] : [],
                'channels' => isset($postPoint["channels"]) ? $postPoint["channels"] : [],
                'ip' => $model->ip
            ]);

            Yii::app()->spool->prepareFilesForSync(intval($model->id));

            $this->redirect(['point/view','id'=>$model->id]);
        } else {
            $this->renderView($model, 'create');
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

        if(!isset($_POST['Point'])) {
            $this->renderView($model, 'update');
            return;
        }

        $author = $model->username;
        $model->attributes = array_merge($_POST['Point'], ['sync' => 0]);

        $model->content = CUploadedFile::getInstance($model, 'content');
        if ($author) {
            $model->username = $author;
        }

        if ($model->validate() && $model->save()) {
            $postPoint = $_POST['Point'];

            Yii::app()->pointsRepository->updateRelations([
                'id' => intval($model->id),
                'tvScheduleFrom' => isset($postPoint["tvScheduleFrom"]) ? $postPoint["tvScheduleFrom"] : [],
                'tvScheduleTo' => isset($postPoint["tvScheduleTo"]) ? $postPoint["tvScheduleTo"] : [],
                'showcases' => isset($postPoint["showcases"]) ? $postPoint["showcases"] : [],
                'channels' => isset($postPoint["channels"]) ? $postPoint["channels"] : [],
                'ip' => $model->ip
            ]);

            Yii::app()->spool->prepareFilesForSync(intval($model->id));

            $CM = Yii::app()->contentManager;
            if (isset($model->content)
              && isset(pathinfo($model->content->getName())['extension'])
            ) {
                $extension = pathinfo($model->content->getName())['extension'];
                $path = $CM->PrepareSpoolPath("spool/content/" . $model->id);
                $model->content->saveAs($path."/"."content".".".$extension);
            }

            $this->redirect(['point/view','id' => $model->id]);
        } else {
            $this->renderView($model, 'update');
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        Yii::app()->pointsRepository->deleteRelations(intval($id));
        Yii::app()->spool->removeSpoolPath(intval($id));

        if(!isset($_GET['ajax'])) {
            $model = new Point('search');
            $model->unsetAttributes();

            $this->redirect(['point/index'], [
                'model' => $model
            ]);
        }
    }

    private function renderView($model, $view)
    {
        $widgets = [];
        $playlists = [];
        // dont need for view
        if ($view !== 'view') {
            $allPlaylists = Yii::app()->playlistsRepository->getUserPlaylists();
            foreach ($allPlaylists as $playlist) {
                $playlists[$playlist['type']][] = $playlist;
            }

            // stream channel may receive bg playlists
            $playlists[3] = array_merge(
                (isset($playlists[1]) ? $playlists[1] : []),
                (isset($playlists[3]) ? $playlists[3] : [])
            );

            $widgets = Widget::model()->findAll();
        }

        $screens = Screen::model()->findAllByAttributes(
          ['user_id' => Yii::app()->user->id]
        );

        $this->render($view, [
            'model'=>$model,
            'playlists' => $playlists,
            'screens' => $screens,
            'widgets' => $widgets
        ]);
    }

    public function actionAjaxGetPointScreen()
    {
        if (!isset($_POST['pointId'])
          || !isset($_POST['pointIp'])
        ) {
            echo json_encode(['err', '']);
            return;
        }

        $pointId = $_POST['pointId'];
        $pointIp = $_POST['pointIp'];

        if ($pointIp === '0.0.0.0') {
            Yii::app()->spool->removeScreenshots($pointId);
            Yii::app()->pointsRepository->sendRequestForScreen($pointId);
            echo json_encode(['pending']);
            return;
        }

        $res = Yii::app()->pointsRepository->getPointScreen($pointId, $pointIp);

        echo json_encode(['ok', $res]);
    }

    public function actionGetScreenshotUrl($pointId)
    {
        if (!isset($_GET['pointId'])) {
            echo json_encode(0);
            return;
        }

        $url = Yii::app()->spool->getScreenshotUrl($pointId);

        if(empty($url)) {
            echo json_encode(0);
            exit;
        }

        echo json_encode([
            'url' => $url
        ]);
        exit;
    }

    public function actionSendReload($pointId)
    {
        Yii::app()->pointsRepository->sendRequestForReload($pointId);
        echo json_encode(1);
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::app()->assets->registerPackage('bootstrap-switch');
        Yii::app()->assets->registerPackage('datetimepicker');
        Yii::app()->assets->registerPackage('switch');

        Yii::app()->assets->register('/js/common/point-screen.js');

        Yii::app()->assets->register('/css/common/points-list.css');
        Yii::app()->assets->register('/css/common/screen-shot-box.css');


        return true;
    }
}
