<?php

Yii::import('ext.EHttpClient.*');

class PointsRepository extends BaseComponent
{
    public $user;
    public $tvSchedule;
    public $showcase;
    public $playlistToPoint;

    public function addPointTvSchedule($id, $tvScheduleFrom, $tvScheduleTo)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        $userId = intval($this->getUser()->id);

        if ((count($tvScheduleFrom) > 0)
            && (count($tvScheduleTo) > 0)
        ) {
            $tvScheduleTable = $this->organizeTvArray($tvScheduleFrom, $tvScheduleTo);
            $tvScheduleModel = $this->getTvSchedule();
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
    public function updatePointTVschedule($id, $tvScheduleFrom, $tvScheduleTo)
    {
        if (!is_int($id)) {
            throw new Exception("Incorrect PointId passed. Integer is required. Passed: "
                . json_encode($id), 1);
        }

        $tvScheduleModel = $this->getTvSchedule();
        $tvScheduleModel->deleteAll(
            "`id_point` = :id_point",
            [':id_point' => $id]
        );

        $this->addPointTvSchedule($id, $tvScheduleFrom, $tvScheduleTo);
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

    public function addChannels($id, $channels, $checkExist = true)
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
        foreach ($channels as $channelType => $playlists) {
            $playlists = array_unique($playlists);
            foreach ($playlists as $playlistId) {
                $attributes = [
                    'id_point' => intval($id),
                    'id_playlist' => intval($playlistId),
                    'channel_type' => intval($channelType)
                ];

                $list = [];

                if ($checkExist) {
                    PlaylistToPoint::model()->findAllByAttributes($attributes);
                }

                if (count($list) === 0) {
                    $playlistToPointInstance = new $playlistToPointModel();
                    $playlistToPointInstance->attributes = $attributes;
                    $playlistToPointInstance->save();
                }
            }
        }
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

        $this->addChannels($id, $channels, false);
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
            if (empty($windowId) || empty($widgetId)) {
                continue;
            }

            $windowId = intval($windowId);
            $widgetId = intval($widgetId);

            if (!is_int($windowId) || empty($widgetId)) {
                continue;
            }

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
        $this->updatePointTVschedule($attr['id'], $attr['tvScheduleFrom'], $attr['tvScheduleTo']);
        $this->updateShowcases($attr['id'], $attr['showcases']);
        $this->updateChannels($attr['id'], $attr['channels']);
        $this->sendRequestForUpdate($attr['id'], $attr['ip']);
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
    }

    public function sendRequestForUpdate($id, $ip)
    {
        $interactionUrl = Yii::app()->params['interactionUrl'];
        $action = '/point/updateContent';

        $requestData = [
            'id' => $id,
            'ip' => ip2long($ip)
        ];

        try {
            $client = new EHttpClient($interactionUrl . $action, [
                'maxredirects' => 3,
                'timeout' => 2,
                'adapter' => 'EHttpClientAdapterCurl'
            ]);

            $client->setParameterGet($requestData);
            $response = $client->request();
        } catch (Exception $ex) { }
    }

    public function sendRequestForScreen($id)
    {
        $interactionUrl = Yii::app()->params['interactionUrl'];
        $action = '/point/getScreenshot';

        $requestData = [
            'id' => $id
        ];

        try {
            $client = new EHttpClient($interactionUrl . $action, [
                'maxredirects' => 3,
                'timeout' => 2,
                'adapter' => 'EHttpClientAdapterCurl'
            ]);

            $client->setParameterGet($requestData);
            $response = $client->request();
        } catch (Exception $ex) { }

        return;
    }

    public function sendRequestForReload($id)
    {
        $interactionUrl = Yii::app()->params['interactionUrl'];
        $action = '/point/broadcastReload';

        $requestData = [
            'id' => $id
        ];

        try {
            $client = new EHttpClient($interactionUrl . $action, [
                'maxredirects' => 3,
                'timeout' => 2,
                'adapter' => 'EHttpClientAdapterCurl'
            ]);

            $client->setParameterGet($requestData);
            $response = $client->request();
        } catch (Exception $ex) { }

        return;
    }

    private function prepareScreenshot($file, $destPath, $url)
    {
        if (strpos($file, 'screenshot') > -1) {
            $nameParts = explode("_", $file);
            $timestamp = intval($nameParts[1]);
            if ($timestamp != 0) { //somescript uploading img from point
                if ($timestamp < (time() - 100)) {// img uploaded gather than 100 seconds ago
                    $newFileName = str_replace(strval($timestamp), "0", $file);
                    $imgDest = $destPath.DIRECTORY_SEPARATOR.$newFileName;
                    rename ($destPath.DIRECTORY_SEPARATOR.$file, $imgDest);
                    $answ = $this->receiveScreenshot($destPath, $url);
                    unlink($destPath.DIRECTORY_SEPARATOR.$newFileName);
                    return $answ;
                }
            }
        }

        return false;
    }

    private function receiveScreenshot($destPath, $url)
    {
        $imgDest = $destPath.'/screenshot_'.time().'.jpg';
        try {
            file_put_contents($imgDest, file_get_contents($url));
            $imgUrl = str_replace(YiiBase::getPathOfAlias('webroot'), Yii::app()->baseUrl, $imgDest);
            return $imgUrl;
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkIpOnline($ip)
    {
        $appendPath = '/spool/vpn-stat.txt';
        $contentPath = YiiBase::getPathOfAlias('webroot');
        $vpnStatFilePath = $contentPath.$appendPath;

        $res = false;
        $handle = @fopen($vpnStatFilePath, "r");
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                $bufArr = explode(",", $buffer);

                if (isset($bufArr[0])) {
                    $searchedIp = $bufArr[0];
                    if ($ip == $searchedIp) {
                        $res = true;
                        break;
                    }
                }
            }
            fclose($handle);
        }
        return $res;
    }

    public function getPointScreen($pointId, $pointIp)
    {
        //$url = 'http://DOMAIN_NAME/images/screenshot.jpg';
        $url = 'http://'.$pointIp.'/screenshot.jpg?pid='.$pointId;
        $appendPath = '/spool/points/'.$pointId;
        $contentPath = YiiBase::getPathOfAlias('webroot');
        $imgPath = $contentPath.$appendPath;

        //maybe this dir doesnt exist
        $pathAppendix = explode("/", $appendPath);
        foreach($pathAppendix as $folder) {
            $contentPath .= "/" . $folder;
            if (!file_exists($contentPath) && !is_dir($contentPath)) {
                mkdir($contentPath);
            }
        }

        $files = scandir($imgPath);
        if (($files) > 0) {
            $entrance = false;
            foreach ($files as $file) {
                $res = $this->prepareScreenshot($file, $imgPath, $url);
                if ($res != false) {
                    $entrance = true;
                    return $res;
                }
            }

            if (!$entrance) {
                $res = $this->receiveScreenshot($imgPath, $url);
                if ($res != false) {
                    return $res;
                }
            }
        } else  {
            $res = $this->receiveScreenshot($imgPath, $url);
            if ($res != false) {
                return $res;
            }
        }
    }
}
