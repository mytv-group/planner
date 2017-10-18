<?php

/**
 * This is the model class for table "tag".
 *
 * The followings are the available columns in table 'tag':
 * @property integer $id
 * @property string $name
 * @property string $dt
 * @property integer $id_user
 *
 * The followings are the available model relations:
 * @property User $idUser
 * @property TagToFile[] $tagToFiles
 */
class Tag extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tag';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name, id_user', 'required'],
            ['id_user', 'numerical', 'integerOnly'=>true],
            ['name', 'length', 'max'=>255],
            ['dt', 'type', 'type'=>'datetime', 'datetimeFormat'=>'YYYY-mm-dd HH:ii:ss'],
            ['name', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'id_user'],
            'tagToFiles' => [self::HAS_MANY, 'TagToFile', 'id_tag'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'dt' => 'Dt',
            'id_user' => 'Id User',
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

        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tag the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
