<?
class WeatherWidget extends CWidget
{
    private $type = '';
    private $config;

    private $imgFolder = '/widgets-content/weather/';
    private $apiUrl = 'http://api.openweathermap.org/data/2.5/weather?q';
    private $celsiusMin = 273.15;
    private $weatherCacheTime = 3600;
    private $weekDays = [
        'Пн', 'Вв', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'
    ];

    private $apiKey;

    private function setApiKey()
    {
        $this->apiKey = Yii::app()->params['weatherApiKey'];
    }

    private function getImg()
    {
        return $this->imgFolder . $this->config->img;
    }

    /*$config example
    * {"city": "Kiev,ua", "cityshow":"Київ", "img":"weather.png","bg":"background.png"}
    */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    private function buildRequest()
    {
        if ($this->apiKey && $this->config->city) {
            return $this->apiUrl . '=' . $this->config->city . '&appid=' . $this->apiKey;
        }
        return false;
    }

    private function generateImage()
    {
        if(!isset($this->config->cityname)) {
          throw new Error (implode('',
              ['Widget ', __CLASS__, ' config error.No cityname.']
          ));
        }
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

        $image = @imagecreatetruecolor(400, 300);
        imagesavealpha($image, true);
        if (!$image) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' impossible to create image stream.']
            ));
        }

        $color = imagecolorallocate($image, 255, 255, 255);
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

        if (!file_exists($bg)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' configured background unexist.']
            ));
        }
        $bg = imagecreatefrompng($bg);
        $icon = imagecreatefrompng($icon);

        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 0);

        imagecopy($image, $bg, 0, 0, 0, 0, 400, 300);
        imagecopy($image, $icon, 138, 105, 0, 0, imagesx($icon), imagesx($icon));
        putenv('GDFONTPATH=' . dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . 'css'
          . DIRECTORY_SEPARATOR . 'fonts');

        $font = getenv('GDFONTPATH') . DIRECTORY_SEPARATOR . 'arialbd.ttf';

        $w =  $this->weekDays[date('w')];

        $temperatureXpos = 188 - (strlen($temperature) - 2) * 10;

        imagettftext($image, 36, 0, 155, 60, $textColor, $font, $this->config->cityname);
        imagettftext($image, 16, 0, 158, 105, $textColor, $font, $w . ', ' . date('d/m'));
        imagettftext($image, 40, 0, $temperatureXpos, 280, $textColor, $font, $temperature);

        imagepng($image, dirname(Yii::app()->basePath) . $this->getImg(), 1);
        imagedestroy($image);

        return;
    }

    public function run()
    {
        $this->setApiKey();

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
        $file = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getImg();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getImg());

            if ((time() - $fileCreated) < $this->weatherCacheTime) {
                echo sprintf('<img class="widget-preview-img" src="%s" alt="Weather temperature"/>', $this->getImg());
                exit;
            }
        }

        $this->generateImage();
        echo sprintf('<img class="widget-preview-img" src="%s" alt="Weather temperature"/>', $this->getImg());
        exit;
    }

    public function showData()
    {
        $file = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getImg();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getImg());

            if ((time() - $fileCreated) > $this->weatherCacheTime) {
                $this->generateImage();
            }
        } else {
            $this->generateImage();
        }

        return ['img' => $this->getImg()];
    }
}
