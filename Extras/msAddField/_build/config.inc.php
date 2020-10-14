<?php

if (!defined('MODX_CORE_PATH')) {
    $path = dirname(__FILE__);
    while (!file_exists($path . '/core/config/config.inc.php') && (strlen($path) > 1)) {
        $path = dirname($path);
    }
    define('MODX_CORE_PATH', $path . '/core/');
}

return [
    'name' => 'msAddField',
    'name_lower' => 'msaddfield',
    'version' => '1.0.5',
    'release' => 'beta',
    // Install package to site right after build
    'install' => true,
    'encryption_enable' => false,
    'encryption' => array(
        'username' => '',
        'api_key' => '',
    ),
    // Which elements should be updated on package upgrade
    'update' => [
        'chunks' => false,
        'menus' => true,
        'plugins' => true,
        'resources' => false,
        'settings' => true,
        'snippets' => false,
        'templates' => false,
        'widgets' => false,
        'policies' => false,
        'events' => false,
    ],
    // Which elements should be static by default
    'static' => [
        'plugins' => false,
        'snippets' => false,
        'chunks' => false,
    ],
    // Log settings
    'log_level' => !empty($_REQUEST['download']) ? 0 : 3,
    'log_target' => php_sapi_name() == 'cli' ? 'ECHO' : 'HTML',
    // Download transport.zip after build
    'download' => !empty($_REQUEST['download']),
    // Copy file
    'copy' => !empty($_REQUEST['copy']),
    'copy_server' => '',
    #'copy_server' => 'http://s16305.h4.modhost.pro/copy_package.php',
    'auto_install' => false,
];