<?php

class PointInfoRequester extends CApplicationComponent
{
	private $status = null;
	
	private function getStats($ip)
	{		
		if($this->status[$ip]) {
			return $this->status[$ip];
		}
		try {
			error_reporting(0);
			$content = file_get_contents('http://'.$ip.'/status.xml');
			var_dump($content);
			error_reporting(E_ALL);
		} catch(Exception $e) {
			return null;
		}
		 
		if(!$content) {
			return null;
		}
				
		$xmlObj = '';//SimpleXMLElement($content);
		$this->status[$ip] = $xmlObj;
		
		return $xmlObj;
	}
	
    public function getSpaceInfo($ip)
    {    	
		$status = $this->getStats($ip);
		
		if(!isset($status->volume)) {
			return null;
		}
		
		$volume = [];
		foreach ($status->volume as $volume) {
			if(isset($volume['name'])
					&& ($volume['name'] == 'content')
					&& isset($volume->size)
					&& isset($volume->free)
			){
				$volume = [
					'size' => $volume->size,
					'free' => $volume->free
				];
			}
		}

        return $volume;
    }
}