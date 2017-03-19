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
            array('name, type, extension, link, date_created', 'required'),
            array('type', 'length', 'max'=>1),
            array('extension', 'length', 'max'=>10),
            array('link', 'length', 'max'=>255),
            array('date_modified', 'safe'),
            array('name', 'safe', 'on'=>'search'),
        );
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
