<?

Yii::import("ext.EAjaxUpload.EAjaxUpload");

/*
* This class appends scripts to extension
*/
class AjaxUploadWidget extends EAjaxUpload {
    public $id = 'fileUploader';
    public $postParams = [];
    public $config = [];
    public $css = null;

    public function init()
    {
        $cls = substr_replace(
            get_class($this),
            '',
            strrpos(get_class($this), 'Widget', -1),
            strlen('Widget')
        );

        /*uncamelize by splitter '-' */
        $name = strtolower(
                preg_replace('/(?!^)[[:upper:]][[:lower:]]/',
                '$0',
                preg_replace('/(?!^)[[:upper:]]+/',
                    '-'.'$0',
                    $cls
                )
            )
        );

        foreach (['js', 'css'] as $dim) {
            $file = '/' . $dim . '/widgets-internal/' . $name . '.' . $dim;
            if (file_exists(INDEX_PATH . $file)) {
                Yii::app()->assets->register($file);
            }
        }

        parent::init();
    }

    public function run()
    {
        if (empty($this->config['action'])) {
            throw new CException('EAjaxUpload: param "action" cannot be empty.');
        }

        if(empty($this->config['allowedExtensions'])) {
            throw new CException('EAjaxUpload: param "allowedExtensions" cannot be empty.');
        }

        if(empty($this->config['sizeLimit'])) {
            throw new CException('EAjaxUpload: param "sizeLimit" cannot be empty.');
        }

        unset($this->config['element']);

        echo '<div id="'.$this->id.'"><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>';
        $assets = Yii::getPathOfAlias('ext.EAjaxUpload.assets');
        $baseUrl = Yii::app()->assetManager->publish($assets);

        Yii::app()->clientScript->registerScriptFile($baseUrl . '/fileuploader.js', CClientScript::POS_HEAD);

        $this->css= (!empty($this->css))
            ? $this->css
            : $baseUrl.'/fileuploader.css';
        Yii::app()->clientScript->registerCssFile($this->css);

        $postParams = [
            'PHPSESSID' => session_id(),
            'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
        ];

        if (isset($this->postParams)) {
            $postParams = array_merge(
                $postParams,
                $this->postParams
            );
        }

        /*
         * js functions that uses (onComplete, ...) are defined in widget autoattaching assets
         * (js/widgets-internal/ajax-upload.js)
         */
        $config = [
            'debug' => false,
            'element' => 'js:document.getElementById("'.$this->id.'")',
            'minSizeLimit' => 1 * 1024,// minimum file size in bytes
            'onComplete' => 'js:onComplete',
            'messages' => [
                'typeError' => '{file} has invalid extension. Only {extensions} are allowed.',
                'sizeError' => '{file} is too large, maximum file size is {sizeLimit}.',
                'minSizeError' => '{file} is too small, minimum file size is {minSizeLimit}.',
                'emptyError' => '{file} is empty, please select files again without it.',
                'onLeave' => 'The files are being uploaded, if you leave now the upload will be cancelled.'
            ],
            'showMessage' => 'js:function(message){ alert(message); }'
        ];

        $config = array_merge($config, $this->config);
        $config['params'] = $postParams;
        $params = CJavaScript::encode($postParams);
        $config = CJavaScript::encode($config);

        Yii::app()->getClientScript()->registerScript(
            "FileUploader_".$this->id,
            "if (!Array.isArray(window.widgets)) { window.widgets = []; };"
            . " if (!Array.isArray(window.widgets.AjaxUpload)) { window.widgets.AjaxUpload = []; };"
            . "window.widgets.AjaxUpload.push({id: ".$this->id.", config: ".$config."});",
            CClientScript::POS_LOAD
        );
    }
}
