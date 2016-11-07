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
          echo "No channes attached to point $pointId, or point does not exist";
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
            echo "Some of params is incorrect (shound be integer)."
                . "Received pointId: $pointId, pointChannel: $pointChannel, pointDate: $pointDate";
            exit;
        }

        if(($pointDate > 20250101) || ($pointDate < 20150101))
        {
            http_response_code(400);
            echo "Incorrect date (shound be integer and formated as YYYY/mm/dd). Received pointDate: $pointDate";
            exit;
        }

        $completeSrt = '';

        /* background or stream */
        if (($pointChannel === 1) || (($pointChannel === 3))) {
            $bg = $CM->GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);

            if (count($bg) === 0) {
                http_response_code(404);
                echo "No avaliable content."
                    . "Received pointId: $pointId, pointChannel: $pointChannel, pointDate: $pointDate";
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
                echo "No avaliable content."
                    . "Received pointId: $pointId, pointChannel: $pointChannel, pointDate: $pointDate";
                exit;
            }

            for ($ii = 0; $ii < count($adv); $ii++)
            {
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
            echo "Incorrect channel. PointChannel: $pointChannel";
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
        if ($sync == 1) {
            $point = Point::model()->findByPk($id);
            $point->sync_time = new CDbExpression("NOW()");
            $point->sync = 1;
            $point->update('sync', 'sync_time');
        }

        echo 'ok';
        exit;
    }

    // interface/setSync/id/156/sync/1
    public function actionPostStatistics()
    {
        $id = Yii::app()->request->getParam('id');
        $data = Yii::app()->request->getParam('data');

        if (($id === null) || !is_int(intval($id))) {
            http_response_code(400);
            echo "Incorrect id. Id: $id";
            exit;
        }

        if (($data === null) || !is_string(strval($data))) {
            http_response_code(400);
            echo "Incorrect statistic data. Data: $data";
            exit;
        }

        $pointId = intval($id);
        $data = strval($data);

        try {
            $CM = Yii::app()->contentManager;
            $pointDir = "spool/points/" . $pointId;
            $pointDir = $CM->PrepareSpoolPath($pointDir);
            $handle = fopen($pointDir . "/" . data('Y-m-d') . "/statistic.txt", "w");
            fwrite($handle, $data);
            fclose($handle);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Saving data error. Exc: ". json_encode($e);
            exit;
        }

        echo 'ok';
    }

    // interface/setSync/id/156/sync/1
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
