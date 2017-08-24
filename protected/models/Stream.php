<?php

/**
 * This is the model class for table "stream".
 *
 * The followings are the available columns in table 'stream':
 * @property integer $id
 * @property integer $playlist_id
 * @property string $url
 * @property string $created_dt
 */
class Stream extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'stream';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('playlist_id, url', 'required'),
            array('playlist_id', 'numerical', 'integerOnly'=>true),
            array('url', 'length', 'min'=>5),
            array('url', 'length', 'max'=>255),
            array('created_dt', 'safe'),
            array('id, url', 'safe', 'on'=>'search'),
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
            'playlist_id' => 'Playlist',
            'url' => 'Url',
            'created_dt' => 'Created Dt',
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
        $criteria->compare('url',$this->url,true);
        $criteria->compare('created_dt',$this->created_dt,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Stream the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
