<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/msAddField/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/msaddfield')) {
            $cache->deleteTree(
                $dev . 'assets/components/msaddfield/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/msaddfield/', $dev . 'assets/components/msaddfield');
        }
        if (!is_link($dev . 'core/components/msaddfield')) {
            $cache->deleteTree(
                $dev . 'core/components/msaddfield/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/msaddfield/', $dev . 'core/components/msaddfield');
        }
    }
}

return true;