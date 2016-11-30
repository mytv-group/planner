<?
class WeatherWidget extends CWidget
{
    private $type = '';
    private $config;
    private $imgFolder = '/widgets-content/';

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    private function getImg()
    {
        return $this->imgFolder . $this->config->img;
    }

    public function run()
    {
        if(!$this->config->img) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' does not have img config attribute.']
            ));
        }

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
        echo sprintf('<img class="widget-preview-img" src="%s" alt="Weather substrate"/>', $this->getImg());
    }

    public function showData()
    {
        return ['img' => $this->getImg()];
    }
}
