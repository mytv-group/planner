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
	// interface.php?id=31&tv=1&date=20160612
	// interface/getTVschedule/id/31/tv/1/date/20160612
	if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['tv']))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/interface/getTVschedule';
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

	/* SYNC */
	// interface.php?id=31&sync=1
	// interface/setSync/id/31/sync/1
	if(isset($_GET['id']) && isset($_GET['sync']))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/interface/setSync';
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
