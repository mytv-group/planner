<?php

/**
 * This is the model class for table "playlists".
 *
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
        return [
            ['name, fromDatetime, toDatetime, type, order, fromTime, toTime, id_user', 'required'],
            ['name', 'length', 'max'=>100],
            ['sun, mon, tue, wed, thu, fri, sat', 'boolean'],
            ['type','in','range'=>['1','2','3'],'allowEmpty'=>false],
            ['order','in','range'=>['ASC','DESC','RANDOM'],'allowEmpty'=>false],
            ['fromDatetime, toDatetime', 'type', 'type'=>'date', 'dateFormat'=>'yyyy-MM-dd'],
            ['fromTime, toTime, every', 'type', 'type'=>'time', 'timeFormat'=>'hh:mm:ss'],
            ['name', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'stream' => [self::HAS_ONE, 'Stream', 'playlist_id'],
            'filesToPlaylist'=>[self::HAS_MANY, 'FileToPlaylist', ['id_playlist' => 'id']],
            'relatedFiles'=>[self::HAS_MANY, 'File', ['id_file'=>'id'],'through'=>'filesToPlaylist'],
            'playlistToPoint'=>[self::HAS_MANY,'PlaylistToPoint', ['id_playlist' => 'id']],
            'points'=>[self::HAS_MANY,'Point', ['id_point'=>'id'],'through'=>'playlistToPoint'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fromDatetime' => 'From Date',
            'toDatetime' => 'To Date',
            'type' => 'Type',
            'order' => 'Order',
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
            'id_user' => 'Author',
            'weekDays' => 'Week Days',
            'ctrl' => 'Controls'
        ];
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
            $criteria->compare('id_user', Yii::app()->user->id);
        }

        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
        ]);
    }

    public function searchByExpiration($expirationTo, $expirationFrom = null)
    {
        $criteria=new CDbCriteria;

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $criteria->compare('id_user', Yii::app()->user->id);
        }

        $expirationToExpression = new CDbExpression($expirationTo);
        $criteria->addCondition('`toDatetime` < '.$expirationToExpression);

        if ($expirationFrom !== null) {
            $expirationFromExpression = new CDbExpression($expirationFrom);
            $criteria->addCondition('`toDatetime` >= '.$expirationFromExpression);
        }

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
        ]);
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
}
