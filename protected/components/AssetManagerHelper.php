<?php

class AssetManagerHelper extends BaseComponent
{
    public $assetManager;
    public $clientScript;
    public $basePath;

    public function register($path, $basePath = null)
    {
        if ($basePath === null) {
            $basePath = $this->getBasePath();
        }

        $path = $basePath . $path;

        $assetManager = $this->getAssetManager();
        $clientScript = $this->getClientScript();

        $method = null;

        if (substr($path, -3) === '.js') {
            $method = 'registerScriptFile';
        } else if (substr($path, -4) === '.css') {
            $method = 'registerCssFile';
        }

        if ($method === null) {
            return false;
        }

        $clientScript->$method(
            $assetManager->publish($path, false, -1, YII_DEBUG)
        );
    }

    public function registerPackage($packedgeName)
    {
        if (!isset(Yii::app()->clientScript->packages[$packedgeName])) {
            throw new Exception("Registering package <<" . $packedgeName . ">> does not exist", 1);
        }

        $p = Yii::app()->clientScript->packages[$packedgeName];
        $cssArray = isset($p['css']) ? $p['css'] : [];
        $jsArray = isset($p['js']) ? $p['js'] : [];
        $filesArray = isset($p['files']) ? $p['files'] : [];
        $pathFromRoot = isset($p['pathFromRoot']) ? $p['pathFromRoot'] : '/';
        $basePath = $this->getBasePath() . $pathFromRoot;

        $assetManager = $this->getAssetManager();
        $clientScript = $this->getClientScript();

        $publishedCssFiles = [];
        foreach ($cssArray as $file) {
            $path = $basePath . $file;
            $exists = file_exists($assetManager->getPublishedPath($path));
            $publishedCss = $assetManager->publish($path, false, -1, YII_DEBUG);
            $clientScript->registerCssFile($publishedCss);
            $publishedCssFiles[] = [
                'exists' => $exists,
                'origin' => $file,
                'published' => $publishedCss
            ];
        }

        foreach ($jsArray as $file) {
            $path = $basePath . $file;
            $clientScript->registerScriptFile(
                $assetManager->publish($path, false, -1, YII_DEBUG)
            );
        }

        $publishedFiles = [];
        foreach ($filesArray as $file) {
            $path = $basePath . $file;
            $publishedFile = $assetManager->publish($path, false, -1, YII_DEBUG);
            $publishedFiles[] = [
                'origin' => $file,
                'published' => $publishedFile
            ];
        }

        foreach ($publishedCssFiles as $item) {
            $cssFile = $item['published'];
            $filePath = $this->getBasePath() . $cssFile;

            if ($handler = fopen($filePath, 'r+')) {
                if (flock($handler, LOCK_SH)) {
                    $str = file_get_contents($filePath);

                    foreach ($publishedFiles as $file) {
                        if (!$item['exists']) {
                            $str = str_replace('../' . $file['origin'], $file['published'], $str);
                            $str = str_replace($file['origin'], $file['published'], $str);
                        }
                    }

                    if (flock($handler, LOCK_EX)) {
                        file_put_contents($filePath, $str);
                    }

                    flock($handler, LOCK_UN);
                }

                fclose($handler);
            }
        }
    }
}
