<?php

/**
 * This is the model class for table "file_to_folder".
 *
 * The followings are the available columns in table 'file_to_folder':
 * @property integer $id
 * @property integer $id_file
 * @property integer $id_folder
 * @property string $id_author
 * @property string $dt
 */
class FileToFolder extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'file_to_folder';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_file, id_folder, id_author', 'required'),
            array('dt', 'default',
                    'value' => new CDbExpression('NOW()'),
                    'setOnEmpty'=>false,'on'=>'insert'),
            array('id_file, id_folder', 'numerical', 'integerOnly'=>true),
            array('id_author', 'length', 'max'=>100),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_file' => 'Id File',
            'id_folder' => 'Id Folder',
            'id_author' => 'Id Author',
            'dt' => 'Dt',
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
        $criteria->compare('id_file',$this->id_file);
        $criteria->compare('id_folder',$this->id_folder);
        $criteria->compare('id_author',$this->id_author,true);
        $criteria->compare('dt',$this->dt,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FileToFolder the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
