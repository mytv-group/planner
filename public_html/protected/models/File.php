<?php

class File extends CActiveRecord
{

	public function tableName()
	{
		return 'file';
	}

	public function rules()
	{

		return array(
			array('id_camp, name, type, extension, link, date_created', 'required'),
			array('type', 'length', 'max'=>1),
			array('extension', 'length', 'max'=>10),
			array('link', 'length', 'max'=>255),
			array('date_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, duration, extension, mime, link, date_created, date_modified', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'camp' => array(self::BELONGS_TO, 'Camp', 'id_camp'),
            'net' => array(self::BELONGS_TO, 'Net', 'id_net'),
            //'channels' => array(self::MANY_MANY, 'Channel1', 'added(file_id, channel1_id)'),
            'added' => array(self::HAS_MANY, 'Added', 'file_id')
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_camp' => 'Campaign',
			'name' => 'Name',
			'type' => 'Type',
            'duration' => 'Duration',
			'extension' => 'Extension',
            'mime' => 'Mime type',
			'link' => 'Link',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('mime',$this->mime,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
