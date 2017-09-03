<?
class ScreenSelectorWidget extends BaseInternalWidget
{
    public $point = null;
    public $screens = [];
    public $editable = false;
    public $postName = 'Point';

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
