<?php
class PointChannelsWidget extends CWidget
{
    public $point = null;
    public $editable = null;
    public $postName = 'Point';

    private $jsSrc = '/js/widgets-internal/point-channels.js';
    private $cssSrc = '/css/widgets-internal/point-channels.css';

    public function init()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getBaseUrl() . $this->cssSrc);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . $this->jsSrc, $cs::POS_END);
    }

    public function run()
    {
        $this->render('pointChannels', [
            'channelTypes' => Playlists::$types,
            'point' => $this->point,
            'editable' => $this->editable,
            'postName' => $this->postName
        ]);
    }
}
