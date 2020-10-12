<?php
if (!empty($_GET['session_id'])) {
    session_id($_GET['session_id']);
    session_start();
}
define('MODX_API_MODE', true);
require 'index.php';
if ($_GET['auth']) {
    exit($modx->toJSON(array(
        'success' => true,
        'message' => 'Вернули сессию',
        'data' => array(
            'session_name' => session_name(),
            'session_id' => session_id()
        ),
    )));
}
$url = $_GET['url'];
if (empty($url)) {
    exit('Url not correct ' . $url);
}
$content = file_get_contents($url);
$cache = $modx->getCacheManager();
$filename = basename($url);
$path = MODX_CORE_PATH . 'packages/' . $filename;
/* @var modUser $User */
if (!$User = $modx->getObject('modUser', array('sudo' => 1))) {
    exit('User not found');
}
if (!$User->isAuthenticated('mgr')) {
    $User->addSessionContext('mgr');
}
if (!$User->isAuthenticated('mgr')) {
    exit($modx->toJSON(array(
        'success' => false,
        'message' => 'Не удалось авторизоватсья',
        'data' => array(
            'is' => $User->isAuthenticated('mgr')
        ),
    )));
}
if (file_exists($path)) {
    unlink($path);
}
$cache->writeFile($path, $content);
/* @var modProcessorResponse $response */
$response = $modx->runProcessor('workspace/packages/scanlocal');
session_destroy();
exit($modx->toJSON($response->response));