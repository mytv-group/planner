<?php

class PreviewController extends Controller
{
	public $layout = '';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users
						'users'=>array('*'),
				),
		);
	}
	
	public function actionView()
	{
		$pointId= Yii::app()->getRequest()->getParam('id');
		$this->pageTitle = "Point Preview";
		$this->renderPartial('index', array('pointId' => $pointId), false, true);
	}
	
	public function actionAjaxGetChannels()
	{
		$pointId = Yii::app ()->request->getPost ( 'pointId' );
		
		$pointDateStr = date('Y-m-d');
		$requestTime = date('H:i:s');
		$pointDatetimeStr = $pointDateStr. " 23:59:59";
		$pointDateTimestamp = strtotime ( $pointDateStr );
		$weekDay = strtolower ( date ( 'D', $pointDateTimestamp ));
		
		$Playlist = new Playlists();
		$pointChannels = Channel::model()->findAll('id_point=:id_point AND window_id IS NOT NULL',array(':id_point'=>$pointId));
		
		$resp = array();
		foreach ($pointChannels as $pointChannel) {
			$pointChannelId = $pointChannel->internalId;
			$windowId = $pointChannel->window_id;
			
			if(!is_null($windowId)){
				$bg = $Playlist->GetBGContentArr ( $pointId, $pointChannelId, $pointDatetimeStr, $weekDay );
				$adv = $Playlist->GetAdvContentArr ( $pointId, $pointChannelId, $pointDatetimeStr, $weekDay );
							
				$blockStructedContent = $Playlist->BuildBlockStructedContent($bg, $adv);
				$straightTimeContent = $Playlist->ConverBlockStructedToStraightTimeContent($blockStructedContent);
								
				$straightTimeContentHisToSecs = $this->ConvertHisToSecsInStraightTime($straightTimeContent, $requestTime);
				$url = Yii::app()->request->getBaseUrl(true) .'/spool/points/'.$pointId.'/'.$pointChannelId;
				$straightTimeContentWithURLPath = $this->UpdateContentPathes($straightTimeContentHisToSecs, $url);
				
				$window = Window::model()->findByPK($windowId);
				
				$widgetToChannel = WidgetToChannel::model()->find("channel_id = :channel_id",
						array("channel_id" => $pointChannel->id));
				$widget = '';
				
				if(count($widgetToChannel) > 0) {
					$widgetModel = Widget::model()->findByPk($widgetToChannel['widget_id']);
					$widget = $widgetModel['content'];
				}
				
				$resp[] = array(
					'width' => $window->width,
					'height' => $window->height,
					'top' => $window->top,
					'left' => $window->left,
					'content' =>  $straightTimeContentWithURLPath,
					'widget' =>  $widget
				);
			}
		}
		
		echo json_encode ($resp);
		exit ();
	}
	
	private function ConvertHisToSecsInStraightTime ($straightTimeContent, $requestTime) 
	{
		$newStraightTimeContent = array();
		$requestTimeObj = new DateTime($requestTime);
		
		$requestTime = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $requestTime);
		sscanf($requestTime, "%d:%d:%d", $hours, $minutes, $seconds);
		$offset = $hours * 3600 + $minutes * 60 + $seconds;
		
		$formingPlBegin = false;
		foreach ($straightTimeContent as $key => $val) {
			$moovieTimeObj = new DateTime($key);
			
			if($moovieTimeObj > $requestTimeObj){
				if(!$formingPlBegin){
					$newStraightTimeContent['0'] = $val;
					$formingPlBegin = true;
				}
				
				$strTime = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $key);
				sscanf($strTime, "%d:%d:%d", $hours, $minutes, $seconds);
				
				$timeSeconds = ($hours * 3600 + $minutes * 60 + $seconds) - $offset;
				$newStraightTimeContent[$timeSeconds] = $val;
			}
			
			$prevMoovieT = $val;
		}
		
		return $newStraightTimeContent;	
	}
	
	private function UpdateContentPathes($straightTimeContent, $url)
	{
		$newStraightTimeContent = array();
		foreach ($straightTimeContent as $key => $val) {
			$newStraightTimeContent[] = array($key, $url . "/" . $val);
		}
		return $newStraightTimeContent;
	}
		
	public function beforeAction($action) {
		if( parent::beforeAction($action) ) {
			$cs = Yii::app()->clientScript;
				
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/lib/jquery-1.11.0.js' );
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap.min.js' );
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/bootstrap/bootstrap-switch.min.js' );

			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/preview/LayoutFactoryModel.js' );
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/preview/ReceiverModel.js' );
			$cs->registerScriptFile( Yii::app()->getBaseUrl() . '/js/pages/preview/preview.js' );
				
			Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/bootstrap.min.css');
	
			return true;
		}
		return false;
	}
}