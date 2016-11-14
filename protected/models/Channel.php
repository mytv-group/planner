<?php

/**
 * This is the model class for table "channel".
 *
 * The followings are the available columns in table 'channel':
 * @property integer $id
 * @property integer $id_point
 * @property integer $layout
 * @property integer $top
 * @property integer $left
 * @property integer $height
 * @property integer $width
 * @property string $playlists
 * @property string $adv
 *
 * The followings are the available model relations:
 * @property Point $idPoint
 * @property Point[] $points
 */
class Channel extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'channel';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_point, internalId', 'required'),
            array('id_point, internalId, window_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id_point', 'safe', 'on'=>'search'),
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
            'idPoint' => array(self::BELONGS_TO, 'Point', 'id_point'),
            'playlistToChannel' => array(self::HAS_MANY, 'PlaylistToChannel', 'channelId'),
            'playlists'=>array(self::HAS_MANY,'Playlists',array('playlistId'=>'id'),'through'=>'playlistToChannel'),
            'window' => array(self::BELONGS_TO, 'Window', 'window_id'),
            'widgetToChannel' => array(self::HAS_ONE, 'WidgetToChannel', 'channel_id'),
            'widget' => array(self::HAS_ONE, 'Widget', array('widget_id'=>'id'),'through'=>'widgetToChannel'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_point' => 'Id Point',
            'window_id' => 'Id Window',
            'playlists' => 'Playlists',
            'adv' => 'Adv',
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
        $criteria->compare('id_point',$this->id_point);
        $criteria->compare('window_id',$this->window_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
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

    public function AddChannelToPoint($pointId)
    {
        $_pointId = $pointId;
        $model = new Channel();

        $res = Channel::model()->findAll('id_point=:id_point ORDER BY internalId ASC', array(':id_point'=>$_pointId));

        $internalId = 1;
        if(count($res) > 0)
        {
            $internalId = $res[count($res) - 1]['internalId'] + 1;
        }

        $model->attributes = array(
                'id_point'=>$_pointId,
                'internalId'=>$internalId
        );

        if($model->validate() && $model->save())
        {
            $id = $model->getPrimaryKey();
            return array(
                "status" => true,
                "id" => $id,
                "internalId" => $internalId
            );
        }
        else
        {
            return array(
                "status" => false,
                "error"  => json_encode($model->getErrors())
            );
        }
    }

    public function RemoveChannel($id)
    {
        $this::model()->deleteByPk($id);
        return array(
            "status" => true,
        );


    }
}
