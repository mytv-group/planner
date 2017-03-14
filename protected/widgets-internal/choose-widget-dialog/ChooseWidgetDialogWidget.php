<?
class ChooseWidgetDialogWidget extends CWidget
{
    public $widgets= null;

    private $jsSrc = '/js/widgets-internal/choose-widget-dialog.js';
    private $cssSrc = '/css/widgets-internal/choose-widget-dialog.css';

    public function init()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getBaseUrl() . $this->cssSrc);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . $this->jsSrc, $cs::POS_END);
    }

    public function run()
    {
        $this->render('widgetSelectorDialog', [
            'widgets' => $this->widgets
        ]);
    }
}
