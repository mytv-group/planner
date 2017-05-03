<?php

/**
 * This is the model class for table "point".
 *
 * The followings are the available columns in table 'point':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $sync_time
 * @property string $update_time
 * @property integer $volume
 * @property integer $TV
 * @property integer $channels
 * @property integer $id_user
 */
class Point extends CActiveRecord
{
    public $content;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'point';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, username, ip, screen_id', 'required'),
            array('volume, TV, screen_id, id_user', 'numerical', 'integerOnly'=>true),
            array('name, username', 'length', 'max'=>255),
            array('sync', 'boolean'),
            array('sync', 'default',
                    'value'=> false,
                    'setOnEmpty' => true, 'on'=>'insert'),
            array('id_user', 'default',
                    'value'=> Yii::app()->user->id,
                    'setOnEmpty' => true, 'on'=>'insert'),
            array('update_time', 'default',
                    'value' => new CDbExpression('NOW()'),
                    'setOnEmpty'=>false,'on'=>'insert'),
            array('update_time', 'default',
                    'value' => new CDbExpression('NOW()'),
                    'setOnEmpty' => false, 'on'=>'update'),
            array('content', 'file', 'types'=>'zip', 'allowEmpty'=>true),
            array('name', 'safe', 'on'=>'search'),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'showcases'=>array(self::HAS_MANY, 'Showcase', 'id_point'),
            'widgets'=>array(self::HAS_MANY, 'Widget', ['id_widget'=>'id'],'through'=>'showcases'),
            'playlistToPoint'=>array(self::HAS_MANY,'PlaylistToPoint', ['id_point' => 'id']),
            'playlists'=>array(self::HAS_MANY,'Playlists', ['id_playlist'=>'id'],'through'=>'playlistToPoint'),
            'pointToNet'=>array(self::HAS_MANY, 'PointToNet', 'id_point'),
            'net'=>array(self::HAS_MANY, 'Net', ['id_net'=>'id'],'through'=>'pointToNet'),
            'screen'=>array(self::BELONGS_TO, 'Screen', 'screen_id'),
            'tv'=>array(self::HAS_MANY, 'TvSchedule', 'id_point')
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
            'username' => 'Username',
            'password' => 'Password',
            'ip' => 'Point IP Address',
            'sync_time' => 'Last Success Sync Time',
            'update_time' => 'Last Update Time',
            'volume' => 'Volume',
            'free_space' => 'Free Space',
            'TV' => 'TV hardware turning',
            'TVschedule' => 'TV turn on Schedule',
            'channels' => 'Channels',
            'sync' => 'Syncronized',
            'screen_id' => "Screen",
            'ctrl' => "Controls"
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('name',$this->name,true);
        $criteria->compare('ip',$this->ip,true);

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $criteria->compare('id_user', Yii::app()->user->id);
        }

        $answ = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        return $answ;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Point the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function GetPointPlaylistNames($id)
    {
        $model = self::model()->findByPk($id);
        $connection = Yii::app()->db;
        $connection->active=true;

        $avaliableChannels = $model->channels;
        $playlists = array();

        if(strlen($avaliableChannels) > 0)
        {
            $sql = "SELECT `id`, `name` FROM `playlists` WHERE `id` IN (".$avaliableChannels.");";

            $command = $connection->createCommand($sql);
            $dataReader=$command->query();
            while(($row=$dataReader->read())!==false)
            {
                $playlists[] = array(
                        'id' => $row['id'],
                        'name' => $row['name']
                );
            }
        }

        $connection->active=false;
        return $playlists;
    }

    public function GetUserAvaliablePlaylist()
    {
        $userName = Yii::app()->user->name;
        $connection = Yii::app()->db;
        $connection->active=true;

        $playlists = array();

        $sql = "SELECT `id`, `name`, `fromDatetime`, `toDatetime`, `fromTime`,`toTime`, ".
            "`sun`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat` FROM `playlists` WHERE `author` = '".$userName."';";

        $command = $connection->createCommand($sql);
        $dataReader=$command->query();
        while(($row=$dataReader->read())!==false)
        {
            $weedDays = "";
            if($row['sun']) { $weedDays .= 'Sun '; }
            if($row['mon']) { $weedDays .= 'Mon '; }
            if($row['tue']) { $weedDays .= 'Tue '; }
            if($row['wed']) { $weedDays .= 'Wed '; }
            if($row['thu']) { $weedDays .= 'Thu '; }
            if($row['fri']) { $weedDays .= 'Fri '; }
            if($row['sat']) { $weedDays .= 'Sat '; }

            $playlists[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'fromDatetime' => $row['fromDatetime'],
                'toDatetime' => $row['toDatetime'],
                'fromTime' => $row['fromTime'],
                'toTime' => $row['toTime'],
                'weekDays' => $weedDays
            );
        }

        $connection->active=false;
        return $playlists;
    }

    private function PrepareSpoolPath($extPathAppendix)
    {
        $pathAppendix = $extPathAppendix;
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
}
