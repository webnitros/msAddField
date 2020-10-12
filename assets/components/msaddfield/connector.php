<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var msAddField $msAddField */
$msAddField = $modx->getService('msAddField', 'msAddField', MODX_CORE_PATH . 'components/msaddfield/model/');
$modx->lexicon->load('msaddfield:default');

// handle request
$corePath = $modx->getOption('msaddfield_core_path', null, $modx->getOption('core_path') . 'components/msaddfield/');
$path = $modx->getOption('processorsPath', $msAddField->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);