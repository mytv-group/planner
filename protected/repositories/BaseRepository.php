<?php

class BaseRepository extends CApplicationComponent
{
    private $_model;
    public $model;

    protected function model()
    {
        if (!empty($this->_model)) {
            return $this->_model;
        }

        $matches = [];
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', get_class($this), $matches);
        $className = $matches[0][0];
        $this->_model = call_user_func([$className, 'model']);

        return $this->_model;
    }
}
