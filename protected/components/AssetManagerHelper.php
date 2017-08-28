<?php

class AssetManagerHelper extends CApplicationComponent
{
    public $assetManager;
    public $clientScript;
    public $basePath;

    private function getAssetManager()
    {
        return $this->assetManager->__invoke();
    }

    private function getClientScript()
    {
        return $this->clientScript->__invoke();
    }

    private function getBasePath()
    {
        return $this->basePath();
    }

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
}
