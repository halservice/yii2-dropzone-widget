<?php

namespace xj\dropzone;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Dropzone Widget
 * @see http://www.dropzonejs.com
 * @see http://www.dropzonejs.com/#event-list
 */
class Dropzone extends Widget {

    /**
     * upload file to URL
     * @var string 
     * @example
     * http://xxxxx/upload/
     * ['article/upload']
     * ['upload']
     */
    public $url;

    /**
     * 是否渲染Tag
     * @var bool
     */
    public $renderTag = true;

    /**
     * uploadify js options
     * @var array
     * @example 
     * [
     * 'url' => 'xxx',
     * ]
     * @see http://www.uploadify.com/documentation/
     */
    public $jsOptions = [];

    /**
     * form input name
     * @var []
     * @see http://www.dropzonejs.com/#config-paramName
     */
    public $paramName = 'Filedata';
    public $wrapTag = 'div';
    public $wrapOptions = [];
    public $formNotiMessage = 'Drop files here or click to upload.';
    public $formNotiMessageOptions = [];
    public $formOptions = [];

    /**
     * Initializes the widget.
     */
    public function init() {
        if (empty($this->id)) {
            $this->id = $this->getId();
        }
        //register js css
        $assets = DropzoneAsset::register($this->view);

        //init options
        $this->initJsOptions($assets);

        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run() {
        $this->registerScripts();
        if ($this->renderTag === true) {
            echo $this->renderTag();
        }
    }

    /**
     * init JsOptions
     * @param DropzoneAsset $assets
     * @return void
     */
    private function initJsOptions($assets) {
        if (!isset($this->jsOptions['paramName'])) {
            $this->jsOptions['paramName'] = $this->paramName;
        }
    }

    /**
     * render file input tag
     * @return string
     */
    private function renderTag() {
        $id = $this->id;
        $formNotiMessage = $this->formNotiMessage;
        $wrapOptions = $this->wrapOptions;
        $formOptions = $this->formOptions;
        $formOptions['id'] = $id;
        Html::addCssClass($this->formNotiMessageOptions, 'dz-message');

        $form = Html::beginForm($this->url, 'POST', $formOptions);
        $form .= Html::tag('div', $formNotiMessage, $this->formNotiMessageOptions);
        $form .= Html::endForm();
        return Html::tag($this->wrapTag, $form, $wrapOptions);
    }

    /**
     * register script
     */
    private function registerScripts() {
        $id = $this->id;
        $jsonOptions = Json::encode($this->jsOptions);
        $script = <<<EOF
$('#{$id}').dropzone({$jsonOptions});
EOF;
        $this->view->registerJs($script, View::POS_READY);
    }

}
