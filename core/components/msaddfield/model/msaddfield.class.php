<?php

class msAddField
{
    /** @var modX $modx */
    public $modx;

    /** @var array() $config */
    public $config = array();

    /**
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX $modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/msaddfield/';
        $assetsUrl = MODX_ASSETS_URL . 'components/msaddfield/';
        $assetsPath = MODX_ASSETS_PATH;

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'custom/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',

            // Для импорта
            'uploadPath' => $assetsPath . 'upload/',
            'uploadPathTmp' => $assetsPath . 'upload_tmp/',

            'pluginsCorePathIndex' => $this->modx->getOption('msaddfield_plugins_core_path_index', $config, MODX_CORE_PATH . 'components/minishop2/plugins/msaddfield/index.php'),
            'pluginsCorePath' => $this->modx->getOption('msaddfield_plugins_core_path', $config, MODX_CORE_PATH . 'components/minishop2/plugins/msaddfield/msproductdata.inc.php'),
            'pluginsAssetsPath' => $this->modx->getOption('msaddfield_plugins_assets_path', $config, MODX_ASSETS_PATH . 'components/minishop2/plugins/msaddfield/msproductdata.js'),
        ], $config);

        $this->modx->addPackage('msaddfield', $this->config['modelPath']);
        $this->modx->lexicon->load('msaddfield:default');
    }


    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return mixed The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/msaddfield/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
    }

    /**
     * Обработчик для событий
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function loadHandlerEvent(modSystemEvent $event, $scriptProperties = array())
    {
        switch ($event->name) {
            case 'OnMODXInit':
                if ($this->modx->context->key === 'mgr') {

                    // Добавление файлов если их нету
                    $this->addPluginsMinishop2();

                    if (!empty($_GET['a']) && ($_GET['a'] === 'resource/update' || $_GET['a'] === 'resource/create')) {
                        // Добавления описания в словари для полей в карточке товара
                        $this->addFieldsOptionsProduct();
                    }
                }
                break;
        }
    }

    /* @var msAddFieldManagement $fieldManagement */
    protected $fieldManagement;

    /**
     * Класс для управление полями в таблице ms2_products
     * @return msAddFieldManagement|null
     */
    public function loadFieldManagement()
    {
        if (is_null($this->fieldManagement)) {
            if (!class_exists('msAddFieldManagement')) {
                require_once dirname(__DIR__) . '/lib/msAddFieldManagement.php';
            }
            $this->fieldManagement = new msAddFieldManagement($this);
        }
        return $this->fieldManagement;
    }

    /**
     * Добавление файлов для плагинов в minishop2
     */
    public function addPluginsMinishop2()
    {

        // core msproductdata.inc.php
        $target = $this->config['pluginsCorePath'];
        if (!file_exists($target)) {
            $cache = $this->modx->getCacheManager();
            $cache->copyFile($this->config['corePath'] . 'elements/minishop2/core/msproductdata.inc.php', $target);
        }

        // core index.php
        $target = $this->config['pluginsCorePathIndex'];
        if (!file_exists($target)) {
            $cache = $this->modx->getCacheManager();
            $cache->copyFile($this->config['corePath'] . 'elements/minishop2/core/index.php', $target);
        }

        // assets
        $target = $this->config['pluginsAssetsPath'];
        if (!file_exists($target)) {
            $cache = $this->modx->getCacheManager();
            $cache->copyFile($this->config['corePath'] . 'elements/minishop2/assets/msproductdata.js', $target);
        }
    }


    /**
     * Добавление полей в опции чтобы показывались в карточке товаров
     */
    public function addFieldsOptionsProduct()
    {
        $addFields = [];
        $q = $this->modx->newQuery('msafField');
        $q->select('name,title,help');
        $q->where(array(
            'active' => 1,
            'show_card' => 1,
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $addFields[] = $row['name'];
            }
        }

        $values = $this->modx->getOption('ms2_product_extra_fields');
        $values = explode(',', $values);
        $values = array_map('trim', array_unique(array_filter(array_merge($values, $addFields))));
        $this->modx->setOption('ms2_product_extra_fields', implode(',', $values));
    }
}