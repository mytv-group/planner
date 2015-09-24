<?
    class PhpAuthManager extends CPhpAuthManager{
  	
        public function init(){
            if($this->authFile===null){
                $this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
            }

            parent::init();
                                   
            // Для гостей у нас и так роль по умолчанию guest.
            if(!Yii::app()->user->isGuest){
                // Связываем роль, заданную в БД с идентификатором пользователя,
                // возвращаемым UserIdentity.getId().
                
                $this->assign(Yii::app()->user->role, Yii::app()->user->id);
                //$subroles = $this->getSubroles(array(Yii::app()->user->role));
            }
        }
                          
//         public function getSubroles($roles)
//         {
//         	$roleChildren = array();
//         	$rolesArray = $this->_rolesArray;
//         	foreach ($roles as $role)
//         	{
//         		$roleChildren[] = $role;
//         		if(array_key_exists($role, $rolesArray) &&
//         				array_key_exists('children', $rolesArray[$role]))
//         		{
//         			$roleChildren = array_merge($roleChildren, 
//         					$this->getSubroles($rolesArray[$role]['children'], $rolesArray));
//         		}
//         	}
        	
//         	return array_unique($roleChildren);
//         }
    }