<?php

/**
 * This is the model class for table "playlist_to_window".
 *
 * The followings are the available columns in table 'playlist_to_window':
 * @property integer $id
 * @property integer $playlist_id
 * @property integer $window_id
 * @property string $time
 *
 * The followings are the available model relations:
 * @property Playlists $playlist
 * @property Window $window
 */
class PlaylistToWindow extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'playlist_to_window';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('playlist_id, window_id, time', 'required'),
			array('playlist_id, window_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, playlist_id, window_id, time', 'safe', 'on'=>'search'),
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
			'playlist' => array(self::BELONGS_TO, 'Playlists', 'playlist_id'),
			'window' => array(self::BELONGS_TO, 'Window', 'window_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'playlist_id' => 'Playlist',
			'window_id' => 'Window',
			'time' => 'Time',
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
		$criteria->compare('playlist_id',$this->playlist_id);
		$criteria->compare('window_id',$this->window_id);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlaylistToWindow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
