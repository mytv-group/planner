<?
class TemperatureWidget extends CWidget
{
    private $type = '';
    private $config;

    private $imgFolder = '/widgets-content/';
    private $apiUrl = 'http://api.openweathermap.org/data/2.5/weather?q';
    private $celsiusMin = 273.15;
    private $weatherCacheTime = 3600;

    private $apiKey;

    private function setApiKey()
    {
        $this->apiKey = Yii::app()->params['weatherApiKey'];
    }

    private function getImg()
    {
        return $this->imgFolder . $this->config->img;
    }

    public function setConfig($config) {
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
        $json = file_get_contents($this->buildRequest());
        $responce = json_decode($json, true);

        if (!isset($responce['main']) || !isset($responce['main']['temp'])) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' weather API responce error.']
            ));
        }

        $temperature = round($responce['main']['temp'] - $this->celsiusMin);
        $image = @imagecreate(55, 55);
        if (!$image) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' impossible to create image stream.']
            ));
        }
        $backgroundColor = imagecolorallocatealpha($image, 255, 255, 255, 0);
        $textColor = imagecolorallocate($image, 33, 33, 33);

        putenv('GDFONTPATH=' . dirname(Yii::app()->basePath) . '/css/fonts');
        $font = 'arialbd.ttf';

        imagettftext($image, 26, 0, 8, 38, $textColor, $font, $temperature);

        imagepng($image, dirname(Yii::app()->basePath) . $this->getImg(), 0.75);
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
        $file = dirname(Yii::app()->basePath) . '/' . $this->getImg();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . '/' . $this->getImg());

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
        $file = dirname(Yii::app()->basePath) . '/' . $this->getImg();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . '/' . $this->getImg());

            if ((time() - $fileCreated) > $this->weatherCacheTime) {
                $this->generateImage();
            }
        } else {
            $this->generateImage();
        }

        return ['img' => $this->getImg()];
    }
}
