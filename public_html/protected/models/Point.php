<?php

Yii::import('ext.EHttpClient.*');

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
 */
class Point extends CActiveRecord
{
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, username, ip, screen_id', 'required'),
			array('volume, TV, screen_id', 'numerical', 'integerOnly'=>true),
			array('name, username', 'length', 'max'=>255),
			array('sync', 'boolean'),
			array('update_time','default',
					'value'=>new CDbExpression('NOW()'),
					'setOnEmpty'=>false,'on'=>'insert'),
			array('update_time','default',
					'value'=>new CDbExpression('NOW()'),
					'setOnEmpty'=>false,'on'=>'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, username', 'safe', 'on'=>'search'),
		);
	}

	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
				'channels'=>array(self::HAS_MANY, 'Channel', 'id_point'),
				'playlistToChannel'=>array(self::HAS_MANY,'PlaylistToChannel',array('id'=>'channelId'),'through'=>'channels'),
				'playlists'=>array(self::HAS_MANY,'Playlists', array('playlistId'=>'id'),'through'=>'playlistToChannel'),
				
				'pointtonet'=>array(self::HAS_MANY, 'PointToNetByUsers', 'point_id'),
				'net'=>array(self::HAS_MANY, 'Net', array('netId'=>'id'),'through'=>'pointtonet'),
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		if(Yii::app()->user->username != User::ROLE_ADMIN)
		{
			$criteria->compare('username',Yii::app()->user->name);
		}

		$answ = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));		
		
		return $answ;
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
	
	public function CheckNetOwnerExist($id)
	{
		$owner = null;
		$model = self::model()->findByPk($id);

		
		return $owner;
	}
	
	public function GetPointPlaylistNames($id)
	{
		$model = self::model()->findByPk($id);
		$connection = Yii::app()->db;
		$connection->active=true;
		
		$avaliableChannels = $model->channels;
		$playlists = array();
		
		if(strlen($avaliableChannels) > 0)
		{
			$sql = "SELECT `id`, `name` FROM `playlists` WHERE `id` IN (".$avaliableChannels.");";
		
			$command = $connection->createCommand($sql);
			$dataReader=$command->query();
			while(($row=$dataReader->read())!==false)
			{
				$playlists[] = array(
						'id' => $row['id'],
						'name' => $row['name']
				);
			}
		}
		
		$connection->active=false;
		return $playlists;
	}
	
	public function GetUserAvaliablePlaylist()
	{
		$userName = Yii::app()->user->name;
		$connection = Yii::app()->db;
		$connection->active=true;
	
		$playlists = array();
	
		$sql = "SELECT `id`, `name`, `fromDatetime`, `toDatetime`, `fromTime`,`toTime`, ".
			"`sun`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat` FROM `playlists` WHERE `author` = '".$userName."';";

		$command = $connection->createCommand($sql);
		$dataReader=$command->query();
		while(($row=$dataReader->read())!==false)
		{
			$weedDays = "";
			if($row['sun']) { $weedDays .= 'Sun '; }  
			if($row['mon']) { $weedDays .= 'Mon '; }
			if($row['tue']) { $weedDays .= 'Tue '; }
			if($row['wed']) { $weedDays .= 'Wed '; }
			if($row['thu']) { $weedDays .= 'Thu '; }
			if($row['fri']) { $weedDays .= 'Fri '; }
			if($row['sat']) { $weedDays .= 'Sat '; }
			
			$playlists[] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'fromDatetime' => $row['fromDatetime'],
				'toDatetime' => $row['toDatetime'],
				'fromTime' => $row['fromTime'],
				'toTime' => $row['toTime'],
				'weekDays' => $weedDays
			);
		}

		$connection->active=false;
		return $playlists;
	}
	
	
	public function SendRequestForUpdate($ip)
	{
		if(defined('HTTP_REQUEST_TO_POINT'))
		{
			$requestData = array(
					"update_need" => true
			);
			
			$requestAddr = 'http://' . $ip . "/sc_upd";
			
			try
			{
				$client = new EHttpClient($requestAddr, array(
						'maxredirects' => 3,
						'timeout'      => 10,
						'adapter'      => 'EHttpClientAdapterCurl'));
	
				$client->setParameterGet($requestData);
				
				$response = $client->request();
				
				$body = "";
				if($response->isSuccessful())
				{
					$body = $response->getBody();
				}
				else
				{
					$body = $response->getRawBody();
				}
			}
			catch (Exception $ex)
			{
				error_log("http request exception - " . json_encode($ex) . ". " .
						"IP - " . $requestAddr . ", " .
						"Post - " . json_encode($requestData)
				);
			}			
		}		
	}
	
	public function PrepareFilesForSync($id)
	{
		$model = self::model()->findByPk($id);
		$pointDir = "spool/points/" . $model->id;

		//remove dir if exist
		if(file_exists($pointDir))
		{
			try
			{
				$this->DeleteDir($pointDir);
			}
			catch (Exception $e)
			{
				error_log("Unlink failed. Exception - " . json_encode($e).
				"Dir - " . $pointDir);
			}
		}
			
		$avaliableChannels = $model->channels;
		
		$connection = Yii::app()->db;
		foreach ($avaliableChannels as $channel)
		{
			$channelId = $channel['id'];
			$channelInfo = Channel::model()->findByPk($channelId);
			$internalId = $channelInfo['internalId'];
			$playlists = $channelInfo->playlists;
			
			$channelDir = $pointDir . "/" . $internalId;
			$channelFullDir = $this->PrepareSpoolPath($channelDir);
			
			foreach ($playlists as $pl)
			{
				$plFiles = explode(",", $pl->files);

				foreach ($plFiles as $fileId)
				{
					if($fileId != '')
					{
						$connection->active=true;
						$sql = "SELECT `name`, `path` FROM `file` WHERE `id` = ".$fileId.";";
						$command = $connection->createCommand($sql);
						$dataReader=$command->query();
	
						if(($row=$dataReader->read())!==false)
						{
							$path = $row['path'];
							$fileName = $row['name'];
							$symlinkPath = $channelFullDir . $fileName;
							
							if(!file_exists($symlinkPath))
							{
								if(defined('SYMLINK'))
								{
									symlink($path, $symlinkPath);
								}
								else
								{
									copy($path, $symlinkPath);
								}
							}
						}
						$connection->active=false;
					}
				}
			}
		}		
	}
	

	private function PrepareSpoolPath($extPathAppendix)
	{
		$pathAppendix = $extPathAppendix;
		$pathAppendix = explode("/", $pathAppendix);
	
		$contentPath = $_SERVER["DOCUMENT_ROOT"];
	
		foreach($pathAppendix as $folder)
		{
			$contentPath .= "/" . $folder;
			if (!file_exists($contentPath) && !is_dir($contentPath))
			{
				mkdir($contentPath);
			}
		}
	
		$contentPath .= "/";
		return $contentPath;
	}
	
	public function RemovePointSpoolPath($id)
	{
		$pointDir = "spool/points/" . $id;
		if(file_exists($pointDir))
		{
			try
			{
				$this->DeleteDir($pointDir);
			}
			catch (Exception $e)
			{
				error_log("Unlink failed. Exception - " . json_encode($e).
					"Dir - " . $pointDir
				);
			}
		}
	}
	
	private function DeleteDir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") $this->DeleteDir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	
	public function CreateTVSocketRequest($ip, $TV)
	{
		if(defined('SOCKET_REQUEST_TO_POINT') && function_exists ('socket_create'))
		{
			// socket
			try {	
				set_time_limit ( 10 );
			
				/* Создаём  TCP / IP */
				//error_reporting ( 0 );
				$socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP );
				if ($socket === false) {
					error_log ( "Не удалось выполнить socket_create(): причина: " . socket_strerror ( socket_last_error () ) . "\n" );
				}
				
				$result = socket_connect ( $socket, $ip, 8777 );
				if ($result === false) {
					error_log ( "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror ( socket_last_error ( $socket ) ) . "\n" );
				} else {
					if ($TV == 1) {
						socket_write ( $socket, 0, strlen ( 0 ) );
					} else {
						socket_write ( $socket, 1, strlen ( 1 ) );
					}
				}
				socket_close ( $socket );
				//error_reporting ( E_ALL );
				
			} catch ( Exception $ex ) {
				error_log ( "Socket exception." );
			}
		}
	}
	
	public function CreateChannelsForWindows($screenId, $pointId)
	{
		$Screen = Screen::model()->findByPk($screenId);
		$windows = $Screen->windows;
				
		Channel::model()->deleteAll("id_point = :id_point AND window_id IS NOT NULL", array('id_point' => $pointId));
		
		$ii = 0; 
		foreach($windows as $window)
		{
			$ii++;
			$channel = new Channel;

			$channel->attributes = array(
				'id_point' => $pointId,
				'window_id' => $window->id,
				'internalId' => $ii
			);
			
			$channel->save();
		}	
	}
}
