<?php

/**
 * This is the model class for table "file_to_playlist".
 *
 * The followings are the available columns in table 'file_to_playlist':
 * @property integer $id
 * @property integer $id_file
 * @property integer $id_playlist
 */
class FileToPlaylist extends CActiveRecord
{
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'file_to_playlist';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return [
      ['id_file, id_playlist', 'required'],
      ['id_file, id_playlist', 'numerical', 'integerOnly'=>true],
    ];
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'file' => array(self::BELONGS_TO, 'File', array('id' => 'id_file')),
      'playlist' => array(self::BELONGS_TO, 'Playlists', array('id' => 'id_playlist')),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'id_file' => 'Id File',
      'id_playlist' => 'Id Playlist'
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

    $criteria->compare('id',$this->id);
    $criteria->compare('id_file',$this->id_file);
    $criteria->compare('id_playlist',$this->id_playlist);

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
