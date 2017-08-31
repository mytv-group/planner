<?
class WeatherWidget extends AbstractWidget
{
    /*$config example
    * {"city": "Kiev,ua",
    * "cityshow":"Київ",
    * "bg":"background.png"}
    */

    private $imgFolder = '/widgets-content/weather/';
    private $apiUrl = 'http://api.openweathermap.org/data/2.5/weather?q';
    private $celsiusMin = 273.15;
    protected $imageCacheTime = 60;
    private $weekDays = [
        '', 'Пн', 'Вв', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'
    ];

    private $imageSizeX = 400;
    private $imageSizeY = 300;

    private $temperatureValuePosX = 192;
    private $temperatureValuePosY = 280;

    private $cityLabelPosX = 200;
    private $cityLabelPosY = 60;

    private $dateLabelPosX = 158;
    private $dateLabelPosY = 105;

    private $icoPosX = 138;
    private $icoPosY = 105;

    private $apiKey;

    private function setApiKey()
    {
        $this->apiKey = Yii::app()->params['weatherApiKey'];
    }

    private function buildRequest()
    {
        if ($this->apiKey && $this->config->city) {
            return $this->apiUrl . '=' . $this->config->city . '&appid=' . $this->apiKey;
        }
        return false;
    }

    protected function checkConfig()
    {
        if(!isset($this->config->cityname)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No cityname.']
            ));
        }

        if (!isset($this->config->bg)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' configured background unexist.']
            ));
        }

        $bg = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . $this->config->bg;

        if (!file_exists($bg)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' configured background unexist.']
            ));
        }
    }

    protected function generateImage()
    {
        $json = file_get_contents($this->buildRequest());
        $responce = json_decode($json, true);

        if (!isset($responce['main'])
          || !isset($responce['weather'])
          || !isset($responce['weather'][0])
          || !isset($responce['weather'][0]['icon'])
          || !isset($responce['main']['temp'])) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' weather API responce error.']
            ));
        }

        $temperature = round($responce['main']['temp'] - $this->celsiusMin) . '°';
        $icon = $responce['weather'][0]['icon'] . '.png';

        $imageWidth = $this->getConfigVal('imageSizeX');
        $imageHeigth = $this->getConfigVal('imageSizeY');

        $image = @imagecreatetruecolor($imageWidth, $imageHeigth);
        imagesavealpha($image, true);
        if (!$image) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' impossible to create image stream.']
            ));
        }

        $color = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $color);

        $icon = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . 'ico'
          . DIRECTORY_SEPARATOR . $icon;

        $notAvaliableIcon = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . 'ico'
          . DIRECTORY_SEPARATOR . 'na.png';

        if (!file_exists($icon)) {
            $icon = $notAvaliableIcon;
        }

        if (!file_exists($icon)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' icon unexist.']
            ));
        }

        $bg = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . $this->config->bg;

        $bg = imagecreatefrompng($bg);
        $icon = imagecreatefrompng($icon);

        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 0);

        imagecopy($image, $bg, 0, 0, 0, 0, $imageWidth, $imageHeigth);

        $icoPosX = $this->getConfigVal('icoPosX');
        $icoPosY = $this->getConfigVal('icoPosY');

        imagecopy($image, $icon, $icoPosX, $icoPosY, 0, 0, imagesx($icon), imagesx($icon));
        putenv('GDFONTPATH=' . dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . 'fonts');
        $font = getenv('GDFONTPATH') . DIRECTORY_SEPARATOR . 'arialbd.ttf';

        $cityLabelPosX = $this->getConfigVal('cityLabelPosX');
        $cityLabelPosY = $this->getConfigVal('cityLabelPosY');

        // align to center if no config
        if (!isset($this->config->temperatureValuePosX)) {
            $cityLabelPosX = ($cityLabelPosX * 2 - strlen($this->config->cityname) * 12) / 2;
        }

        imagettftext($image, 36, 0, $cityLabelPosX, $cityLabelPosY, $textColor, $font, $this->config->cityname);

        $w =  $this->weekDays[date('w')];
        $dateLabelPosX = $this->getConfigVal('dateLabelPosX');
        $dateLabelPosY = $this->getConfigVal('dateLabelPosY');
        imagettftext($image, 16, 0, $dateLabelPosX, $dateLabelPosY, $textColor, $font, $w . ', ' . date('d/m'));

        $temperatureValuePosX = $this->getConfigVal('temperatureValuePosX');
        $temperatureValuePosY = $this->getConfigVal('temperatureValuePosY');

        // align to center if no config
        if (!isset($this->config->temperatureValuePosX)) {
            $temperatureValuePosX = $this->temperatureValuePosX - (strlen($temperature) - 2) * 10;
        }

        imagettftext($image, 40, 0, $temperatureValuePosX, $temperatureValuePosY, $textColor, $font, $temperature);

        if (isset($this->config->rotation)
            && is_int(intval($this->config->rotation))
        ) {
            $image = imagerotate($image, $this->config->rotation, 0);
        }

        imagepng($image, dirname(Yii::app()->basePath) . $this->getOutput(), 1);
        imagedestroy($image);

        return;
    }

    private function getConfigVal($attrName)
    {
        $defaultVal = isset($this->$attrName) ? $this->$attrName : 0;
        return (isset($this->config->$attrName) && is_int(intval($this->config->$attrName)))
          ? intval($this->config->$attrName)
          : $defaultVal;
    }

    public function run()
    {
        $this->setApiKey();
        return parent::run();
    }
}
