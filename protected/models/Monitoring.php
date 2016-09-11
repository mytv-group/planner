<?php

/**
 * This is the model class for table "point".
 *
 * The followings are the available columns in table 'point':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $ip
 * @property string $sync_time
 * @property string $update_time
 * @property integer $volume
 * @property integer $TV
 * @property string $tv_schedule_blocks
 * @property integer $sync
 *
 * The followings are the available model relations:
 * @property Channel[] $channels
 */
class Monitoring extends CModel
{

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
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
			'ip' => 'Ip',
			'sync_time' => 'Sync Time',
			'update_time' => 'Update Time',
			'free_space' => 'Free Space',
			'volume' => 'Volume',
			'status' => 'Status',
			'screen' => 'Screen',
			'preview' => 'Preview',
			'TV' => 'Tv',
			'tv_schedule_blocks' => 'Tv Schedule Blocks',
			'sync' => 'Sync',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('sync_time',$this->sync_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('volume',$this->volume);
		$criteria->compare('TV',$this->TV);
		$criteria->compare('tv_schedule_blocks',$this->tv_schedule_blocks,true);
		$criteria->compare('sync',$this->sync);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function attributeNames()
	{
		parent::attributeNames();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Monitoring the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function prapareScreenshot($file, $destPath, $url)
	{
		
		if(strpos($file, 'screenshot') > -1)
		{
			$nameParts = explode("_", $file);
			$timestamp = intval($nameParts[1]);
			if($timestamp != 0) //somescript uploading img from point
			{
				if($timestamp < (time() - 100))// img uploaded gather than 100 secconds ago
				{
					$newFileName = str_replace(strval($timestamp), "0", $file);
					$imgDest = $destPath.DIRECTORY_SEPARATOR.$newFileName;
					rename ($destPath.DIRECTORY_SEPARATOR.$file, $imgDest);
					$answ = $this->receiveScreenshot($destPath, $url);
					unlink($destPath.DIRECTORY_SEPARATOR.$newFileName);
					return $answ;
				}
			}
			else 
			{
				return false;
			}
		}			
		else 
		{
			return false;
		}
	}
	
	public function receiveScreenshot($destPath, $url)
	{
		$imgDest = $destPath.'/screenshot_'.time().'.jpg';
		try
		{
			file_put_contents($imgDest, file_get_contents($url));
			$imgUrl = str_replace(YiiBase::getPathOfAlias('webroot'), Yii::app()->baseUrl, $imgDest);
			return $imgUrl;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function checkIpOnline($ip)
	{
		$appendPath = '/spool/vpn-stat.txt';
		$contentPath = YiiBase::getPathOfAlias('webroot');
		$vpnStatFilePath = $contentPath.$appendPath;
		
		$res = false;
		$handle = @fopen($vpnStatFilePath, "r");
		if ($handle) {

		    while (($buffer = fgets($handle)) !== false) {
		        $bufArr = explode(",", $buffer);
		        
		        if(isset($bufArr[0]))
		        {
		        	$searchedIp = $bufArr[0];
		        	if($ip == $searchedIp)
		        	{
		        		$res = true;
		        		break;
		        	}
		        }
		    }
		    fclose($handle);
		}
		return $res;
	}
}
