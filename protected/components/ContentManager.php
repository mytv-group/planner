<?php

class ContentManager extends CApplicationComponent
{
    private $eol = YII_DEBUG ? '<br>' : PHP_EOL;

    public function GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay)
    {
        $connection = Yii::app()->db;

        $pointDate = new DateTime($pointDatetimeStr);
        $pointDateStr = date_format ( $pointDate, "Y-m-d" );

        $sql = "SELECT `t3`.`id`, `files`, `type`, `fromDatetime`, `toDatetime`, `fromTime`, `toTime`, `id_playlist`, `t4`.`id` AS 'id-author' FROM `playlist_to_point` AS `t2` " .
            "JOIN `playlists` AS `t3` " .
            "JOIN `user` AS `t4` " .
            "ON `t2`.`id_playlist` = `t3`.`id` " .
            "AND `t3`.`author` = `t4`.`username` " .
            "AND `t2`.`id_point` = '" . $pointId . "' " .
            "AND `t3`.`fromDatetime` <= '" . $pointDatetimeStr . "' " .
            "AND `t3`.`toDatetime` >= '" . $pointDatetimeStr . "' " .
            "AND `t3`.`" . $weekDay . "` = '1' " .
            "AND `t2`.`channel_type` = '" . $pointChannel . "' " .
            "ORDER BY `fromTime`;";

        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();

        $blocksArr = array ();
        foreach ($rows as $row) {
            $fromDatetime = date_create ($row['fromDatetime'] . ' ' . $row['fromTime']);
            $toDatetime = date_create ($row['toDatetime'] . ' ' . $row['toTime']);

            $fromTime = $row['fromTime'];
            $toTime = $row['toTime'];

            $type = $row['type'];
            $playlistId = intval($row['id_playlist']);
            $authorId = $row['id-author'];

            $files = [];
            $playlistInstance = Playlists::model()->findByPk($playlistId);
            if ($playlistInstance && $playlistInstance->relatedFiles) {
                $files = $playlistInstance->relatedFiles;
            }

            /* if today starts showing check broadcasting is later showing begin */
            if (($fromDatetime <= $toDatetime)) {
                $blocksArr [] = array (
                    'from' => $fromTime,
                    'to' => $toTime,
                    'fromDateTime' => new DateTime ( $fromTime ),
                    'toDateTime' => new DateTime ( $toTime ),
                    'files' => $files,
                    'type' => $type,
                    'playlistId' => $playlistId,
                    'authorId' => $authorId
                );
            }
        }

        foreach ($blocksArr as &$block) {
            if ($block ['type'] == 3) {
                $sql = "SELECT `url` FROM `stream` WHERE `playlist_id` = '" . $block['playlistId'] . "';";

                $command=$connection->createCommand($sql);
                $rows=$command->queryAll();

                if ($rows) {
                    $duration = $block ['toDateTime']->getTimestamp() - $block ['fromDateTime']->getTimestamp();;
                    $block ["filesWithDuration"] = array ();
                    foreach ($rows as $row) {
                        $block ["filesWithDuration"] [] = array (
                                $duration + 5, //5 seconds above just not to have mute between turns
                                $row['url'],
                                $duration . " " . $row['url'] . " "
                                    . "duration:0;"
                                    . "file:0;"
                                    . "pl:" . $block['playlistId'] . ";"
                                    . "author:" . $block['authorId'] . ""
                                    . $this->eol /*ready to output str*/
                        );
                    }

                    $block ["duration"] = $duration;
                }
            } else {
                $from = $block ['from'];

                $duration = 0;
                $block ["filesWithDuration"] = array ();
                $files = $block['files'];
                foreach($files as $file) {
                    $duration += $file->duration;
                    $block ["filesWithDuration"] [] = array (
                        $file->duration,
                        $file->name,
                        $file->duration . " "
                            . $file->name . " "
                            . "duration:" . $file->duration . ";"
                            . "file:" . $file->id . ";"
                            . "pl:" . $block['playlistId'] . ";"
                            . "author:" . $block['authorId'] . " "
                            . "" . $this->eol /*ready to output str*/
                    );
                }

                $block ["duration"] = $duration;
            }
        }

        return $blocksArr;
    }

    public function GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay)
    {
        $connection = Yii::app()->db;

        $sql = "SELECT `files`, `fromDatetime`, `toDatetime`, `fromTime`, `toTime`, `every`, `id_playlist`, `t4`.`id` AS 'id-author' FROM `playlist_to_point` AS `t2` " .
            "JOIN `playlists` AS `t3` " .
            "JOIN `user` AS `t4` " .
            "ON `t2`.`id_playlist` = `t3`.`id` " .
            "AND `t3`.`author` = `t4`.`username` " .
            "AND `t2`.`id_point` = '" . $pointId . "' " .
            "AND `t3`.`fromDatetime` <= '" . $pointDatetimeStr . "' " .
            "AND `t3`.`toDatetime` >= '" . $pointDatetimeStr . "' " .
            "AND `t2`.`channel_type` = '" . $pointChannel . "' " .
            "AND `t3`.`" . $weekDay . "` = '1' " . "AND `t3`.`type` = '2';";

        $command=$connection->createCommand($sql);
        $rows=$command->queryAll();

        $advArr = array ();
        foreach ($rows as $row) {
            $fromDatetime = date_create ( $row['fromDatetime']);
            $toDatetime = date_create ( $row['toDatetime']);
            $playlistId = intval($row['id_playlist']);

            $files = [];
            $playlistInstance = Playlists::model()->findByPk($playlistId);
            if ($playlistInstance && $playlistInstance->relatedFiles) {
                $files = $playlistInstance->relatedFiles;
            }

            $every = $row['every'];

            $duration = 0;
            $filesWithDuration = array ();
            foreach ($files as $file) {
                $duration += $file->duration;
                $filesWithDuration [] = array (
                    $file->duration,
                    $file->name,
                    $file->duration . " " . $file->name . " "
                        . "duration:" . $file->duration . ";"
                        . "file:" . $file->id . ";"
                        . "pl:" . $row['id_playlist'] . ";"
                        . "author:" . $row['id-author'] . ""
                        . $this->eol /*ready to output str*/
                );
            }

            $duration = intval ( $duration );

            $repeating = explode ( ":", $every );
            $repeating = $repeating [0] * 60 * 60 + $repeating [1] * 60 + $repeating [2];

            $startTime = new DateTime ($row['fromTime']);
            $endTime = new DateTime ($row['toTime']);

            $curTime = clone $startTime;

            while ($curTime < $endTime) {
                $endBlockTime = clone $curTime;
                $endBlockTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );

                $fromTime = clone $curTime;
                $fromTime = $fromTime->format ( 'H:i:s' );

                $toTime  = clone $endBlockTime;
                $toTime = $toTime->format ( 'H:i:s' );

                $advArr [] = array (
                    'from' => $fromTime,
                    'to' => $toTime,
                    'fromDateTime' => clone $curTime,
                    'toDateTime' => clone $endBlockTime,
                    'files' => $files,
                    'duration' => $duration,
                    "filesWithDuration" => $filesWithDuration
                );

                $curTime->add ( new DateInterval ( 'PT' . $repeating . 'S' ) );
            }
        }

        $size = count($advArr) - 1;
        for ($ii = $size; $ii >= 0; $ii--) {
            for ($jj = 0; $jj <= ($ii-1); $jj++) {
                $first = new DateTime ($advArr[$jj]['from']);
                $next = new DateTime ($advArr[$jj+1]['from']);

                if ($first > $next) {
                        $tmp = $advArr[$jj];
                        $advArr[$jj] = $advArr[$jj+1];
                        $advArr[$jj+1] = $tmp;
                }
            }
        }

        return $advArr;
    }

    public function GenerateContentBlock($block, $from = null)
    {
        $blockStr = '';

        if (!isset($block["from"]) || !isset($block["filesWithDuration"])) {
            return $blockStr;
        }

        if ($from === null) {
            $from = $block["from"];
        }

        $blockStr .= $from . $this->eol;
        foreach ($block['filesWithDuration'] as $files) {
            if (isset($files[2])) {
                $blockStr .= $files[2];
            }
        }

        return $blockStr . $this->eol;
    }

    public function PrepareSpoolPath($pathAppendix)
    {
        $pathAppendix = explode("/", $pathAppendix);

        $contentPath = $_SERVER["DOCUMENT_ROOT"];

        foreach($pathAppendix as $folder) {
            $contentPath .= "/" . $folder;
            if (!file_exists($contentPath) && !is_dir($contentPath)) {
                mkdir($contentPath);
            }
        }

        $contentPath .= "/";
        return $contentPath;
    }

    private function arrayInsert(&$array,$element,$position=null)
    {
        if (count($array) == 0) {
            $array[] = $element;
        } elseif (is_numeric($position) && $position < 0) {
            if((count($array)+$position) < 0) {
                $array = $this->arrayInsert($array,$element,0);
            } else {
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
}
