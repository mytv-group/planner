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
            ['name, duration, size, mime, path, link, visibility, id_user', 'required'],
            ['visibility, id_user', 'numerical', 'integerOnly'=>true],
            ['duration', 'length', 'max'=>20],
            ['date_created', 'default', 'value' => date('Y-m-d H:i:s')],
            ['mime', 'length', 'max'=>100],
            ['path, link', 'length', 'max'=>255],
            ['name', 'safe', 'on'=>'search'],
        ];
    }

    public function relations()
    {
        return [
            'tagsToFiles' => [self::HAS_MANY, 'TagToFile', 'id_file'],
            'tags'=>[self::HAS_MANY, 'Tag', ['id_tag'=>'id'],'through'=>'tagsToFiles'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'duration' => 'Duration',
            'extension' => 'Extension',
            'mime' => 'Mime type',
            'link' => 'Link',
            'date_created' => 'Date Created'
        ];
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, [
          'criteria'=>$criteria,
        ]);
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
