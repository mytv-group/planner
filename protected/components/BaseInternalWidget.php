<?php
class BaseInternalWidget extends CWidget
{
    public function init()
    {
        $cls = substr_replace(
            get_class($this),
            '',
            strrpos(get_class($this), 'Widget', -1),
            strlen('Widget')
        );
        $name = $this->uncamelize($cls, '-');

        foreach (['js', 'css'] as $dim) {
            $file = '/' . $dim . '/widgets-internal/' . $name . '.' . $dim;
            if (file_exists(INDEX_PATH . $file)) {
                Yii::app()->assets->register($file);
            }
        }
    }

    private function uncamelize($camel, $splitter = "_")
    {
        $camel = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel));
        return strtolower($camel);
    }
}
