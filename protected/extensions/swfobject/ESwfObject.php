<?php

class ESwfObject extends CWidget
{
    public $params;
    public $flashvars;
    public $attributes;
        
    public $swfFile;
    public $width;
    public $height;
    public $playerVersion;
    
    private $expressInstallFile;
    private $newLineJS;
    private $baseUrl;
    private $clientScript;

    private $_openTag;

    public $randomID;

    /**
     * Init the extension
     */
    public function init()
    {
        parent::init();

        $this->randomID  = '_' . uniqid();
        $this->newLineJS = "\n";

        ob_start();
        ob_implicit_flush(false);
        echo '<div id="'.$this->id.'">';

        $this->_openTag=ob_get_contents();
        ob_clean();
    }

    /**
     * Renders the content of the portlet.
     */
    public function run()
    {
        parent::run();
        $this->publishAssets();
        $this->registerClientScripts();

        $js = $this->createJsCode();
        $this->clientScript->registerScript('js_eswfobject' . $this->randomID, $js, CClientScript::POS_HEAD);

        $content=ob_get_clean();
        echo $this->_openTag;
        echo $content;
        echo "</div>\n";


    }
    
    /**
    * Publishes the assets
    */
    public function publishAssets()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
    }
    
    /**
    * Registers the external javascript files
    */
    public function registerClientScripts()
    {
        // add the scripts
        if ($this->baseUrl === '')
            throw new CException(Yii::t('ESwfObject', 'baseUrl must be set. This is done automatically by calling publishAssets()'));

        $this->clientScript = Yii::app()->getClientScript();
        $this->clientScript->registerScriptFile($this->baseUrl.'/swfobject.js');
        
        // set install express url
        $this->expressInstallFile = $this->baseUrl.'/expressInstall.swf';
    }
    
    /**
     * The javascript needed
     */
    protected function createJsCode(){

        // add the flashvars array

        if (is_object($this->flashvars)) {
            $vars = (array)$this->flashvars;
        } else {
            $vars = $this->flashvars;
        }

        $js = $this->newLineJS
            . 'var flashvars' . $this->randomID . ' = ' . $this->_json($vars) . ';' . $this->newLineJS;

        // add the params array
        if (is_object($this->params)) {
            $params = (array)$this->params;
        } else {
            $params = $this->params;
        }

        $js .= 'var params' . $this->randomID . ' = ' . $this->_json($params) . ';' . $this->newLineJS;
        
        // add the attributes array
        if (is_object($this->attributes)) {
            $attributes = (array)$this->attributes;
        } else {
            $attributes = $this->attributes;
        }

        $js .= 'var attributes' . $this->randomID . ' = ' . $this->_json($attributes) . ';' . $this->newLineJS;

        if (count($vars) > 0) {
            $js .= "for (key in flashvars".$this->randomID.") { " . $this->newLineJS
                .  "    if (flashvars".$this->randomID.".hasOwnProperty(key)) {" . $this->newLineJS
                .  "        flashvars".$this->randomID."[key] = encodeURIComponent(flashvars".$this->randomID."[key]);" . $this->newLineJS
                .  "    }" . $this->newLineJS
                .  "}" . $this->newLineJS;
        }
        
        // create the swfobject call
        $js .= $this->newLineJS;
        $js .= 'swfobject.embedSWF("' . $this->swfFile . '", "' . $this->id . '", "' . $this->width . '", ';
        $js .= '"' . $this->height . '", "' . $this->playerVersion . '", "' . $this->expressInstallFile . '", ';
        $js .= 'flashvars' . $this->randomID . ', params' . $this->randomID . ', attributes' . $this->randomID . ');' . $this->newLineJS;
        
        return $js;
    }

    private function _json($assoc_array)
    {
        foreach ($assoc_array as &$value) {
            if (!is_object($value) && !is_array($value)) continue;
            $value = json_encode($value);
        }

        return json_encode($assoc_array);
    }
}