<?
class TvScheduleWidget extends BaseInternalWidget
{
    public $tvBlocks = [];
    public $editable = false;
    public $postName = 'Point';

    public function run()
    {
        $this->render('tvSchedule', [
            'tvBlocks' => $this->tvBlocks,
            'editable' => $this->editable,
            'postName' => $this->postName
        ]);
    }
}
