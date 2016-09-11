<?php
	// REMOVE THIS FILE AFTER POINTS MOVED TO REST INTERFACE
	// THIS FILE JUST TEMPORARY FALLBACK
	
	// interface.php?id=31&ch=1&date=20160612
	// interface/getPointSchedule/id/31/ch/1/date/20160612
	if(isset($_GET['id']) && isset($_GET['ch']) && isset($_GET['date']))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/interface/getPointSchedule';
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'GET',
						'content' => http_build_query($_GET)
				)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		echo $result;
	}
	
	/* TV */
	if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['tv']))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/interface/getTV';
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'GET',
						'content' => http_build_query($_GET)
				)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		echo $result;
		
		$pointId = intval($_GET['id']);
		$pointDate = intval($_GET['date']);
		
		$year = substr((string)$pointDate, 0, 4);
		$month = substr((string)$pointDate, 4, 2);
		$day = substr((string)$pointDate, 6, 2);
		
		$pointDate = $year . "-" . $month . "-" . $day; 
		
		$db = new DataBaseConnector();
		$link = $db->Connect();
			
		$sql = "SELECT `from`, `to` FROM `tvschedule` WHERE `point_id` IN ('".$pointId."') " .
				"AND (`from` <= '" . $pointDate . "') ".
				"AND (`to` >= '" . $pointDate . "') " .
				"ORDER BY `from` DESC;";
		
				
		$result = $link->query($sql);
		
		$onOffArr = array();
		while($row = $result->fetch_array())
		{
			$fromFull = explode(" ", $row['from']);
			$from = $fromFull[1];
			
			$toFull = explode(" ", $row['to']);
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
				$onOffList .= $item[0] . PHP_EOL;
				$onOffList .= '1 on';
				$onOffList .= PHP_EOL. PHP_EOL;
				$onOffList .= $item[1] . PHP_EOL;
				$onOffList .= '1 off';
				$onOffList .= PHP_EOL. PHP_EOL;
			}
		
			$pointDir = "spool/points/" . $pointId;
			$pointDir = PrepareSpoolPath($pointDir);
			
			$handle = fopen($pointDir . "/tv.txt", "w");
			fwrite($handle, $onOffList);
			fclose($handle);
		
			echo $onOffList;
		}
		else 
		{
			$onOffList .= "00:00:00" . PHP_EOL;
			$onOffList .= '1 off';
			$onOffList .= PHP_EOL. PHP_EOL;
			echo $onOffList;
		}
	}
	
	/* SYNC */
	if(isset($_GET['id']) && isset($_GET['sync']))
	{
		$pointId = intval($_GET['id']);
		$sync = intval($_GET['sync']);
	
		if($sync == 1){
			$db = new DataBaseConnector();
			$link = $db->Connect();
				
			$sql = "UPDATE `point` SET `sync` = 1, `sync_time` = NOW() WHERE `id` = " . $pointId . ";";
			$link->query($sql);
			
		}

		echo "ok";
	}
		
	
?>
