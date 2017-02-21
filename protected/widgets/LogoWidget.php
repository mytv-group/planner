<?
class LogoWidget extends AbstractWidget
{
    /*$config example
    * "bg":"background.png"
    */

    private $imgFolder = '/widgets-content/logo/';

    const outputPrefix = 'logo';

    protected function checkConfig()
    {
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
    }

    protected function generateImage()
    {
        $image = @imagecreatetruecolor(600, 300);
        imagesavealpha($image, true);
        if (!$image) {
            throw new Error (implode('',
                ['Widget ', __CLASS__, ' impossible to create image stream.']
            ));
        }

        $color = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $color);

        $bg = dirname(Yii::app()->basePath)
          . DIRECTORY_SEPARATOR . $this->imgFolder
          . DIRECTORY_SEPARATOR . $this->config->bg;

        $bg = imagecreatefrompng($bg);

        imagecopy($image, $bg, 0, 0, 0, 0, 600, 300);

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
