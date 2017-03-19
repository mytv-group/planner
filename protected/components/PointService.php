<?php

Yii::import('ext.EHttpClient.*');

class PointService extends CApplicationComponent
{
    public $user;
    public $tvSchedule;
    public $showcase;
    public $playlistToPoint;
    public $file;
    public $spoolPath;

    private function getUser()
    {
        return $this->user->__invoke();
    }

    private function getTvSchedule()
    {
        return $this->tvSchedule->__invoke();
    }

    private function getShowcase()
    {
        return $this->showcase->__invoke();
    }

    private function getPlaylistToPoint()
    {
        return $this->playlistToPoint->__invoke();
    }

    private function getSpoolPath()
    {
        return $this->spoolPath;
    }

    private function getFile()
    {
        return $this->file->__invoke();
    }

    public function updatePointTVschedule($id, $tvScheduleFrom, $tvScheduleTo)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        $userId = intval($this->getUser()->id);
        $tvScheduleModel = $this->getTvSchedule();
        $tvScheduleModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        if ((count($tvScheduleFrom) > 0)
            && (count($tvScheduleTo) > 0)
        ) {
            $tvScheduleTable = $this->organizeTvArray($tvScheduleFrom, $tvScheduleTo);

            foreach ($tvScheduleTable as $tvScheduleAttributes) {
                $tvSchedule = new $tvScheduleModel();
                $tvSchedule->attributes = array_merge(
                    $tvScheduleAttributes, [
                        'id_point' => intval($id),
                        'id_user' => intval($userId)
                    ]);
                $tvSchedule->save();
            }
        }
    }

    private function organizeTvArray ($fromArray, $toArray)
    {
        $array = [];
        if (count($fromArray) > count($toArray)) {
            $array = $this->check($fromArray, $toArray, 'dt_from', 'dt_to');
        } else {
            $array = $this->check($toArray, $fromArray, 'dt_to', 'dt_from');
        }

        $array2 = [];
        for ($ii = 0; $ii < count($array); $ii++) {
            if(strtotime ($array[$ii]['dt_from']) < strtotime ($array[$ii]['dt_to'])) {
                $array2[] = $array[$ii];
            }
        }

        return $array2;
    }

    private function check ($array1, $array2, $name1, $name2)
    {
        $arr = [];
        for ($ii = 0; $ii < count($array1); $ii++) {
            if (($array1[$ii] !== '')
               && isset($array2[$ii])
               && ($array2[$ii] !== '')
             ) {
                 $arr[] = [
                     $name1 => $array1[$ii],
                     $name2 => $array2[$ii],
                 ];
             }
        }

        return $arr;
    }

    public function updateChannels($id, $channels)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        if (!is_array($channels)) {
            throw new Exception("Incorrect channels passed. Array is required. Passed: "
                . json_encode($channels), 1);
        }

        $playlistToPointModel = $this->getPlaylistToPoint();
        $playlistToPointModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        foreach ($channels as $channelType => $playlists) {
            foreach ($playlists as $playlistId) {
                $playlistToPointInstance = new $playlistToPointModel();
                $playlistToPointInstance->attributes = [
                    'id_point' => intval($id),
                    'id_playlist' => intval($playlistId),
                    'channel_type' => intval($channelType)
                ];
                $playlistToPointInstance->save();
            }
        }
    }

    public function updateShowcases($id, $showcases)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        if (!is_array($showcases)) {
            throw new Exception("Incorrect showcases passed. Array is required. Passed: "
                . json_encode($showcases), 1);
        }

        $showcaseModel = $this->getShowcase();
        $showcaseModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        foreach ($showcases as $windowId => $widgetId) {
            $showcaseInstance = new $showcaseModel();
            $showcaseInstance->attributes = [
                'id_point' => intval($id),
                'id_window' => intval($windowId),
                'id_widget' => intval($widgetId)
            ];
            $showcaseInstance->save();
        }
    }

    public function updateRelations($attr)
    {
        Yii::app()->pointService->updatePointTVschedule($attr['id'], $attr['tvScheduleFrom'], $attr['tvScheduleTo']);
        Yii::app()->pointService->updateShowcases($attr['id'], $attr['showcases']);
        Yii::app()->pointService->updateChannels($attr['id'], $attr['channels']);
        Yii::app()->pointService->sendRequestForUpdate($attr['ip']);
        Yii::app()->pointService->prepareFilesForSync($attr['id']);
    }

    public function deleteRelations($id)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        $playlistToPointModel = $this->getPlaylistToPoint();
        $playlistToPointModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        $tvScheduleModel = $this->getTvSchedule();
        $tvScheduleModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        $showcaseModel = $this->getShowcase();
        $showcaseModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        $this->removeSpoolPath($id);
    }

    public function sendRequestForUpdate($ip)
    {
        if(defined('HTTP_REQUEST_TO_POINT')) {
            $requestData = array(
                "update_need" => true
            );

            $requestAddr = 'http://' . $ip . "/sc_upd";

            try {
                $client = new EHttpClient($requestAddr, [
                    'maxredirects' => 3,
                    'timeout' => 10,
                    'adapter' => 'EHttpClientAdapterCurl'
                ]);

                $client->setParameterGet($requestData);
                $response = $client->request();
                $body = "";
                if ($response->isSuccessful()) {
                    $body = $response->getBody();
                } else {
                    $body = $response->getRawBody();
                }
            } catch (Exception $ex) {
                error_log("http request exception - " . json_encode($ex) . ". " .
                        "IP - " . $requestAddr . ", " .
                        "Post - " . json_encode($requestData)
                );
            }
        }
    }

    public function removeSpoolPath($id)
    {
        $pointDir = $this->getSpoolPath() . $id;
        if (file_exists($pointDir)) {
            try {
                $this->deleteDir($pointDir);
            } catch (Exception $e) {
                error_log("Unlink failed. Exception - " . json_encode($e).
                    "Dir - " . $pointDir
                );
            }
        }
    }

    private function deleteDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->deleteDir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function prepareFilesForSync($id)
    {
        $fileModel = $this->getFile();
        $pointDir = $this->getSpoolPath() . $id;

        //remove dir if exist
        if(file_exists($pointDir)) {
            try {
                $this->deleteDir($pointDir);
            } catch (Exception $e) {
                error_log("Unlink failed. Exception - " . json_encode($e).
                "Dir - " . $pointDir);
            }
        }
        $playlistToPointModel = $this->getPlaylistToPoint();
        $avaliablePlaylists = $playlistToPointModel->findAllByAttributes([
            'id_point' => $id
        ]);

        foreach ($avaliablePlaylists as $playlistToPoint) {
            $pl = $playlistToPoint->playlist;
            $channelDir = $pointDir . "/" . $pl->type;
            $channelFullDir = $this->prepareSpoolPath($channelDir);

            $plFiles = explode(",", $pl->files);

            foreach ($plFiles as $fileId) {
                if ($fileId != '') {
                    $file = $fileModel->findByPk($fileId);
                    $symlinkPath = $channelFullDir . $file->name;
                    if(!file_exists($symlinkPath)
                        && file_exists($file->path)
                    ) {
                        if (defined('SYMLINK')) {
                            symlink($file->path, $symlinkPath);
                        } else {
                            copy($file->path, $symlinkPath);
                        }
                    }

                }
            }
        }
    }

    private function prepareSpoolPath($pathAppendix)
    {
        $pathAppendix = explode("/", $pathAppendix);
        $contentPath = $_SERVER["DOCUMENT_ROOT"];

        foreach($pathAppendix as $folder) {
            $contentPath .= "/" . $folder;
            if (!file_exists($contentPath) && !is_dir($contentPath)) {
                mkdir($contentPath);
            }
        }

        $contentPath .= "/";
        return $contentPath;
    }
}
