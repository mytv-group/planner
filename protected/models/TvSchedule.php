<?php

/**
 * This is the model class for table "tvschedule".
 *
 * The followings are the available columns in table 'tvschedule':
 * @property integer $id
 * @property integer $point_id
 * @property string $from
 * @property string $to
 * @property string $author
 */
class TvSchedule extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tvschedule';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['id_point, dt_from, dt_to, id_user', 'required'],
            ['id_point, id_user', 'numerical', 'integerOnly'=>true],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_point' => 'Point',
            'dt_from' => 'From',
            'dt_to' => 'To',
            'id_user' => 'Author',
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
        $criteria->compare('point_id',$this->point_id);
        $criteria->compare('from',$this->from,true);
        $criteria->compare('to',$this->to,true);
        $criteria->compare('author',$this->author,true);

        return new CActiveDataProvider($this, [
          'criteria'=>$criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TvSchedule the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
