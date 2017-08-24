<?php

/**
 * This is the model class for table "window".
 *
 * The followings are the available columns in table 'window':
 * @property integer $id
 * @property string $name
 * @property integer $top
 * @property integer $left
 * @property integer $width
 * @property integer $height
 * @property integer $authorId
 *
 * The followings are the available model relations:
 * @property WindowToScreen[] $windowToScreens
 */
class Window extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'window';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('screen_id, name, top, left, width, height, authorId', 'required'),
            array('screen_id, top, left, width, height, authorId', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>25),
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
            'showcases' => array(self::HAS_MANY, 'Showcase', 'id_window'),
            'widget' => array(self::HAS_ONE, 'Widget', ['id_widget'=>'id'],'through'=>'showcases'),
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
            'top' => 'Top',
            'left' => 'Left',
            'width' => 'Width',
            'height' => 'Height',
            'authorId' => 'Author',
        );
    }

    public function getInfo() {
        if (!is_int(intval($this->top))
            || !is_int(intval($this->left))
            || !is_int(intval($this->width))
            || !is_int(intval($this->height))
        ) {
            throw new Error (implode('',
                [ __CLASS__, ' contains corrupted data ', $this->top, ', ',
                    $this->left, ', ', $this->width, ', ', $this->height, '.']
            ));
        }

        return [
            'top' => $this->top,
            'left' => $this->left,
            'width' => $this->width,
            'height' => $this->height
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
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Window the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
