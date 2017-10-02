<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property integer $type
 * @property integer $blocked
 */
class User extends CActiveRecord
{
   const ROLE_ADMIN = 'admin';
   const ROLE_MODER = 'moderator';
//   const ROLE_USER = 'user';
//   const ROLE_BANNED = 'banned';

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
      return 'user';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return [
      ['username, password, name, role', 'required'],
      ['blocked', 'numerical', 'integerOnly'=>true],
      ['username', 'length', 'max'=>100],
      ['password, name', 'length', 'max'=>255],
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      ['id, username, password, name, blocked', 'safe', 'on'=>'search'],
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
      'pointtouser' => [self::HAS_MANY, 'PointToUser', ['user_id' => 'id']],
      'pointsavaliable' => [self::HAS_MANY, 'Point', ['point_id' => 'id'], 'through'=>'pointtouser'],
      'screens' => [self::HAS_MANY, 'Screen', ['user_id' => 'id']],
    ];
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'username' => 'Username',
      'password' => 'Password',
      'password2' => 'Repeat password',
      'name' => 'Name',
      'blocked' => 'Blocked',
      'role' => 'Role',
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
    $criteria->compare('username',$this->username,true);
    $criteria->compare('password',$this->password,true);
    $criteria->compare('name',$this->name,true);
    $criteria->compare('blocked',$this->blocked);
    $criteria->compare('role',$this->role);

    $activeRecord = new CActiveDataProvider($this, [
      'criteria'=>$criteria,
    ]);
    return $activeRecord;
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return User the static model class
   */
  public static function model($className=__CLASS__)
  {
      return parent::model($className);
  }

  protected function beforeSave(){
      $this->password = md5($this->password);
      return parent::beforeSave();
  }
}
