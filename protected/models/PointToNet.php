<?php

/**
 * This is the model class for table "point_to_net".
 *
 * The followings are the available columns in table 'point_to_net':
 * @property integer $id
 * @property integer $id_point
 * @property integer $id_net
 * @property string $dt_created
 *
 * The followings are the available model relations:
 * @property Net $idNet
 * @property Point $idPoint
 */
class PointToNet extends CActiveRecord
{
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'point_to_net';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return [
      ['id_point, id_net', 'required'],
      ['id_point, id_net', 'numerical', 'integerOnly'=>true],
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
      'net' => [self::BELONGS_TO, 'Net', 'id_net'],
      'point' => [self::BELONGS_TO, 'Point', 'id_point'],
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
      'id_net' => 'Id Net',
      'dt_created' => 'Dt Created',
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
    $criteria->compare('id_net',$this->id_net);
    $criteria->compare('dt_created',$this->dt_created,true);

    return new CActiveDataProvider($this, [
      'criteria'=>$criteria,
    ]);
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return PointToNet the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function saveArray ($netId, $attachedPoints)
  {
    $arr = [];
    if(is_array($attachedPoints)) {
        $arr = $attachedPoints;
    }

    self::model()->deleteAll(
        "`id_net` = :id_net",
        [':id_net' => $netId]
    );

    foreach ($arr as $item) {
        $model = new PointToNet;
        $model->attributes = [
            'id_point' => intval($item),
            'id_net' => $netId
        ];
        $model->save();
    }
  }
}
