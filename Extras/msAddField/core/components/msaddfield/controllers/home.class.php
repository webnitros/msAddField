<?php

/**
 * The home manager controller for msAddField.
 *
 */
class msAddFieldHomeManagerController extends modExtraManagerController
{
    /** @var msAddField $msAddField */
    public $msAddField;


    /**
     *
     */
    public function initialize()
    {
        $this->msAddField = $this->modx->getService('msAddField', 'msAddField', MODX_CORE_PATH . 'components/msaddfield/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['msaddfield:manager', 'msaddfield:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('msaddfield');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->msAddField->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/msaddfield.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/widgets/fields/grid.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/widgets/fields/windows.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->msAddField->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');

        $this->msAddField->config['date_format'] = $this->modx->getOption('msaddfield_date_format', null, '%d.%m.%y <span class="gray">%H:%M</span>');
        $this->msAddField->config['help_buttons'] = ($buttons = $this->getButtons()) ? $buttons : '';

        $this->addHtml('<script type="text/javascript">
        msAddField.config = ' . json_encode($this->msAddField->config) . ';
        msAddField.config.connector_url = "' . $this->msAddField->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "msaddfield-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .=  '<div id="msaddfield-panel-home-div"></div>';
        return '';
    }

    /**
     * @return string
     */
    public function getButtons()
    {
        $buttons = null;
        $name = 'msAddField';
        $path = "Extras/{$name}/_build/build.php";
        if (file_exists(MODX_BASE_PATH . $path)) {
            $site_url = $this->modx->getOption('site_url').$path;
            $buttons[] = [
                'url' => $site_url,
                'text' => $this->modx->lexicon('msaddfield_button_install'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1&encryption_disabled=1',
                'text' => $this->modx->lexicon('msaddfield_button_download'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1',
                'text' => $this->modx->lexicon('msaddfield_button_download_encryption'),
            ];
        }
        return $buttons;
    }
}