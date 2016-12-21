<?php

/**
 * This is the model class for table "statistic".
 *
 * The followings are the available columns in table 'statistic':
 * @property integer $id
 * @property string $dt_playback
 * @property double $duration
 * @property integer $channel
 * @property string $file_name
 * @property integer $id_file
 * @property integer $id_playlist
 * @property integer $id_author
 * @property string $dt
 */
class Statistic extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'statistic';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, dt_playback, duration, channel, file_name, id_file, id_playlist, id_author', 'required'),
            array('id, channel, id_file, id_playlist, id_author', 'numerical', 'integerOnly'=>true),
            array('duration', 'numerical'),
            array('file_name', 'length', 'max'=>45),
            array('dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, dt_playback, duration, channel, file_name, id_file, id_playlist, id_author, dt', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'point'=>array(self::HAS_ONE, 'Point', array('id'=>'id_point')),
            'playlist'=>array(self::HAS_ONE, 'Playlists', array('id'=>'id_playlist')),
            'file'=>array(self::HAS_ONE, 'File', 'id_file')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'dt_playback' => 'Playback datetime',
            'duration' => 'Duration',
            'channel' => 'Channel',
            'file_name' => 'File Name',
            'id_file' => 'Id File',
            'id_playlist' => 'Id Playlist',
            'id_author' => 'Id Author',
            'dt' => 'Dt',
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

        $criteria->compare('id',$this->id);
        $criteria->compare('dt_playback',$this->dt_playback,true);
        $criteria->compare('duration',$this->duration);
        $criteria->compare('channel',$this->channel);
        $criteria->compare('file_name',$this->file_name,true);
        $criteria->compare('id_file',$this->id_file);
        $criteria->compare('id_playlist',$this->id_playlist);
        $criteria->compare('id_author',$this->id_author);
        $criteria->compare('dt',$this->dt,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'Pagination' => array (
                 'PageSize' => 100
             ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Statistic the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
