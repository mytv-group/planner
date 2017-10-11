<?php

class BaseComponent extends CApplicationComponent
{
    private $_model;

    /*
    * This magic allows not to define
    * getter function in componets i.e
    * abolishes following:
    *
    * component config:
    * 'compenent' => [
    *     'class'=>'ClassComponent',
    *     'propSimple' => function() {
    *         return 'simple';
    *     },
    *     'propComplex' => function() {
    *         return new StdClass;
    *     },
    * ],
    *
    * to use propSimple it is necessary to write getter
    * in component
    *
    * private function getPropSimple()
    * {
    *     return $this->propSimple;
    * }
    *
    * to use propComplex:
    *
    * private function getPropComplex()
    * {
    *     return $this->propSimple->__invoke();
    * }
    */
    public function __call($name, $arguments) {
        if ((strpos($name, 'get') !== 0)
            || (strlen($name) < 4)
        ) {
            throw new \Exception('Called method unexist');
        }

        $property = lcfirst(substr($name, 3, strlen($name)));

        if (!property_exists($this, $property)) {
            throw new \Exception('Called attribute not announced');
        }

        if (!is_callable([$this->$property, '__invoke'])) {
            return $this->$property;
        }

        return $this->$property->__invoke();
    }

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
