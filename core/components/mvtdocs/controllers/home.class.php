<?php

/**
 * The home manager controller for mvtDocs.
 *
 */
class mvtDocsHomeManagerController extends modExtraManagerController
{
    /** @var mvtDocs $mvtDocs */
    public $mvtDocs;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('mvtdocs_core_path', null,
                $this->modx->getOption('core_path') . 'components/mvtdocs/') . 'model/mvtdocs/';
        $this->mvtDocs = $this->modx->getService('mvtdocs', 'mvtDocs', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('mvtdocs:default');
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
        return $this->modx->lexicon('mvtdocs');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->mvtDocs->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->mvtDocs->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/mvtdocs.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/files.grid.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/files.windows.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/links.windows.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/links.grid.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->mvtDocs->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        mvtDocs.config = ' . json_encode($this->mvtDocs->config) . ';
        mvtDocs.config.connector_url = "' . $this->mvtDocs->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "mvtdocs-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->mvtDocs->config['templatesPath'] . 'home.tpl';
    }
}