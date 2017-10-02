<?php

/**
 * This is the model class for table "channel".
 */
class Showcase extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'showcase';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['id_point, id_window, id_widget', 'required'],
            ['id_point, id_window, id_widget', 'numerical', 'integerOnly'=>true],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'point' => [self::BELONGS_TO, 'Point', 'id_point'],
            'window' => [self::BELONGS_TO, 'Window', 'id_window'],
            'widget' => [self::BELONGS_TO, 'Widget', 'id_widget'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_point' => 'Id Point',
            'window_id' => 'Id Window',
            'playlists' => 'Playlists',
            'adv' => 'Adv',
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('id_point',$this->id_point);
        $criteria->compare('window_id',$this->window_id);

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Channel the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
