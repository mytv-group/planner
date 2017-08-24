<?
class ScreenSelectorWidget extends CWidget
{
    public $point = null;
    public $screens = [];
    public $editable = false;
    public $postName = 'Point';

    private $jsSrc = '/js/widgets-internal/screen-selector.js';
    private $cssSrc = '/css/widgets-internal/screen-selector.css';

    public function init()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getBaseUrl() . $this->cssSrc);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . $this->jsSrc, $cs::POS_END);
    }

    public function run()
    {
        $this->render('screenSelector', [
            'point' => $this->point,
            'screens' => $this->screens,
            'editable' => $this->editable,
            'postName' => $this->postName
        ]);
    }
}
