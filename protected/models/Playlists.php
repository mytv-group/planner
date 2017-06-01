<?php

/**
 * This is the model class for table "playlists".
 *
 * The followings are the available columns in table 'playlists':
 * @property integer $id
 * @property string $name
 * @property string $files
 * @property string $fromDatetime
 * @property string $toDatetime
 * @property string $fromTime
 * @property string $toTime
 * @property integer $sun
 * @property integer $mon
 * @property integer $tue
 * @property integer $wed
 * @property integer $thu
 * @property integer $fri
 * @property integer $sat
 * @property string $author
 */
class Playlists extends CActiveRecord
{
    public static $types = [
        1 => 'Background',
        2 => 'Advertising',
        3 => 'Stream'
    ];

    public static $typesShort = [
        1 => 'bg',
        2 => 'adv',
        3 => 'strm'
    ];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'playlists';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, fromDatetime, toDatetime, type, fromTime, toTime, author', 'required'),
            array('name', 'length', 'max'=>100),
            array('files', 'length', 'max'=>65000),
            array('author', 'length', 'max'=>255),
            array('sun, mon, tue, wed, thu, fri, sat', 'boolean'),
            array('type','in','range'=>array('1','2','3'),'allowEmpty'=>false),
            array('fromDatetime, toDatetime', 'type', 'type'=>'date', 'dateFormat'=>'yyyy-MM-dd'),
            array('fromTime, toTime, every', 'type', 'type'=>'time', 'timeFormat'=>'hh:mm:ss'),
            array('name', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'stream' => array(self::HAS_MANY, 'Stream', 'playlist_id'),
            'filesToPlaylist'=>array(self::HAS_MANY, 'FileToPlaylist', ['id_playlist' => 'id']),
            'relatedFiles'=>array(self::HAS_MANY, 'File', ['id_file'=>'id'],'through'=>'filesToPlaylist'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'files' => 'Files',
            'fromDatetime' => 'From Date',
            'toDatetime' => 'To Date',
            'type' => 'Type',
            'fromTime' => 'Start Broadcasting',
            'toTime' => 'End Broadcasting',
            'every' => 'Every',
            'sun' => 'Sun',
            'mon' => 'Mon',
            'tue' => 'Tue',
            'wed' => 'Wed',
            'thu' => 'Thu',
            'fri' => 'Fri',
            'sat' => 'Sat',
            'author' => 'Author',
            'weekDays' => 'Week Days',
            'ctrl' => 'Controls'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $criteria->compare('author', Yii::app()->user->username);
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function searchByExpiration($expirationTo, $expirationFrom = null)
    {
        $criteria=new CDbCriteria;

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $criteria->compare('author', Yii::app()->user->username);
        }

        $expirationToExpression = new CDbExpression($expirationTo);
        $criteria->addCondition('`toDatetime` < '.$expirationToExpression);

        if ($expirationFrom !== null) {
            $expirationFromExpression = new CDbExpression($expirationFrom);
            $criteria->addCondition('`toDatetime` >= '.$expirationFromExpression);
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Playlists the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function getUserPlaylists()
    {
        if (in_array(Yii::app()->user->role,
          [User::ROLE_ADMIN])
        ) {
            return self::model()->findAll();
        } else {
            return self::model()->findAllByAttributes(['author' => Yii::app()->user->name]);
        }
    }

    public function GetBGContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay)
    {
        $connection = Yii::app()->db;
        $connection->active=true;

        $pointDate = new DateTime($pointDatetimeStr);
        $pointDateStr = date_format ( $pointDate, "Y-m-d" );

        $sql = "SELECT `files`, `fromDatetime`, `toDatetime`, `fromTime`,`toTime` FROM `channel` AS `t1` " .
                "JOIN `playlist_to_channel` AS `t2` " .
                "JOIN `playlists` AS `t3` " .
                "ON `t1`.`id` = `t2`.`channelId` " .
                "AND `t2`.`playlistId` = `t3`.`id` " .
                "AND `t1`.`id_point` = '" . $pointId . "' " .
                "AND `t1`.`internalId` = '" . $pointChannel . "' " .
                "AND `t3`.`fromDatetime` <= '" . $pointDatetimeStr . "' " .
                "AND `t3`.`toDatetime` >= '" . $pointDatetimeStr . "' " .
                "AND `t3`.`" . $weekDay . "` = '1' " .
                "AND `t3`.`type` = '0' " .
                "ORDER BY `fromTime`;";

        $command = $connection->createCommand($sql);
        $dataReader = $command->query();

        $blocksArr = array ();
        while (($row=$dataReader->read()) != false) {
            $fromDatetime = date_create ( $row ['fromDatetime'] );
            $toDatetime = date_create ( $row ['toDatetime'] );

            $fromTime = $row ['fromTime'];
            $toTime = $row ['toTime'];

            $files = $row ['files'];

            /* if today starts showing check broadcasting is later showing begin */
            if (($fromDatetime < $toDatetime) && ($fromTime < $toTime)) {
                if (((date_format ( $fromDatetime, "Y-m-d" ) != $pointDateStr) &&
                        (date_format ( $toDatetime, "Y-m-d" ) != $pointDateStr)) ||
                        ((date_format ( $fromDatetime, "Y-m-d" ) == $pointDateStr) &&
                                (strtotime ( date_format ( $fromDatetime, "h:i:s" ) ) <  strtotime ( $fromTime ))) ||
                        ((date_format ( $toDatetime, "Y-m-d" ) == $pointDateStr) &&
                                (strtotime ( date_format ( $toDatetime, "h:i:s" ) ) >  strtotime ( $toTime )))) {

                    $blocksArr [] = array (
                            'from' => $fromTime,
                            'to' => $toTime,
                            'fromDateTime' => new DateTime ( $fromTime ),
                            'toDateTime' => new DateTime ( $toTime ),
                            'files' => $files
                    );
                }
            }
        }

        if (count ( $blocksArr ) > 0) {
            $filesList = '';
            foreach ( $blocksArr as &$block ) {
                $files = implode ( "','", explode ( ",", $block ['files'] ) );
                $from = $block ['from'];

                $sql = "SELECT `duration`, `name` FROM `file` WHERE `id` IN ('" . $files . "');";

                $command = $connection->createCommand($sql);
                $dataReader = $command->query();

                $duration = 0;
                $block ["filesWithDuration"] = array ();
                while (($row=$dataReader->read()) != false) {
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

        return $blocksArr;
    }

    public function GetAdvContentArr($pointId, $pointChannel, $pointDatetimeStr, $weekDay) {
        $connection = Yii::app()->db;
        $connection->active=true;

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


        $command = $connection->createCommand($sql);
        $dataReader = $command->query();

        $advArr = array ();

        while (($row=$dataReader->read()) != false) {
            $fromDatetime = date_create ( $row ['fromDatetime'] );
            $toDatetime = date_create ( $row ['toDatetime'] );

            $every = $row ['every'];
            $files = $row ['files'];
            $files = implode ( "','", explode ( ",", $files ) );

            $sql = "SELECT `duration`, `name` FROM `file` WHERE `id` IN ('" . $files . "');";
            $command = $connection->createCommand($sql);
            $dataReader2 = $command->query();

            $duration = 0;
            $filesWithDuration = array ();
            while (($row2=$dataReader2->read()) != false) {
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
                    self::array_insert ( $advArr, array (
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
                    );
                }

                $curTime->add ( new DateInterval ( 'PT' . $duration . 'S' ) );
                $curTime->add ( new DateInterval ( 'PT' . $repeating . 'S' ) );
            }
        }
        return $advArr;
    }


    /**
     *
     */
    public function buildBlockStructedContent($bg, $adv)
    {
        $blockStructedContent = array();

        $lastBlock = array();
        $lastFrom = '';
        $lastBlockEndTime = new DateTime();

        if ((count ( $bg ) > 0) && (count ( $adv ) > 0)) {
            $ii = 0;
            while ( (new DateTime ( $bg [$ii] ['from'] ) < new DateTime ( $adv [0] ['from'] )) && $ii < count ( $bg ) ) {
                $bgBlock = $bg [$ii];
                $ii ++;
                $from = $bgBlock ["from"];
                $lastFrom = $from;
                $lastBlockEndTime = new DateTime($from);

                $blockStructedContent[$from] = array();

                foreach ( $bgBlock ['filesWithDuration'] as $files ) {
                    $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                    $blockStructedContent[$from][] = $files [2];
                }
                $lastBlock = $bgBlock ['filesWithDuration'];
            }

            for($jj = 0; $jj < count ( $adv ); $jj ++) {
                if (($ii > 0) && (new DateTime ( $bg [$ii - 1] ['from'] ) < new DateTime ( $adv [$jj - 1] ['from'] )) && (new DateTime ( $bg [$ii - 1] ['to'] ) > new DateTime ( $adv [$jj - 1] ['to'] ))) {
                    $bgBlock = $bg [$ii - 1];
                    $from = $adv [$jj - 1] ['to'];
                    $lastFrom = $from;
                    $lastBlockEndTime = new DateTime($from);

                    $blockStructedContent[$from] = array();

                    foreach ( $bgBlock ['filesWithDuration'] as $files ) {
                        $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                        $blockStructedContent[$from][] = $files [2];
                    }
                    $lastBlock = $bgBlock ['filesWithDuration'];
                }

                $advBlock = $adv [$jj];
                $from = $advBlock ["from"];
                $lastFrom = $from;
                $lastBlockEndTime = new DateTime($from);

                $blockStructedContent[$from] = array();

                foreach ( $advBlock ['filesWithDuration'] as $files ) {
                    $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                    $blockStructedContent[$from][] = $files [2];
                }

                if (($jj < count ( $adv ) - 1) && ($ii < count ( $bg )) && (new DateTime ( $bg [$ii] ['from'] ) < new DateTime ( $adv [$jj + 1] ['from'] ))) {
                    $bgBlock = $bg [$ii];
                    $ii ++;
                    $from = $bgBlock ["from"];
                    $lastFrom = $from;
                    $lastBlockEndTime = new DateTime($from);

                    $blockStructedContent[$from] = array();

                    foreach ( $bgBlock ['filesWithDuration'] as $files ) {
                        $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                        $blockStructedContent[$from][] = $files [2];
                    }
                    $lastBlock = $bgBlock ['filesWithDuration'];

                    $jj ++;
                    $advBlock = $adv [$jj];
                    $from = $advBlock ["from"];
                    $lastFrom = $from;
                    $lastBlockEndTime = new DateTime($from);

                    $blockStructedContent[$from] = array();

                    foreach ( $advBlock ['filesWithDuration'] as $files ) {
                        $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                        $blockStructedContent[$from][] = $files [2];
                    }
                }
            }

        } else if ((count ( $bg ) == 0) && (count ( $adv ) > 0)) {
            foreach ( $adv as $advBlock ) {
                $from = $advBlock ["from"];
                $lastFrom = $from;
                $lastBlockEndTime = new DateTime($from);
                $blockStructedContent[$from] = array();

                foreach ( $advBlock ['filesWithDuration'] as $files ) {
                    $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                    $blockStructedContent[$from][] = $files [2];
                }
                $lastBlock = $bgBlock ['filesWithDuration'];
            }

        } else if ((count ( $bg ) > 0) && (count ( $adv ) == 0)) {
            foreach ( $bg as $bgBlock ) {
                $from = $bgBlock ["from"];
                $lastFrom = $from;
                $lastBlockEndTime = new DateTime($from);
                $blockStructedContent[$from] = array();

                foreach ( $bgBlock ['filesWithDuration'] as $files ) {
                    $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                    $blockStructedContent[$from][] = $files [2];
                }
                $lastBlock = $bgBlock ['filesWithDuration'];
            }

            $midnight = new DateTime("23:59:59");
            while($lastBlockEndTime < $midnight){
                foreach ($lastBlock as $files ) {
                    $lastBlockEndTime->add(new DateInterval('PT'.ceil($files[0]).'S'));
                    $blockStructedContent[$lastFrom][] = $files [2];
                }
            }
        }

        return $blockStructedContent;
    }

    public function ConverBlockStructedToStraightTimeContent($blockContent)
    {
        $prevBlockFilesAndDurations = array();
        $straightTimeContent = array();

        foreach ($blockContent as $time => $block) {

            //check do we need cut or prolong previous block
            if(count($prevBlockFilesAndDurations) > 0) {
                //cut all movies if time gather than next block start time
                //imposible in context before buildBlockStructedContent using
                $ii = -1;
                $lastKey = key( array_slice( $straightTimeContent, $ii, 1, TRUE ) );
                while(date($lastKey) > date($time) && !is_null($lastKey)) {
                    unset($straightTimeContent[$lastKey]);
                    $ii--;
                    $lastKey = key( array_slice( $straightTimeContent, $ii, 1, TRUE ) );
                }

                //prolong previous block
                $ii = 0;
                $lastKey = key( array_slice( $straightTimeContent, -1, 1, TRUE ) );
                $lenght = $prevBlockFilesAndDurations[$ii][0];
                $file = $prevBlockFilesAndDurations[$ii][1];

                $possibleTime = new DateTime($lastKey);
                $possibleTime->add (new DateInterval ( 'PT' . ceil($length) . 'S' ));
                $possibleTime = $possibleTime->format('H:i:s');

                while((date($lastKey) < date($time)) && (date($possibleTime) < date($time))) {
                    $straightTimeContent[$possibleTime] = $file;

                    if($ii >= (count($prevBlockFilesAndDurations) - 1)) {
                        $ii = 0;
                    } else {
                        $ii++;
                    }

                    $lastKey = key( array_slice( $straightTimeContent, -1, 1, TRUE ) );
                    $lenght = $prevBlockFilesAndDurations[$ii][0];
                    $file = $prevBlockFilesAndDurations[$ii][1];

                    $possibleTime = new DateTime($lastKey);
                    $possibleTime->add (new DateInterval ( 'PT' . ceil($length) . 'S' ));
                    $possibleTime = $possibleTime->format('H:i:s');
                }
            }

            $straightTimeContent[$time] = '';

            $prevBlockFilesAndDurations = array();
            foreach ($block as $moovies) {
                $lenghtPath = explode(" ", $moovies);
                if(count($lenghtPath) > 0) {
                    $length = $lenghtPath[0];
                    $path = $lenghtPath[1];

                    $straightTimeContent[$time] = $path;
                    $prevBlockFilesAndDurations[] = $lenghtPath;

                    $time = new DateTime($time);
                    $time->add (new DateInterval ( 'PT' . floor($length) . 'S' ));
                    $time = $time->format('H:i:s');
                }
            }
        }

        return $straightTimeContent;
    }

    private static function array_insert(&$array,$element,$position=null)
    {
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
}
