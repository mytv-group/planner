<?php

class WebUser extends CWebUser
{
    private $_model = null;

    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }

        return $this->_model;
    }

    function getRole()
    {
        if ($user = $this->getModel()){
            return $user->role;
        }
    }

    function isAdmin()
    {
        if ($user = $this->getModel()){
            return ($user->role === $user::ROLE_ADMIN);
        }
    }

    public function checkAccess($operation, $params=array(), $allowCaching=true)
    {
        $checkAccess = Yii::app()->getAuthManager()->checkAccess($operation, $this->getId(), $params);
        return $checkAccess;
    }
}
