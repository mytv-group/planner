<?php
	$messages = array(
			'incrData' => "E[0] Incorrect input data format",
			'incrDate' => "E[1] Incorrect date format",
			'noChInPoint' => "E[2] No channels in point",
			'emptyRes' => "E[3] Empty result. Nonexistent point or channel " .
				"or empty channel or overdue playlists or no playlist broadcasting at selected date",
			'emptyTVRes' => "E[4] Empty result. Nonexistent point " .
				"or empty TV schedule at selected date",
// 			'incrCh' => "E[2] Incorrect channel num",
// 			'noPoint' => "E[3] No points with received id",
// 			'emptyCh' => "E[4] Selected channel not set",
// 			'noFilesInCh' => "E[5] No files in selected channel",
// 			'noFilesAtDate' => "E[6] No files at selected date"
	);
	
	include_once '_DatabaseConnector.php';
	include_once '_ContentManager.php';

	if(isset($_GET['id']) && isset($_GET['ch']) && isset($_GET['date']))
	{
		
		$pointId = intval($_GET['id']);
		$pointChannel = intval($_GET['ch']);
		$pointDate = intval($_GET['date']);
		
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
				if($pointChannel <= 0) //send channels list
				{
					$db = new DataBaseConnector();
					$link = $db->Connect();
						
					$sql = "SELECT `internalId` FROM `channel` WHERE `id_point`= '".$pointId."';";
					$result = $link->query($sql);
					
					$channels= array();
					while($row = $result->fetch_array())
					{
						$channels[] = $row['internalId'];
					}			

					$db->Disconnect();
					unset($db);
					
					if(count($channels) > 0)
					{
						echo implode(PHP_EOL, $channels);
					}
					else 
					{
						echo  $messages['noChInPoint'];
					}
				}
				else
				{
					$CM = new ContentManager();
					$bg = $CM->GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);
					$adv = $CM->GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay);
					unset($CM);
					
					$completeSrt = "";					
					if((count($bg) > 0) && (count($adv) > 0))
					{
						$ii = 0;
						while((new DateTime($bg[$ii]['from']) < new DateTime($adv[0]['from'])) &&
								$ii < count($bg))
						{
							$bgBlock = $bg[$ii];
							$ii++;
							$from = $bgBlock["from"];
							
							echo ($from . PHP_EOL);
							$completeSrt .= $from . PHP_EOL;
							
							foreach ($bgBlock['filesWithDuration'] as $files)
							{
								echo ($files[2]);
								$completeSrt .= $files[2];
							}
							
							echo PHP_EOL;
							$completeSrt .= PHP_EOL;
						}
						
						for($jj = 0; $jj < count($adv); $jj++)
						{
							if(($ii > 0) && 
								(new DateTime($bg[$ii - 1]['from']) < new DateTime($adv[$jj - 1]['from'])) && 
								(new DateTime($bg[$ii - 1]['to']) > new DateTime($adv[$jj - 1]['to'])))
							{
								$bgBlock = $bg[$ii - 1];
								$from = $adv[$jj - 1]['to'];
								
								echo ($from . PHP_EOL);
								$completeSrt .= $from . PHP_EOL;
								
								foreach ($bgBlock['filesWithDuration'] as $files)
								{
									echo ($files[2]);
									$completeSrt .= $files[2];
								}
								
								echo PHP_EOL;
								$completeSrt .= PHP_EOL;
							}
							
							$advBlock = $adv[$jj];
							$from = $advBlock["from"];
							
							echo ($from . PHP_EOL);
							$completeSrt .= $from . PHP_EOL;
								
							foreach ($advBlock['filesWithDuration'] as $files)
							{
								echo ($files[2]);
								$completeSrt .= $files[2];
							}
							
							echo PHP_EOL;
							$completeSrt .= PHP_EOL;
							
							if(($jj < count($adv) - 1) && ($ii < count($bg)) && 
								(new DateTime($bg[$ii]['from']) < new DateTime($adv[$jj + 1]['from'])))
							{
								$bgBlock = $bg[$ii];
								$ii++;
								$from = $bgBlock["from"];
								
								echo ($from . PHP_EOL);
								$completeSrt .= $from . PHP_EOL;
								
								foreach ($bgBlock['filesWithDuration'] as $files)
								{
									echo ($files[2]);
									$completeSrt .= $files[2];
								}
								
								echo PHP_EOL;
								$completeSrt .= PHP_EOL;
								
								$jj++;
								$advBlock = $adv[$jj];
								$from = $advBlock["from"];
								
								echo ($from . PHP_EOL);
								$completeSrt .= $from . PHP_EOL;
								
								foreach ($advBlock['filesWithDuration'] as $files)
								{
									echo ($files[2]);
									$completeSrt .= $files[2];
								}
								
								echo PHP_EOL;
								$completeSrt .= PHP_EOL;
							}
						}
						
						$pointDir = "spool/points/" . $pointId;
						$pointDir = PrepareSpoolPath($pointDir);
						
						$handle = fopen($pointDir . "/ch" . $pointChannel . ".txt", "w");
						fwrite($handle, $completeSrt);
						fclose($handle);						
					}
					else if((count($bg) == 0) && (count($adv) > 0))
					{
						foreach ($adv as $advBlock)
						{
							$from = $advBlock["from"];
							
							echo ($from . PHP_EOL);
							$completeSrt .= $from . PHP_EOL;
							
							foreach ($advBlock['filesWithDuration'] as $files)
							{
								echo ($files[2]);
								$completeSrt .= $files[2];
							}
							
							echo PHP_EOL;
							$completeSrt .= PHP_EOL;
						}
						
						$pointDir = "spool/points/" . $pointId;
						$pointDir = PrepareSpoolPath($pointDir);
						
						$handle = fopen($pointDir . "/ch" . $pointChannel . ".txt", "w");
						fwrite($handle, $completeSrt);
						fclose($handle);
					}
					else if((count($bg) > 0) && (count($adv) == 0))
					{
						foreach ($bg as $bgBlock)
						{
							$from = $bgBlock["from"];
							
							echo ($from . PHP_EOL);
							$completeSrt .= $from . PHP_EOL;
							
							foreach ($bgBlock['filesWithDuration'] as $files)
							{
								echo ($files[2]);
								$completeSrt .= $files[2];
							}
							
							echo PHP_EOL;
							$completeSrt .= PHP_EOL;
						}
						
						$pointDir = "spool/points/" . $pointId;
						$pointDir = PrepareSpoolPath($pointDir);
						
						$handle = fopen($pointDir . "/ch" . $pointChannel . ".txt", "w");
						fwrite($handle, $completeSrt);
						fclose($handle);
					}
					else 
					{
						echo($messages['emptyRes']);
					}
				}					
			}
			else 
			{
				echo $messages['incrDate'];
			}
		}
		else
		{
			echo $messages['incrData'];
		}
	}
	
	
	/* Service func */
	function PrepareSpoolPath($extPathAppendix)
	{
		$pathAppendix = $extPathAppendix;
		$pathAppendix = explode("/", $pathAppendix);
	
		$contentPath = $_SERVER["DOCUMENT_ROOT"];
	
		foreach($pathAppendix as $folder)
		{
			$contentPath .= "/" . $folder;
			if (!file_exists($contentPath) && !is_dir($contentPath))
			{
				mkdir($contentPath);
			}
		}
	
		$contentPath .= "/";
		return $contentPath;
	}
	
	/* TV */
	if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['tv']))
	{
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
			echo "00:00:00 1 off";
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
