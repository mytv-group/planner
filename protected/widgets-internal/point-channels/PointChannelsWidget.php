<?php
class PointChannelsWidget extends BaseInternalWidget
{
    public $point = null;
    public $editable = null;
    public $postName = 'Point';

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
