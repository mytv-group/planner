<?
class TVscheduleWidget extends CWidget
{
    public $tvBlocks = [];
    public $editable = false;

    private $jsSrc = '/js/widgets-internal/tv-schedule.js';
    private $cssSrc = '/css/widgets-internal/tv-schedule.css';

    public function init()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getBaseUrl() . $this->cssSrc);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . $this->jsSrc, $cs::POS_END);
    }

    public function run()
    {
        $this->render('tvSchedule', [
            'tvBlocks' => $this->tvBlocks,
            'editable' => $this->editable
        ]);
    }
}
