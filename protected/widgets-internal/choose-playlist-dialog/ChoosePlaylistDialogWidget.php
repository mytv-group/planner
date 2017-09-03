<?
class ChoosePlaylistDialogWidget extends BaseInternalWidget
{
    public $channelType = null;
    public $channelName = null;
    public $playlists = null;

    public function run()
    {
        $this->render('playlistDialog', [
            'channelName' => $this->channelName,
            'channelType' => $this->channelType,
            'playlists' => $this->playlists
        ]);
    }
}
