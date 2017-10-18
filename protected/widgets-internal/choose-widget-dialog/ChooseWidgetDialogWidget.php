<?
class ChooseWidgetDialogWidget extends BaseInternalWidget
{
    public $widgets = null;

    public function run()
    {
        $this->render('widgetSelectorDialog', [
            'widgets' => $this->widgets
        ]);
    }
}
