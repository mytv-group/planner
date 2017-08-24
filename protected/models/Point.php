<?php

/**
 * This is the model class for table "point".
 *
 * The followings are the available columns in table 'point':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $sync_time
 * @property string $update_time
 * @property integer $volume
 * @property integer $TV
 * @property integer $channels
 * @property integer $id_user
 */
class Point extends CActiveRecord
{
    public $content;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'point';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, username, ip, screen_id', 'required'),
            array('volume, TV, screen_id, id_user', 'numerical', 'integerOnly'=>true),
            array('name, username', 'length', 'max'=>255),
            array('sync', 'boolean'),
            array('sync', 'default',
                    'value'=> false,
                    'setOnEmpty' => true, 'on'=>'insert'),
            array('id_user', 'default',
                    'value'=> Yii::app()->user->id,
                    'setOnEmpty' => true, 'on'=>'insert'),
            array('update_time', 'default',
                    'value' => new CDbExpression('NOW()'),
                    'setOnEmpty'=>false,'on'=>'insert'),
            array('update_time', 'default',
                    'value' => new CDbExpression('NOW()'),
                    'setOnEmpty' => false, 'on'=>'update'),
            array('content', 'file', 'types'=>'zip', 'allowEmpty'=>true),
            array('name', 'safe', 'on'=>'search'),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'showcases'=>array(self::HAS_MANY, 'Showcase', 'id_point'),
            'widgets'=>array(self::HAS_MANY, 'Widget', ['id_widget'=>'id'],'through'=>'showcases'),
            'playlistToPoint'=>array(self::HAS_MANY,'PlaylistToPoint', ['id_point' => 'id']),
            'playlists'=>array(self::HAS_MANY,'Playlists', ['id_playlist'=>'id'],'through'=>'playlistToPoint'),
            'pointToNet'=>array(self::HAS_MANY, 'PointToNet', 'id_point'),
            'net'=>array(self::HAS_MANY, 'Net', ['id_net'=>'id'],'through'=>'pointToNet'),
            'screen'=>array(self::BELONGS_TO, 'Screen', 'screen_id'),
            'tv'=>array(self::HAS_MANY, 'TvSchedule', 'id_point')
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
            'username' => 'Username',
            'password' => 'Password',
            'ip' => 'Point IP Address',
            'sync_time' => 'Last Success Sync Time',
            'update_time' => 'Last Update Time',
            'volume' => 'Volume',
            'free_space' => 'Free Space',
            'TV' => 'TV hardware turning',
            'TVschedule' => 'TV turn on Schedule',
            'channels' => 'Channels',
            'sync' => 'Syncronized',
            'status' => 'Status',
            'screen' => 'Screen',
            'screen_id' => "Screen",
            'ctrl' => "Controls"
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('ip',$this->ip,true);

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $criteria->compare('id_user', Yii::app()->user->id);
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function searchWithoutContent()
    {
        $pointCriteria=new CDbCriteria;

        if (Yii::app()->user->role != User::ROLE_ADMIN) {
            $pointCriteria->compare('id_user', Yii::app()->user->id);
        }

        $allPoints = Point::model()->findAll($pointCriteria);
        $points = [];

        foreach ($allPoints as $point) {
            $playlists = $point->playlists;

            $count = count($playlists);
            $expired = 0;
            foreach ($playlists as $playlist) {
                if (date_create($playlist->toDatetime) < new DateTime('now')) {
                    $expired++;
                }
            }

            if ($count === $expired) {
                $points[] = $point;
            }
        }

        return new CArrayDataProvider($points, array(
            'keyField' => 'id',
            'sort' => array(
                'attributes' => array(
                    'id' => array(
                        'asc'=>'id',
                        'desc'=>'id DESC',
                        'label' => 'ID'
                    ),
                    'name' => array(
                        'asc'=>'name',
                        'desc'=>'name DESC',
                        'label' => 'Name'
                    ),
                    'ip' => array(
                        'asc'=>'ip',
                        'desc'=>'ip DESC',
                        'label' => 'IP'
                    ),
                ),
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Point the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
