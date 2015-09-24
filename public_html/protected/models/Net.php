<?php

/**
 * This is the model class for table "net".
 *
 * The followings are the available columns in table 'net':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 * @property PointToNet[] $pointToNets
 */
class Net extends CActiveRecord
{
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
			array('name, pointsattached, screen_id, user_id', 'required'),
			array('user_id, screen_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'channels'=>array(self::HAS_MANY, 'NetChannel', 'net_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'screen' => array(self::BELONGS_TO, 'Screen', 'screen_id'),
			'windows' => array(self::HAS_MANY, 'Window', 'screen_id'),
			'pointstonets' => array(self::HAS_MANY, 'PointToNet', array('net_id' => 'id')),
			'pointsattached' => array(self::HAS_MANY, 'Point', array('point_id' => 'id'), 'through'=>'pointstonets'),
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
			'screen_id' => 'Screen',
			'user_id' => 'User',
			'user' => 'Author',	
			'pointsattached' => 'Attached Points',
			'pointsavaliable' => 'Avaliable Points'
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	
	public function CreateChannelsForWindows($screenId, $netId)
	{
		$Screen = Screen::model()->findByPk($screenId);
		$windows = $Screen->windows;
	
		NetChannel::model()->deleteAll("net_id = :net_id", array('net_id' => $netId));
	
		foreach($windows as $window)
		{
			$channel = new NetChannel;
	
			$channel->attributes = array(
					'net_id' => $netId,
					'window_id' => $window->id,
			);
				
			$channel->save();
		}
	}
}
