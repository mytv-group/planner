<?php

Yii::import('ext.EHttpClient.*');

class PointService extends CApplicationComponent
{
    public $user;
    public $tvSchedule;
    public $showcase;
    public $playlistToPoint;

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

    public function addPointTVschedule($id, $tvScheduleFrom, $tvScheduleTo)
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

        $this->addPointTVschedule($id, $tvScheduleFrom, $tvScheduleTo);
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
        $this->sendRequestForUpdate($attr['ip']);
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
}
