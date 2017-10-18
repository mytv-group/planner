<?php

/**
 * This is the model class for table "tag_to_file".
 *
 * The followings are the available columns in table 'tag_to_file':
 * @property integer $id
 * @property integer $id_tag
 * @property string $id_file
 * @property string $dt
 *
 * The followings are the available model relations:
 * @property File $idFile
 * @property Tag $idTag
 */
class TagToFile extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tag_to_file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['id_tag, id_file', 'required'],
            ['id_tag', 'numerical', 'integerOnly'=>true],
            ['id_file', 'length', 'max'=>20],
            ['dt', 'type', 'type'=>'datetime', 'datetimeFormat'=>'YYYY-mm-dd HH:ii:ss'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'file' => [self::BELONGS_TO, 'File', 'id_file'],
            'tag' => [self::BELONGS_TO, 'Tag', 'id_tag'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tag' => 'Id Tag',
            'id_file' => 'Id File',
            'dt' => 'Dt',
        ];
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

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TagToFile the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
