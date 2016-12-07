<?php

/**
 * This is the model class for table "playlist_to_point".
 *
 * The followings are the available columns in table 'playlist_to_point':
 * @property integer $id
 * @property integer $id_point
 * @property integer $id_playlist
 * @property integer $channel_type
 */
class PlaylistToPoint extends CActiveRecord
{
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'playlist_to_point';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('id_point, id_playlist, channel_type', 'required'),
      array('id_point, id_playlist, channel_type', 'numerical', 'integerOnly'=>true),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, id_point, id_playlist, channel_type', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'playlist' => array(self::HAS_ONE, 'Playlists', array('id' => 'id_playlist')),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'id_point' => 'Id Point',
      'id_playlist' => 'Id Playlist',
      'channel_type' => 'Channel Type',
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
    $criteria->compare('id_point',$this->id_point);
    $criteria->compare('id_playlist',$this->id_playlist);
    $criteria->compare('channel_type',$this->channel_type);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return PlaylistToPoint the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }
}
