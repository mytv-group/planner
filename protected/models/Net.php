<?php

/**
 * This is the model class for table "net".
 *
 * The followings are the available columns in table 'net':
 * @property integer $id
 * @property string $name
 * @property integer $id_user
 * @property string $dt_created
 *
 * The followings are the available model relations:
 * @property User $idUser
 * @property PointToNet[] $pointToNets
 */
class Net extends CActiveRecord
{
  public $attachedPoints;
  public $availablePoints;
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'net';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name, id_user', 'required'),
      array('id_user', 'numerical', 'integerOnly'=>true),
      array('name', 'length', 'max'=>255),
      array('dt_created', 'safe'),
      array('name', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'user' => array(self::BELONGS_TO, 'User', 'id_user'),
      'pointsToNet' => array(self::HAS_MANY, 'PointToNet', 'id_net'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'name' => 'Name',
      'id_user' => 'User',
      'dt_created' => 'Date created',
      'options' => 'Options',
      'attachedPoints' => 'Attached points',
      'TVschedule' => 'TV turn on Schedule',
      'channels' => 'Channels',
      'screen_id' => "Screen",
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
    $criteria=new CDbCriteria;

    $criteria->compare('name', $this->name, true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => [
          'pageSize'=> 10,
      ]
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Net the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }
}
