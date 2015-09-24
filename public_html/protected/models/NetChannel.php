<?php

/**
 * This is the model class for table "net_channel".
 *
 * The followings are the available columns in table 'net_channel':
 * @property integer $id
 * @property integer $net_id
 * @property integer $window_id
 *
 * The followings are the available model relations:
 * @property Window $window
 * @property Net $net
 */
class NetChannel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'net_channel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('net_id, window_id', 'required'),
			array('net_id, window_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, net_id, window_id', 'safe', 'on'=>'search'),
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
			'window' => array(self::BELONGS_TO, 'Window', 'window_id'),
			'playlistToChannel' => array(self::HAS_MANY, 'PlaylistToNetChannel', 'net_channel_id'),
			'playlists'=>array(self::HAS_MANY,'Playlists',array('playlist_id'=>'id'),'through'=>'playlistToChannel'),
			'net' => array(self::BELONGS_TO, 'Net', 'net_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'net_id' => 'Net',
			'window_id' => 'Window',
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
		$criteria->compare('net_id',$this->net_id);
		$criteria->compare('window_id',$this->window_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NetChannel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
