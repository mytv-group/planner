<?
class ChoosePlaylistDialogWidget extends CWidget
{
    public $channelType = null;
    public $channelName = null;
    public $playlists = null;

    private $jsSrc = '/js/widgets-internal/choose-playlist-dialog.js';
    private $cssSrc = '/css/widgets-internal/choose-playlist-dialog.css';

    public function init()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getBaseUrl() . $this->cssSrc);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . $this->jsSrc, $cs::POS_END);
    }

    public function run()
    {
        $this->render('playlistDialog', [
            'channelName' => $this->channelName,
            'channelType' => $this->channelType,
            'playlists' => $this->playlists
        ]);
    }
}
