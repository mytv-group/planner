<?php
/**
 * @author Bryan Jayson Tan <admin@bryantan.info>.
 * @link http://bryantan.info
 * @date 12/24/12
 * @time 4:10 PM
 *
 * datetime picker widget
 * @src http://trentrichardson.com
 *
 * inherit concept from CJuiDatePicker
 * @see CJuiDatePicker
 */
Yii::import('zii.widgets.jui.CJuiDatePicker');

class BJuiDateTimePicker extends CJuiDatePicker
{
    public $themeName=null;
    /**
     * the url for the theme
     * @var null
     */
    public $themeUrl=null;

    /**
     * adds the sliderAccess plugin to sliders within timepicker
     * @var bool
     */
    public $enableSliderAccess=false;

    /**
     * Object to pass to sliderAccess when used.
     * @var array
     */
    public $sliderAccessOptions=array();

    /**
     * available options are datetime|time
     * @var string
     */
    public $type='datetime';

    /**
     * assets url
     * @var null
     */
    private $_assetsUrl=null;

    public function init(){
        parent::init();

        $this->registerAssets();
        $this->registerTheme();
    }

    /**
     * inherit in CJuiDatePicker concept
     */
    public function run()
    {
        list($name,$id)=$this->resolveNameID();

        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];

        if ($this->flat===false)
        {
            if($this->hasModel()){
                echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
                $attribute = $this->attribute;
            }else{
                echo CHtml::textField($name,$this->value,$this->htmlOptions);
            }
        }
        else
        {
            if($this->hasModel())
            {
                echo CHtml::activeHiddenField($this->model,$this->attribute,$this->htmlOptions);
                $attribute = $this->attribute;
                $this->options['defaultValue'] = $this->model->$attribute;
            }
            else
            {
                echo CHtml::hiddenField($name,$this->value,$this->htmlOptions);
                $this->options['defaultValue'] = $this->value;
            }

            if (!isset($this->options['onSelect']))
                $this->options['onSelect']=new CJavaScriptExpression("function( selectedDateTime ) { jQuery('#{$id}').val(selectedDateTime);}");

            $id = $this->htmlOptions['id'] = $id.'_container';
            $this->htmlOptions['name'] = $name.'_container';

            echo CHtml::tag('div', $this->htmlOptions, '');
        }

        $this->registerSliderAccess();

        $options=CJavaScript::encode($this->options);
        $js = "jQuery('#{$id}').{$this->type}picker($options);";

        $this->registeri18nFile();

        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__.'#'.$id, $js);
    }

    /**
     * register assets and default script/script files
     * @return mixed
     */
    protected function registerAssets(){
        if ($this->_assetsUrl===null){
            $this->_assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__).'/assets');

            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile($this->_assetsUrl.'/js/jquery-ui-timepicker-addon.js');
            $cs->registerCssFile($this->_assetsUrl.'/css/style.css');

            return $this->_assetsUrl;
        }
    }

    /**
     * register theme if themeUrl or themeName is not empty
     * if themeName is set, register css file under assets/css/<themeName>
     * if themeUrl is set, register css file from $this->themeUrl
     */
    protected function registerTheme(){
        if ($this->themeUrl || $this->themeName){
            if ($this->themeName){
                $themeUrl="{$this->_assetsUrl}/css/{$this->themeName}/jquery.ui.min.css";
            }else{
                $themeUrl=$this->themeUrl;
            }
            Yii::app()->getClientScript()->registerCssFile($themeUrl);
        }
    }

    /**
     * register slider access
     */
    protected function registerSliderAccess(){
        // if enableSliderAccess = true, add other options
        if ($this->enableSliderAccess===true){
            $this->registerScriptFile('jquery-ui-sliderAccess.js');
            $this->options['addSliderAccess']=true;
            if (!isset($this->sliderAccessOptions['touchonly'])){
                $this->sliderAccessOptions['touchonly']=false;
            }
            $this->options['sliderAccessArgs']=$this->sliderAccessOptions;
        }
    }

    /**
     * register internalization file
     * file must be in assets/js folder
     */
    protected function registeri18nFile(){
        if ($this->language!='' && $this->language!='en'){
            $this->registerScriptFile("jquery.ui.timepicker.{$this->language}.js");
        }
    }

    /**
     * register script file under assets/js folder
     * a shortcut method to call registerScriptFile
     */
    protected function registerScriptFile($fileName,$position=CClientScript::POS_END){
        Yii::app()->getClientScript()->registerScriptFile($this->_assetsUrl.'/js/'.$fileName,$position);
    }
}
