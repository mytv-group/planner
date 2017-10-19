<?php

class TemplateEngine extends BaseComponent
{
    public $assetManager;
    public $clientScript;
    public $templatesPath;
    public $templateStylesPath;

    public function put($name)
    {
        $templatesPath = $this->getTemplatesPath();
        $stylesPath = $this->getTemplateStylesPath();

        if (file_exists($stylesPath.$name.'.css')) {
            $this->getClientScript()->registerCssFile(
                $this->getAssetManager()->publish($stylesPath.$name.'.css')
            );
        }

        echo file_get_contents($templatesPath.$name.'.lodash.tpl');
    }
}
