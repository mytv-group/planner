<?
class WeatherWidget extends CWidget
{
    private $type = '';
    private $img = '/widgets-content/weather-substrate.png';

    public function setType($type) {
        $this->type = $type;
    }

    public function run()
    {
        if (($this->type !== '') && method_exists($this, $this->type)) {
            call_user_func([$this, $this->type]);
            return;
        }

        throw new Error (implode('',
            ['Widget ', __CLASS__, ' does not contain method ', $this->type, '.']
        ));
    }

    public function preview()
    {
        echo sprintf('<img class="widget-preview-img" src="%s" alt="Weather substrate"/>', $this->img);
    }

    public function info()
    {
        return ['img' => $this->img];
    }
}
