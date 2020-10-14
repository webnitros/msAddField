<?php

class msAddFieldManagement
{
    /* @var msAddField $msix */
    public $msix;

    /* @var modX $modx */
    public $modx;

    /* @var string $field */
    private $field;
    /* @var array $meta */
    private $meta;
    /* @var string $field */
    private $fieldClear;
    private $prefix;
    private $columns;

    /* @var string $table */
    private $table;

    /**
     * @param msAddField $msix
     */
    public function __construct($msix)
    {
        $this->msix = $msix;
        $this->modx = $msix->modx;
    }

    /**
     * Инициализация
     * @param string $field
     * @param string $className
     * @param string|null $prefix
     * @param array|null $meta
     * @return bool|msAddFieldManagement
     */
    public function process($className, $field, $prefix = null, $meta = null)
    {
        $this->columns = null;
        $this->meta = $meta;
        $this->prefix = $prefix;
        $this->fieldClear = $field;
        $this->field = $this->modx->escape($field);
        if (!$table = $this->modx->getTableName($className)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Не удалось получить таблицу");
            return false;
        }
        $this->table = $table;
        return $this;
    }

    /**
     * Получения списка полей в таблице
     * @return array
     */
    public function showFields()
    {
        if (is_null($this->columns)) {
            $q = $this->modx->prepare("SHOW COLUMNS FROM {$this->table};");
            $q->execute();
            $rows = $q->fetchAll(PDO::FETCH_ASSOC);
            $this->columns = array_column($rows, 'Field');
        }
        return $this->columns;
    }

    /**
     * Проверка существования поля в таблице
     * @return bool
     */
    public function hasField()
    {
        $fields = $this->showFields();
        if (in_array($this->fieldClear, $fields, true)) {
            return true;
        }
        return false;
    }

    /**
     * Определения добавления следующего поля по префиксу
     * @return bool|null|string
     */
    public function after()
    {
        if (!$fields = $this->showFields()) {
            return false;
        }

        $isFindField = null;
        foreach ($fields as $field) {
            if (strripos($field, $this->prefix) !== false) {
                $isFindField = $field;
            }
        }
        return $isFindField ? " AFTER {$this->modx->escape($isFindField)}" : null;
    }

    /**
     * Добавление поля
     * @return $this
     * @throws Exception
     */
    public function addField()
    {

        if (!$this->hasField()) {
            $after = '';
            if ($this->prefix) {
                $after = $this->after();
            }

            $dbtype = $this->meta['dbtype'];

            $precision = $this->meta['precision'];
            $default = is_null($this->meta['default']) ? 'NULL' : $this->meta['default'];

            if ((empty($default) && $default !== 0) && !empty($this->meta['null'])) {
                $default = NULL;
            }

            $precision = !empty($precision) ? "({$precision})" : '';
            $SQL = "ALTER TABLE {$this->table} ADD {$this->field} {$dbtype}{$precision} NULL DEFAULT {$default}{$after};";
            $this->sql($SQL, __METHOD__);
        }
        return $this;
    }


    /**
     * Удаление поля
     * @return bool
     * @throws Exception
     */
    public function removeField()
    {
        if ($this->hasField()) {
            $this->sql("ALTER TABLE {$this->table} DROP COLUMN {$this->field}", __METHOD__);
        }
        return true;
    }

    /**
     * Добавление индекса для поля
     * @return $this
     * @throws Exception
     */
    public function addIndex()
    {
        if ($this->hasField() && !$this->hasIndex()) {
            $this->sql("ALTER TABLE {$this->table} ADD INDEX ({$this->field})", __METHOD__);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasIndex()
    {
        if ($this->table && $this->field) {
            $q = $this->modx->prepare("SHOW INDEX FROM {$this->table} WHERE key_name = '{$this->fieldClear}';");
            $q->execute();
            $rows = $q->fetchAll(PDO::FETCH_ASSOC);
            return count($rows) > 0;
        }
        return false;
    }

    /**
     * Удаление индекса
     * @return bool
     * @throws Exception
     */
    public function removeIndex()
    {
        if ($this->hasIndex()) {
            $this->sql("ALTER TABLE {$this->table} DROP INDEX {$this->field}", __METHOD__);
        }
        return true;
    }

    /**
     * Выполнение SQL
     * @param $sql
     * @param $method
     * @return bool
     * @throws Exception
     */
    private function sql($sql, $method)
    {
        if ($this->table && $this->field) {
            if ($this->modx->exec($sql) !== false) {
                return true;
            }

            $this->modx->log(modX::LOG_LEVEL_ERROR, $sql);
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Columps ' . print_r($this->showFields(), 1));
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error {$this->table}->{$this->field}: " . print_r($this->modx->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
            throw new Exception('[' . $method . '] ' . print_r($this->modx->errorInfo(), 1));
        }
        return false;
    }


    /**
     * Расширение таблицы minishop
     */
    public function extensionProduct()
    {
        $pluginsCorePath = $this->msix->config['pluginsCorePath'];
        $pluginsAssetsPath = $this->msix->config['pluginsAssetsPath'];

        $fields = null;
        $fieldMeta = null;
        $indexes = null;
        $xtype = null;

        /* @var msafField $object */
        $q = $this->modx->newQuery('msafField');
        if ($objectList = $this->modx->getCollection('msafField', $q)) {
            foreach ($objectList as $object) {
                if ($object->hasField()) {
                    $name = $object->get('name');
                    $meta = $object->getMeta();
                    $fieldMeta[$name] = $meta;
                    $fields[$name] = $meta['default'];

                    if ($object->isIndexes()) {
                        $indexes[$name] = $object->getIndexData();
                    }
                    if ($object->isShowProduct()) {
                        $xtype[$name] = $object->getMetaXtype();
                    }
                }
            }
        }

        $data = array(
            'fields' => $fields,
            'fieldMeta' => $fieldMeta,
        );
        if ($indexes) {
            $data['indexes'] = $indexes;
        }


        $cache = $this->modx->getCacheManager();

        $content = $this->getTemplateProductDataXtype($xtype, date('d.m.Y'));
        if (!$cache->writeFile($pluginsAssetsPath, $content)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось создать файл {$pluginsAssetsPath}", '', __METHOD__, __FILE__, __LINE__);
        }
        $content = $this->getTemplateProductData($data, date('d.m.Y'));
        if (!$cache->writeFile($pluginsCorePath, $content)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось создать файл {$pluginsCorePath}", '', __METHOD__, __FILE__, __LINE__);
        }

        $this->clearCache();
        return true;
    }

    private function clearCache()
    {
        $this->modx->cacheManager->refresh(array(
            'system_settings' => array(),
            'lexicon_topics' => array(),
            'scripts' => array(),
        ));
    }

    /**
     * @param $data
     * @param $date
     * @return string
     */
    private function getTemplateProductDataXtype($data, $date)
    {
        $Fields = '';
        $Columns = '';
        foreach ($data as $field => $options) {
            $xtype = $options['xtype'];
            #unset($options['xtype']);
            $decimalPrecision = '';
            if ($options['decimalPrecision']) {
                $decimalPrecision = '
                    decimalPrecision: ' . $options['decimalPrecision'];
            }

            $Fields .= "
                {$field}: {
                    xtype: '{$xtype}',
                    description: '<b>[[+{$field}]]</b><br />' + _('ms2_product_{$field}_help'),{$decimalPrecision}
                },
            ";
            $Columns .= "
                {$field}: {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: '{$xtype}',
                        name: '{$field}'
                    }
                },
            ";
        }


        return <<<EOD
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: $date
 * Time: 1:39
 */ 
 miniShop2.plugin.mcCml = {
    getFields: function () {
        return {
            $Fields
        } 
    },
    getColumns: function () {
        return {
            $Columns
        }
    }
}
EOD;
    }

    /**
     * @param $data
     * @param $date
     * @return string
     */
    private function getTemplateProductData($data, $date)
    {
        return '<?php 
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: ' . $date . '
 * Time: 1:39
 */ 
 return ' . var_export($data, true) . ';';
    }

}