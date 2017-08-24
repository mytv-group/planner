<?php
    class WebUser extends CWebUser {
        private $_model = null;

        function getRole() {
            if($user = $this->getModel()){
                return $user->role;
            }
        }

        private function getModel(){
            if (!$this->isGuest && $this->_model === null){
                $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
            }
            return $this->_model;
        }

        public function checkAccess($operation, $params=array(), $allowCaching=true)
        {
            $checkAccess = Yii::app()->getAuthManager()->checkAccess($operation, $this->getId(), $params);
            return $checkAccess;
        }
    }
