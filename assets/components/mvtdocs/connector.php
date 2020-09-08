<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var mvtDocs $mvtDocs */
$mvtDocs = $modx->getService('mvtdocs', 'mvtDocs', $modx->getOption('mvtdocs_core_path', null,
        $modx->getOption('core_path') . 'components/mvtdocs/') . 'model/mvtdocs/'
);
$modx->lexicon->load('mvtdocs:default');

// handle request
$corePath = $modx->getOption('mvtdocs_core_path', null, $modx->getOption('core_path') . 'components/mvtdocs/');
$path = $modx->getOption('processorsPath', $mvtDocs->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));