<?php
    // REMOVE THIS FILE AFTER POINTS MOVED TO REST INTERFACE
    // THIS FILE JUST TEMPORARY FALLBACK

    // interface.php?id=31&ch=1&date=20160612
    // interface/getPointSchedule/id/31/ch/1/date/20160612
    if(isset($_GET['id']) && isset($_GET['ch']) && isset($_GET['date']))
    {
        $url = sprintf('%s://%s:%s/interface/getPointSchedule/id/%s/ch/%s/date/%s',
            'http', $_SERVER['SERVER_ADDR'], $_SERVER['SERVER_PORT'], $_GET['id'], $_GET['ch'], $_GET['date']);

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Host: ".$_SERVER['HTTP_HOST']));
            echo curl_exec($curl);
            curl_close($curl);
        }

        exit;
    }

    /* TV */
    // interface.php?id=31&tv=1&date=20160612
    // interface/getTVschedule/id/31/tv/1/date/20160612
    if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['tv']))
    {
        $url = sprintf('%s://%s:%s/interface/getTVschedule/id/%s/tv/%s/date/%s',
            'http', $_SERVER['SERVER_ADDR'], $_SERVER['SERVER_PORT'], $_GET['id'], 1, $_GET['date']);

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Host: ".$_SERVER['HTTP_HOST']));
            echo curl_exec($curl);
            curl_close($curl);
        }

        exit;
    }

    /* SYNC */
    // interface.php?id=31&sync=1
    // interface/setSync/id/31/sync/1
    if(isset($_GET['id']) && isset($_GET['sync']))
    {
        $url = sprintf('%s://%s/interface/setSync/id/%s/sync/%s',
            'http', $_SERVER['SERVER_NAME'], $_GET['id'], 1);

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            echo curl_exec($curl);
            curl_close($curl);
        }

        exit;
    }
