<?
class GreetingWidget extends AbstractWidget
{
    /*$config example
    * "header": "З НОВИМ РОКОМ",
    * "footer": "ТА РІЗДВОМ ХРИСТОВИМ!",
    * "logo": "logo",
    * "bg":"background.png"
    */

    private $imgFolder = '/widgets-content/greeting/';

    const outputPrefix = 'greeting';

    protected function checkConfig()
    {
        if (!isset($this->config->header)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No header text.']
            ));
        }

        if (!isset($this->config->footer)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No footer text.']
            ));
        }

        if (!isset($this->config->logo)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No logo.']
            ));
        }

        if (!isset($this->config->rotation)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No rotation value.']
            ));
        }

        if (!isset($this->config->bg)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' config error. No bg image name.']
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

        $logo = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . 'logo.png';

        if (!file_exists($logo)) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' logo unexist.']
            ));
        }
    }

    protected function generateImage()
    {
        $image = @imagecreatetruecolor(500, 300);
        imagesavealpha($image, true);
        if (!$image) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' impossible to create image stream.']
            ));
        }

        $color = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $color);

        $logo = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . 'logo.png';

        $bg = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . $this->config->bg;

        $bg = imagecreatefrompng($bg);
        $logo = imagecreatefrompng($logo);

        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 0);

        imagecopy($image, $bg, 0, 0, 0, 0, 500, 300);
        imagecopy($image, $logo, 220, 224, 0, 0, imagesx($logo), imagesx($logo));
        putenv('GDFONTPATH=' . dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . 'fonts');

        $font = getenv('GDFONTPATH') . DIRECTORY_SEPARATOR . 'arialbd.ttf';

        imagettftext($image, 27, 0, 95, 90, $textColor, $font, $this->config->header);
        imagettftext($image, 27, 0, 15, 150, $textColor, $font, $this->config->footer);

        if (isset($this->config->rotation)
            && is_int(intval($this->config->rotation))
        ) {
            $image = imagerotate($image, $this->config->rotation, 0);
        }

        imagepng($image, dirname(Yii::app()->basePath) . $this->getOutput(), 1);
        imagedestroy($image);

        return;
    }
}
