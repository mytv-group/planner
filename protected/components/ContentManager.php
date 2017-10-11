<?php

class ContentManager extends BaseComponent
{
    private $eol = YII_DEBUG ? '<br>' : PHP_EOL;

    public function GetBgContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay)
    {
        $connection = Yii::app()->db;

        $pointDate = new DateTime($pointDatetimeStr);
        $pointDateStr = date_format ( $pointDate, "Y-m-d" );

        $rows = Yii::app()->db->createCommand()
            ->select('type, '
                . 'fromDatetime, '
                . 'toDatetime, '
                . 'fromTime, '
                . 'toTime, '
                . 'id_playlist, '
                . 'files_order, '
                . 'id_user'
            )
            ->from('playlist_to_point')
            ->join('playlists', '')
            ->join('user', '')
            ->where([
                'and',
                'playlist_to_point.id_playlist = playlists.id',
                'playlists.id_user = user.id',
                'playlist_to_point.id_point = :pointId',
                'playlists.fromDatetime <= :pointDatetimeStr',
                'playlists.toDatetime >= :pointDatetimeStr',
                'playlists.'.$weekDay.' = 1',
                'playlist_to_point.channel_type = :pointChannel'
            ], [
                ':pointId' => $pointId,
                ':pointDatetimeStr' => $pointDatetimeStr,
                ':pointChannel' => $pointChannel
            ])
            ->order('fromTime')
            ->queryAll();

        $blocksArr = array ();
        foreach ($rows as $row) {
            $fromDatetime = date_create ($row['fromDatetime'] . ' ' . $row['fromTime']);
            $toDatetime = date_create ($row['toDatetime'] . ' ' . $row['toTime']);

            $fromTime = $row['fromTime'];
            $toTime = $row['toTime'];

            $filesOrder = $row['files_order'];
            $type = $row['type'];
            $playlistId = intval($row['id_playlist']);
            $authorId = $row['id_user'];

            $files = [];
            $playlistInstance = Playlists::model()->findByPk($playlistId);

            if ($playlistInstance && $filesToPlaylist = $playlistInstance->filesToPlaylist) {
                $filesToPlaylist = $this->sortFiles($filesToPlaylist);

                foreach ($filesToPlaylist as $filesToPlaylistInstance) {
                    $files[] = $filesToPlaylistInstance->file;
                }

                $files = $this->orderArray($files, $filesOrder);
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
                $blockPlaylistId = $block['playlistId'];

                $streams = Stream::model()->findAllByAttributes(['playlist_id' => $blockPlaylistId]);

                if ($streams) {
                    $duration = $block ['toDateTime']->getTimestamp() - $block ['fromDateTime']->getTimestamp();;
                    $block ["filesWithDuration"] = array ();
                    foreach ($streams as $stream) {
                        $block ["filesWithDuration"] [] = array (
                                $duration + 5, //5 seconds above just not to have mute between turns
                                $stream->url,
                                $duration . " " . $stream->url . " "
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

        $rows = Yii::app()->db->createCommand()
            ->select('fromDatetime, '
                . 'toDatetime, '
                . 'fromTime, '
                . 'toTime, '
                . 'every, '
                . 'id_playlist, '
                . 'files_order, '
                . 'id_user'
            )
            ->from('playlist_to_point')
            ->join('playlists', '')
            ->join('user', '')
            ->where([
                'and',
                'playlist_to_point.id_playlist = playlists.id',
                'playlists.id_user = user.id',
                'playlist_to_point.id_point = :pointId',
                'playlists.fromDatetime <= :pointDatetimeStr',
                'playlists.toDatetime >= :pointDatetimeStr',
                'playlists.'.$weekDay.' = 1',
                'playlists.type = 2',
                'playlist_to_point.channel_type = :pointChannel'
            ], [
                ':pointId' => $pointId,
                ':pointDatetimeStr' => $pointDatetimeStr,
                ':pointChannel' => $pointChannel
            ])
            ->queryAll();

        $advArr = array ();
        foreach ($rows as $row) {
            $fromDatetime = date_create ( $row['fromDatetime']);
            $toDatetime = date_create ( $row['toDatetime']);
            $playlistId = intval($row['id_playlist']);
            $filesOrder = $row['files_order'];

            $files = [];
            $playlistInstance = Playlists::model()->findByPk($playlistId);

            if ($playlistInstance && $filesToPlaylist = $playlistInstance->filesToPlaylist) {
                $filesToPlaylist = $this->sortFiles($filesToPlaylist);

                foreach ($filesToPlaylist as $filesToPlaylistInstance) {
                    $files[] = $filesToPlaylistInstance->file;
                }

                $files = $this->orderArray($files, $filesOrder);
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
                        . "author:" . $row['id_user'] . ""
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

    private function sortFiles($filesToPlaylist)
    {
        function compareOrder($a, $b) {
            if ($a['order'] === $b['order']) { return 0; }

            return ($a['order'] < $b['order']) ? -1 : 1;
        }

        usort($filesToPlaylist, "compareOrder");

        return $filesToPlaylist;
    }


    private function orderArray($unorderedFiles, $order='ASC')
    {


        $files = [];

        if ($order === 'ASC') {
            $files = $unorderedFiles;
        }

        if ($order === 'DESC') {
            $files = array_reverse($unorderedFiles);
        }

        if ($order === 'RANDOM') {
            $files = $unorderedFiles;
            shuffle($files);
        }

        return $files;
    }
}
