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
				'actions'=>array('getPointSchedule', 'getChannels', 'getTVschedule', 'setSync'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

 		if(is_int($pointId) && is_int($pointChannel) && is_int($pointDate))
 		{
 			if(($pointDate < 20250101) && ($pointDate > 20150101))
 			{
 				$bg = $CM->GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);
 				$adv = $CM->GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);

 				if((count($bg) > 0) || (count($adv) > 0))
 				{
	 				$completeSrt = "";
	 				if((count($bg) > 0) && (count($adv) > 0))
	 				{
	 					$ii = 0;
	 					while((new DateTime($bg[$ii]['from']) < new DateTime($adv[0]['from']))
	 						&& $ii < count($bg)
	 					) {
	 						$bgBlock = $bg[$ii];
	 						$ii++;
	 						$from = $bgBlock["from"];

	 						$completeSrt .= $from . $this->eol;

	 						foreach ($bgBlock['filesWithDuration'] as $files)
	 						{
	 							$completeSrt .= $files[2];
	 						}

	 						$completeSrt .= $this->eol;
	 					}

	 					for($jj = 0; $jj < count($adv); $jj++)
	 					{
	 						if(($ii > 0)
	 							&& (new DateTime($bg[$ii - 1]['from']) < new DateTime($adv[$jj - 1]['from']))
	 							&& (new DateTime($bg[$ii - 1]['to']) > new DateTime($adv[$jj - 1]['to']))
	 						) {
	 							$bgBlock = $bg[$ii - 1];
	 							$from = $adv[$jj - 1]['to'];

	 							$completeSrt .= $from . $this->eol;

	 							foreach ($bgBlock['filesWithDuration'] as $files)
	 							{
	 								$completeSrt .= $files[2];
	 							}

	 							$completeSrt .= $this->eol;
	 						}

	 						$advBlock = $adv[$jj];
	 						$from = $advBlock["from"];

	 						$completeSrt .= $from . $this->eol;

	 						foreach ($advBlock['filesWithDuration'] as $files)
	 						{
	 							$completeSrt .= $files[2];
	 						}

	 						$completeSrt .= $this->eol;

	 						if(($jj < count($adv) - 1) && ($ii < count($bg))
	 							&& (new DateTime($bg[$ii]['from']) < new DateTime($adv[$jj + 1]['from']))
	 						) {
	 							$bgBlock = $bg[$ii];
	 							$ii++;
	 							$from = $bgBlock["from"];

	 							$completeSrt .= $from . $this->eol;

	 							foreach ($bgBlock['filesWithDuration'] as $files)
	 							{
	 								$completeSrt .= $files[2];
	 							}

	 							$completeSrt .= $this->eol;

	 							$jj++;
	 							$advBlock = $adv[$jj];
	 							$from = $advBlock["from"];

	 							$completeSrt .= $from . $this->eol;

	 							foreach ($advBlock['filesWithDuration'] as $files)
	 							{
	 								$completeSrt .= $files[2];
	 							}

	 							$completeSrt .= $this->eol;
	 						}
	 					}

	 					$pointDir = "spool/points/" . $pointId;
	 					$pointDir = $CM->PrepareSpoolPath($pointDir);

	 				} else if((count($bg) == 0) && (count($adv) > 0)) {
	 					foreach ($adv as $advBlock)
	 					{
	 						$from = $advBlock["from"];

	 						$completeSrt .= $from . $this->eol;

	 						foreach ($advBlock['filesWithDuration'] as $files)
	 						{
	 							$completeSrt .= $files[2];
	 						}

	 						$completeSrt .= $this->eol;
	 					}
	 				} else if((count($bg) > 0) && (count($adv) == 0)) {
	 					foreach ($bg as $bgBlock)
	 					{
	 						$from = $bgBlock["from"];

	 						$completeSrt .= $from . $this->eol;

	 						foreach ($bgBlock['filesWithDuration'] as $files)
	 						{
	 							$completeSrt .= $files[2];
	 						}

	 						$completeSrt .= $this->eol;
	 					}
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
 				else
 				{
		 			http_response_code(404);
					echo "No content for point. Received pointDate: $pointDate";
					exit;
 				}
 			}
 			else
 			{
	 			http_response_code(404);
				echo "Incorrect date (shound be integer and formated as YYYY/mm/dd). Received pointDate: $pointDate";
				exit;
 			}
 		}
 		else
 		{
 			http_response_code(404);
			echo "Some of params is incorrect (shound be integer)."
				."Received pointId: $pointId, pointChannel: $pointChannel, pointDate: $pointDate";
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
}
