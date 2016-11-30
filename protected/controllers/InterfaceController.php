<?php

class InterfaceController extends Controller
{
    private $eol = YII_DEBUG ? '<br>' : PHP_EOL;
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('getPointSchedule', 'getChannels', 'getTVschedule', 'setSync', 'postStatistics'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function filters()
    {
        return [
            'postOnly + postStatistics',
        ];
    }

    public function actionGetChannels($id)
    {
        $pointId = intval($id);

        if(is_int($pointId))
        {
          $channels = Channel::model()->findAll(["condition"=>"id_point = $pointId"]);

          $channelIds = [];
          foreach($channels as $channel) {
              $channelIds[] = $channel->internalId;
          }

          if(count($channelIds) > 0)
          {
              echo implode($this->eol, $channelIds);
              exit;
          }

          http_response_code(404);
          echo sprintf("No channes attached to point %s, or point does not exist", $pointId);
          exit;
        }
    }

    // interface/getPointSchedule/id/124/ch/1/date/20160806
    public function actionGetPointSchedule($id, $ch, $date)
    {
        $pointId = intval($id);
        $pointChannel = intval($ch);
        $pointDate = intval($date);

        $CM = Yii::app()->contentManager;

        $year = substr((string)$pointDate, 0, 4);
        $month = substr((string)$pointDate, 4, 2);
        $day = substr((string)$pointDate, 6, 2);

        $pointDateStr =  $year . "-" . $month . "-" . $day;
        $pointDatetimeStr =  $year . "-" . $month . "-" . $day. " 23:59:59";
        $pointDateTimestamp = strtotime($day . "-" . $month . "-" . $year);
        $weekDay = strtolower(date('D', $pointDateTimestamp));

        if(!is_int($pointId) || !is_int($pointChannel) || !is_int($pointDate))
        {
            http_response_code(400);
            echo sprintf("Some of params is incorrect (shound be integer). "
                ."Received pointId: %s, pointChannel: %s, pointDate: %s",
                $pointId, $pointChannel, $pointDate
            );
            exit;
        }

        if(($pointDate > 20250101) || ($pointDate < 20150101))
        {
            http_response_code(400);
            echo sprintf("Incorrect date (shound be integer and formated as YYYY/mm/dd). Received pointDate: %s",
                $pointDate);
            exit;
        }

        $completeSrt = '';

        /* background or stream */
        if (($pointChannel === 1) || (($pointChannel === 3))) {
            $bg = $CM->GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);

            if (count($bg) === 0) {
                http_response_code(404);
                echo sprintf("No avaliable content. "
                    ."Received pointId: %s, pointChannel: %s, pointDate: %s",
                    $pointId, $pointChannel, $pointDate
                );
                exit;
            }

            for ($ii = 0; $ii < count($bg); $ii++)
            {
                $completeSrt .= $CM->GenerateContentBlock($bg[$ii]);

                if (($ii > 0)
                  && (strtotime($bg[$ii - 1]["to"]) > strtotime($bg[$ii]["to"]))
                ) {
                    $completeSrt .= $CM->GenerateContentBlock($bg[$ii - 1], $bg[$ii]["to"]);
                }
            }
        } else if ($pointChannel === 2) { /*advertising*/
            $adv = $CM->GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);

            if (count($adv) === 0) {
                http_response_code(404);
                echo sprintf("No avaliable content. "
                    ."Received pointId: %s, pointChannel: %s, pointDate: %s",
                    $pointId, $pointChannel, $pointDate
                );
                exit;
            }

            for ($ii = 0; $ii < count($adv); $ii++) {
                $completeSrt .= $CM->GenerateContentBlock($adv[$ii]);

                if (($ii < count($adv) - 1)
                  && (strtotime($adv[$ii + 1]["from"]) < strtotime($adv[$ii]["to"]))
                ) {
                    $completeSrt .= $CM->GenerateContentBlock($adv[$ii + 1], $adv[$ii]["to"]);
                    $ii++;
                }
            }
        } else {
            http_response_code(400);
            echo sprintf("Incorrect channel. PointChannel: %s", $pointChannel);
            exit;
        }

        try {
            $pointDir = "spool/points/" . $pointId;
            $pointDir = $CM->PrepareSpoolPath($pointDir);

            $handle = fopen($pointDir . "/ch" . $pointChannel . ".txt", "w");
            fwrite($handle, $completeSrt);
            fclose($handle);
        } catch(Exception $e) {
          // wont break result because file
        } finally {
            echo $completeSrt;
            exit;
        }
    }

    public function actionGetTVschedule($id, $date, $tv)
    {
        $pointId = intval($_GET['id']);
        $pointDate = intval($_GET['date']);

        $year = substr((string)$pointDate, 0, 4);
        $month = substr((string)$pointDate, 4, 2);
        $day = substr((string)$pointDate, 6, 2);

        $pointDate = $year . "-" . $month . "-" . $day;

        $criteria=new CDbCriteria;
        $criteria->select = "`from`, `to`";
        $criteria->condition = "`point_id` = {$pointId} AND `from` <= '{$pointDate}' AND `to` >= '{$pointDate}'";
        $criteria->order = '`from` DESC';
        $schedule = TVSchedule::model()->findAll($criteria);

        $onOffArr = [];
        foreach ($schedule as $item)
        {
            $fromFull = explode(" ", $item->from);
            $from = $fromFull[1];

            $toFull = explode(" ", $item->to);
            $to = $toFull[1];

            /* if prev 'to' gather current 'from' change prev 'to' to current */
            if((count($onOffArr) > 0) &&
                (date($onOffArr[count($onOffArr) - 1][1]) > date($from)) &&
                (date($onOffArr[count($onOffArr) - 1][1]) < date($to)))
            {
                $onOffArr[count($onOffArr) - 1][1] = $to;
            } else {
                $onOffArr[] = array($from, $to);
            }
        }

        if(count($onOffArr) > 0)
        {
            $onOffList = '';
            foreach ($onOffArr as $item)
            {
                $onOffList .= $item[0] . $this->eol;
                $onOffList .= '1 on';
                $onOffList .= $this->eol. $this->eol;
                $onOffList .= $item[1] . $this->eol;
                $onOffList .= '1 off';
                $onOffList .= $this->eol. $this->eol;
            }

            $pointDir = "spool/points/" . $pointId;
            $CM = Yii::app()->contentManager;
            $pointDir = $CM->PrepareSpoolPath($pointDir);

            $handle = fopen($pointDir . "/tv.txt", "w");
            fwrite($handle, $onOffList);
            fclose($handle);

            echo $onOffList;
        }
        else
        {
            $onOffList = "00:00:00" . PHP_EOL;
            $onOffList .= '1 off';
            $onOffList .= PHP_EOL. PHP_EOL;
            echo $onOffList;
        }
    }

    // interface/setSync/id/156/sync/1
    public function actionSetSync($id, $sync)
    {
        $point = Point::model()->findByPk($id);

        if (!isset($point)) {
            http_response_code(404);
            echo sprintf('Point with id %s does not exist.', $id);
            exit;
        }

        if ($sync == 1) {
            $point->sync_time = new CDbExpression("NOW()");
            $point->sync = 1;
            $point->update('sync', 'sync_time');
        }

        echo 'ok';
        exit;
    }

    // interface/setSync/id/156/sync/1
    /*
    * $_POST['data'] has following structure
    * [
    *     {
    *         "channel": 1,
    *         "file": "xxxxxx.mp4",
    *         "date": "20161107",
    *         "time": "18:23",
    *         "meta": "YourMetaData"
    *     },
    *     ...
    * ]
    */
    public function actionPostStatistics()
    {
        $id = Yii::app()->request->getParam('id');
        $data = Yii::app()->request->getRawBody();

        if (($id === null) || !is_int(intval($id))) {
            http_response_code(400);
            echo sprintf("Incorrect id. Id: %s", $id);
            exit;
        }

        if (($data === null) || !is_string(strval($data))) {
            http_response_code(400);
            echo sprintf("Incorrect statistic data. Data: %s", $data);
            exit;
        }

        $pointId = intval($id);
        $decodedData = json_decode(strval($data), true);

        if ($decodedData === null) {
            try {
                $CM = Yii::app()->contentManager;
                $pointDir = "spool/points/" . $pointId . "/" . data('Y-m-d');
                $pointDir = $CM->PrepareSpoolPath($pointDir);
                $handle = fopen($pointDir . "/statistic.txt", "w");
                fwrite($handle, $data);
                fclose($handle);
            } catch (Exception $e) {
                http_response_code(500);
                echo sprintf("Saving data error. Exc: %s", json_encode($e));

                exit;
            }

            http_response_code(400);
            echo sprintf("Incorrect statistic data format. JSON decoding error. Data: %s", $data);

            exit;
        }

        foreach ($decodedData as $value) {
            if (!$value['meta']) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Empty meta in row %s. Data: %s", json_encode($value), $data);
                exit;
            }

            $explodedMeta = explode(';', $value['meta']);

            if (count($explodedMeta) !== 4) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken meta in row %s. Data: %s", json_encode($value), $data);
                exit;
            }

            $duration = explode(':', $explodedMeta[0]);
            if (!isset($duration[0])
                || !isset($duration[1])
                || !is_float(floatval($duration[1]))
                || ($duration[0] !== 'duration')
            ) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken meta in row %s. No duration set. Data: %s", json_encode($value), $data);
                exit;
            }
            $duration = floatval($duration[1]);

            $idFile = explode(':', $explodedMeta[1]);
            if (!isset($idFile[0])
                || !isset($idFile[1])
                || !is_int(intval($idFile[1]))
                || ($idFile[0] !== 'file')
            ) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken meta in row %s. No file id. Data: %s", json_encode($value), $data);
                exit;
            }
            $idFile = intval($idFile[1]);

            $idPlaylist = explode(':', $explodedMeta[2]);
            if (!isset($idPlaylist[0])
                || !isset($idPlaylist[1])
                || !is_int(intval($idPlaylist[1]))
                || ($idPlaylist[0] !== 'pl')
            ) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken meta in row %s. No playlist id. Data: %s", json_encode($value), $data);
                exit;
            }
            $idPlaylist = intval($idPlaylist[1]);

            $idAuthor = explode(':', $explodedMeta[3]);
            if (!isset($idAuthor[0])
                || !isset($idAuthor[1])
                || !is_int(intval($idAuthor[1]))
                || ($idAuthor[0] !== 'author')
            ) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken meta in row %s. No author id. Data: %s", json_encode($value), $data);
                exit;
            }
            $idAuthor = intval($idAuthor[1]);

            if (!$value['channel']
                || !$value['file']
                || !$value['date']
                || !$value['time']
                || !is_int(intval($value['channel']))
                || !is_string(strval($value['file']))
                || !is_string(strval($value['date']))
                || !is_string(strval($value['time']))
            ) {
                http_response_code(400);
                echo sprintf("Incorrect statistic data format. Broken row structure %s. Data: %s", json_encode($value), $data);
                exit;
            }

            $channel = intval($value['channel']);
            $fileName = strval($value['file']);
            $date = strval($value['date']);
            $time = strval($value['time']);

            $year = substr((string)$date, 0, 4);
            $month = substr((string)$date, 4, 2);
            $day = substr((string)$date, 6, 2);

            $hours = substr((string)$time, 0, 2);
            $minutes = substr((string)$time, 3, 2);
            $seconds = substr((string)$time, 6, 2);

            $statistic = new Statistic();
            $statistic->dt_playback = $year . '-' . $month . '-' . $day . ' ' . $hours . ':' . $minutes . ':' . $seconds;
            $statistic->duration = $duration;
            $statistic->file_name = substr($fileName, 0, 255);
            $statistic->channel = $channel;
            $statistic->id_point = $pointId;
            $statistic->id_file = $idFile;
            $statistic->id_playlist = $idPlaylist;
            $statistic->id_author = $idAuthor;
            $statistic->save();
        }

        echo 'ok';
    }

    // interface/getWidgets/id/156/
    public function actionGetWidgets($id)
    {
        $point = Point::model()->findByPk($id);

        if (!isset($point)) {
            http_response_code(404);
            echo sprintf('Point with id %s does not exist.', $id);
            exit;
        }

        if (!$point->screen) {
            http_response_code(500);
            echo 'Point screen haven\'t been configured.';
            exit;
        }

        $responce = ['responce_version' => '1'];
        $screen = $point->screen;
        $responce['screen'] = $screen->getInfo();

        $channels = $point->channels;
        $responce['widgets'] = [];

        foreach($channels as $channel) {
            $widgetToChannel = $channel->widgetToChannel;
            $window = $channel->window;

            if ($widgetToChannel
                && $window
                && $widgetToChannel->widget
            ) {
                $widget = $widgetToChannel->widget;
                $responce['widgets'][] = [
                    'window' => $window->getInfo(),
                    'config' => $widget->getInfo(),
                    'content' => $widget->getWidgetInfo()
                ];
            }
        }

        echo json_encode($responce);
        exit;
    }
}
