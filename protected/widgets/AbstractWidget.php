<?
abstract class AbstractWidget extends CWidget
{
    protected $id;
    protected $type = '';
    protected $config;

    protected $outputFolder = '/runtime-widgets';
    protected $imageCacheTime = 1;

    const outputPrefix = 'counter';
    const imgExt = '.png';

    abstract protected function checkConfig();
    abstract protected function generateImage();

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    protected function getOutput()
    {
        if(!file_exists(dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->outputFolder)) {
            mkdir(dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->outputFolder);
        }

        $output = $this->id . "_" . self::outputPrefix;
        if (isset($this->config->output)) {
            $output .= $this->config->output;
        }
        $output .= self::imgExt;
        return $this->outputFolder . DIRECTORY_SEPARATOR . $output;
    }

    public function preview()
    {
        $file = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getOutput();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getOutput());

            if ((time() - $fileCreated) < $this->imageCacheTime) {
                echo sprintf('<img class="widget-preview-img" src="%s" alt="Widget"/>', $this->getOutput());
                exit;
            }
        }

        $this->generateImage();
        echo sprintf('<img class="widget-preview-img" src="%s" alt="Widget"/>', $this->getOutput());
        exit;
    }

    public function showData()
    {
        $file = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getOutput();
        if (file_exists ($file)) {
            $fileCreated = filemtime (dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . $this->getOutput());

            if ((time() - $fileCreated) > $this->imageCacheTime) {
                $this->generateImage();
            }
        } else {
            $this->generateImage();
        }

        return ['img' => $this->getOutput()];
    }
}
