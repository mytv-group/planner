<?php

include_once '_DatabaseConnector.php';

class ContentManager {
	public function GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay) {
		$db = new DataBaseConnector ();
		$link = $db->Connect ();
		
		$pointDate = new DateTime($pointDatetimeStr);
		$pointDateStr = date_format ( $pointDate, "Y-m-d" );
		
		$sql = "SELECT `t3`.`id`, `files`, `type`, `fromDatetime`, `toDatetime`, `fromTime`,`toTime` FROM `channel` AS `t1` " . 
			"JOIN `playlist_to_channel` AS `t2` " . 
			"JOIN `playlists` AS `t3` " . 
			"ON `t1`.`id` = `t2`.`channelId` " . 
			"AND `t2`.`playlistId` = `t3`.`id` " . 
			"AND `t1`.`id_point` = '" . $pointId . "' " . 
			"AND `t1`.`internalId` = '" . $pointChannel . "' " . 
			"AND `t3`.`fromDatetime` <= '" . $pointDatetimeStr . "' " . 
			"AND `t3`.`toDatetime` >= '" . $pointDatetimeStr . "' " . 
			"AND `t3`.`" . $weekDay . "` = '1' " . 
			"AND (`t3`.`type` = '0'  OR `t3`.`type` = '2')" . 
			"ORDER BY `fromTime`;";
		
		$result = $link->query ( $sql );
		
		$blocksArr = array ();
		while ( $row = $result->fetch_array () ) {

			$fromDatetime = date_create ( $row ['fromDatetime'] );
			$toDatetime = date_create ( $row ['toDatetime'] );
			
			$fromTime = $row ['fromTime'];
			$toTime = $row ['toTime'];
			
			$files = $row ['files'];
			$type = $row ['type'];
			$playlistId = $row ['id'];
			
			/* if today starts showing check broadcasting is later showing begin */
			if (($fromDatetime < $toDatetime) && ($fromTime < $toTime)) {
				if (((date_format ( $fromDatetime, "Y-m-d" ) != $pointDateStr) && (date_format ( $toDatetime, "Y-m-d" ) != $pointDateStr)) || ((date_format ( $fromDatetime, "Y-m-d" ) == $pointDateStr) && (strtotime ( date_format ( $fromDatetime, "h:i:s" ) ) < strtotime ( $fromTime ))) || ((date_format ( $toDatetime, "Y-m-d" ) == $pointDateStr) && (strtotime ( date_format ( $toDatetime, "h:i:s" ) ) > strtotime ( $toTime )))) {
					$blocksArr [] = array (
							'from' => $fromTime,
							'to' => $toTime,
							'fromDateTime' => new DateTime ( $fromTime ),
							'toDateTime' => new DateTime ( $toTime ),
							'files' => $files,
							'type' => $type,
							'playlistId' => $playlistId
					);
				}
			}
		}
		
		if (count ( $blocksArr ) > 0) {
			foreach ( $blocksArr as &$block ) {
				if($block ['type'] == 2) {
					$files = implode ( "','", explode ( ",", $block ['files'] ) );
					$from = $block ['from'];
					
					$sql = "SELECT `url` FROM `stream` WHERE `playlist_id` = '" . $block['playlistId'] . "';";
					$result = $link->query ( $sql );
					
					if($result) {
						$duration = $block ['toDateTime']->getTimestamp() - $block ['fromDateTime']->getTimestamp();;
						$block ["filesWithDuration"] = array ();
						if ( $row = $result->fetch_array () ) {
							$block ["filesWithDuration"] [] = array (
									$duration + 5, //5 seconds above just not to have mute between turns
									$row ['url'],
									$duration . " " . $row ['url'] . "" . PHP_EOL /*ready to output str*/
							);
						}
						
						$block ["contentEndTime"] = date_format ( $block ["fromDateTime"]->modify ( '+' . intval ( $duration ) . ' seconds' ), "h:i:s" );
					}
				} else {
					$sql = "SELECT `duration`, `name` FROM `file` WHERE `id` IN ('" . $files . "');";
					$result = $link->query ( $sql );
					
					if($result) {
						$duration = 0;
						$block ["filesWithDuration"] = array ();
						while ( $row = $result->fetch_array () ) {
							$duration += $row ['duration'];
							$block ["filesWithDuration"] [] = array (
									$row ['duration'],
									$row ['name'],
									$row ['duration'] . " " . $row ['name'] . "" . PHP_EOL /*ready to output str*/
							);
						}
							
						$block ["duration"] = $duration;
						$block ["contentEndTime"] = date_format ( $block ["fromDateTime"]->modify ( '+' . intval ( $duration ) . ' seconds' ), "h:i:s" );
					}
				}
			}
		}
		

		
		return $blocksArr;
	}
	
	public function GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay) {
		$db = new DataBaseConnector ();
		$link = $db->Connect ();
		
		$sql = "SELECT `files`, `fromDatetime`, `toDatetime`, `every` FROM `channel` AS `t1` " . 
			"JOIN `playlist_to_channel` AS `t2` " . 
			"JOIN `playlists` AS `t3` " . 
			"ON `t1`.`id` = `t2`.`channelId` " . 
			"AND `t2`.`playlistId` = `t3`.`id` " . 
			"AND `t1`.`id_point` = '" . $pointId . "' " . 
			"AND `t1`.`internalId` = '" . $pointChannel . "' " . 
			"AND `t3`.`fromDatetime` <= '" . $pointDatetimeStr . "' " . 
			"AND `t3`.`toDatetime` >= '" . $pointDatetimeStr . "' " . 
			"AND `t3`.`" . $weekDay . "` = '1' " . "AND `t3`.`type` = '1';";
		
		$result = $link->query ( $sql );
		$advArr = array ();
		if ($row = $result->fetch_array ()) {
			$fromDatetime = date_create ( $row ['fromDatetime'] );
			$toDatetime = date_create ( $row ['toDatetime'] );
			
			$every = $row ['every'];
			$files = $row ['files'];
			$files = implode ( "','", explode ( ",", $files ) );
			
			$db2 = new DataBaseConnector ();
			$link2 = $db2->Connect ();
			
			$sql = "SELECT `duration`, `name` FROM `file` WHERE `id` IN ('" . $files . "');";
			$result2 = $link2->query ( $sql );
			
			$duration = 0;
			$filesWithDuration = array ();
			while ( $row2 = $result2->fetch_array () ) {
				$duration += $row2 ['duration'];
				$filesWithDuration [] = array (
						$row2 ['duration'],
						$row2 ['name'],
						$row2 ['duration'] . " " . $row2 ['name'] . "" . PHP_EOL /*ready to output str*/
				);
			}
			
			$duration = intval ( $duration );
			
			$repeating = explode ( ":", $every );
			$repeating = $repeating [0] * 60 * 60 + $repeating [1] * 60 + $repeating [0];
			
			$startTime = new DateTime ( '00:00:01' );
			$endTime = new DateTime ( '23:59:59' );
			
			$curTime = clone $startTime;
			
			while ( $curTime < $endTime ) {
				$endBlockTime = clone $curTime;
				$endBlockTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );
				
				$fromTime = $curTime->format ( 'H:i:s' );
				$toTime = $endBlockTime->format ( 'H:i:s' );
				
				$advArr [] = array (
						'from' => $fromTime,
						'to' => $toTime,
						'fromDateTime' => clone $curTime,
						'toDateTime' => clone $endBlockTime,
						'files' => $files,
						'duration' => $duration,
						"filesWithDuration" => $filesWithDuration 
				);
				
				$curTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );
				$curTime->add ( new DateInterval ( 'PT' . $repeating . 'S' ) );
			}
		}
		
		while ( $row = $result->fetch_array () ) {
			$fromDatetime = date_create ( $row ['fromDatetime'] );
			$toDatetime = date_create ( $row ['toDatetime'] );
			
			$every = $row ['every'];
			$files = $row ['files'];
			$files = implode ( "','", explode ( ",", $files ) );
			
			$db2 = new DataBaseConnector ();
			$link2 = $db2->Connect ();
			
			$sql = "SELECT `duration`, `name` FROM `file` WHERE `id` IN ('" . $files . "');";
			$result2 = $link2->query ( $sql );
			
			$duration = 0;
			$filesWithDuration = array ();
			while ( $row2 = $result2->fetch_array () ) {
				$duration += $row2 ['duration'];
				$filesWithDuration [] = array (
						$row2 ['duration'],
						$row2 ['name'],
						$row2 ['duration'] . " " . $row2 ['name'] . "" . PHP_EOL /*ready to output str*/
				);
			}
			
			$duration = intval ( $duration );
			
			$repeating = explode ( ":", $every );
			$repeating = $repeating [0] * 60 * 60 + $repeating [1] * 60 + $repeating [0];
			
			$startTime = new DateTime ( '00:00:01' );
			$endTime = new DateTime ( '23:59:59' );
			
			$curTime = clone $startTime;
			
			while ( $curTime < $endTime ) {
				$endBlockTime = clone $curTime;
				$endBlockTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );
				
				$fromTime = $curTime->format ( 'H:i:s' );
				$toTime = $endBlockTime->format ( 'H:i:s' );
				
				if (isset($advArr [0]) && ($endBlockTime < $advArr [0] ['fromDateTime'])) {
					array_insert ( $advArr, array (
							'from' => $fromTime,
							'to' => $toTime,
							'fromDateTime' => clone $curTime,
							'toDateTime' => clone $endBlockTime,
							'files' => $files,
							'duration' => $duration,
							"filesWithDuration" => $filesWithDuration 
					), 1 ); // insert in the beggining
				} else {
					$advArr [] = array (
							'from' => $fromTime,
							'to' => $toTime,
							'fromDateTime' => clone $curTime,
							'toDateTime' => clone $endBlockTime,
							'files' => $files,
							'duration' => $duration,
							"filesWithDuration" => $filesWithDuration 
					); // insert in the end
				}
				
				$curTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );
				$curTime->add ( new DateInterval ( 'PT' . $repeating . 'S' ) );
			}
		}
		return $advArr;
	}		
}

function array_insert(&$array,$element,$position=null) {
	if (count($array) == 0) {
		$array[] = $element;
	}
	elseif (is_numeric($position) && $position < 0) {
		if((count($array)+$position) < 0) {
			$array = array_insert($array,$element,0);
		}
		else {
			$array[count($array)+$position] = $element;
		}
	}
	elseif (is_numeric($position) && isset($array[$position])) {
		$part1 = array_slice($array,0,$position,true);
		$part2 = array_slice($array,$position,null,true);
		$array = array_merge($part1,array($position=>$element),$part2);
		foreach($array as $key=>$item) {
			if (is_null($item)) {
				unset($array[$key]);
			}
		}
	}
	elseif (is_null($position)) {
		$array[] = $element;
	}
	elseif (!isset($array[$position])) {
		$array[$position] = $element;
	}
	$array = array_merge($array);
	return $array;
}