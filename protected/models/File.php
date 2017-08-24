<?php

class File extends CActiveRecord
{
    public function tableName()
    {
        return 'file';
    }

    public function rules()
    {
        return [
            array('name, duration, mime, path, link, visibility, id_user', 'required'),
            array('visibility, id_user', 'numerical', 'integerOnly'=>true),
            array('duration', 'length', 'max'=>20),
            array('date_created', 'default', 'value' => date('Y-m-d H:i:s')),
            array('mime', 'length', 'max'=>100),
            array('path, link', 'length', 'max'=>255),
            array('name', 'safe', 'on'=>'search'),
        ];
    }

    public function relations()
    {
        return [];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'duration' => 'Duration',
            'extension' => 'Extension',
            'mime' => 'Mime type',
            'link' => 'Link',
            'date_created' => 'Date Created'
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
          'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
