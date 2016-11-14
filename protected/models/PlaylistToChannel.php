<?php

/**
 * This is the model class for table "playlist_to_channel".
 *
 * The followings are the available columns in table 'playlist_to_channel':
 * @property integer $channelId
 * @property integer $playlistId
 *
 * The followings are the available model relations:
 * @property Playlists $playlist
 * @property Channel $channel
 */
class PlaylistToChannel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'playlist_to_channel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('channelId, playlistId', 'required'),
			array('channelId, playlistId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('channelId, playlistId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'channel' => array(self::BELONGS_TO, 'Channel', 'channelId'),
			'playlist' => array(self::HAS_ONE, 'Playlists', 'playlistId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'channelId' => 'Channel',
			'playlistId' => 'Playlist',
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

		$criteria->compare('channelId',$this->channelId);
		$criteria->compare('playlistId',$this->playlistId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Playlisttochannel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function AddPlaylistToChannel($channelId, $plId)
	{
		$_channelId = intval($channelId);
		$_plId = intval($plId);
		
		$model = new PlaylistToChannel();
		$model->attributes = array(
			'channelId'=>$_channelId,
			'playlistId'=>$_plId
		);
		
		return $model->save();
	}
	
	public function RemovePlaylistFromChannel($channelId, $plId)
	{
		$_channelId = intval($channelId);
		$_plId = intval($plId);
			
		return self::model()->deleteAllByAttributes(array(
			'channelId'=>$_channelId,
	        'playlistId'=>$_plId
		));
	}
}
