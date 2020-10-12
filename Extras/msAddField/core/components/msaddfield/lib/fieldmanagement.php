<?php

class msAddFieldFieldManagement
{
    /* @var msAddField $msix */
    public $msix = null;

    /* @var modX $modx */
    public $modx = null;

    /* @var string $field */
    private $field = null;
    private $className = null;
    /* @var string $meta */
    private $meta = null;
    /* @var string $field */
    private $fieldClear = null;
    private $prefix = null;
    private $columns = null;

    /* @var string $table */
    private $table = null;

    /**
     * @param msAddField $msix
     */
    function __construct(msAddField &$msix)
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
     * @return bool|msAddFieldFieldManagement
     */
    public function process($className, $field, $prefix = null, $meta = null)
    {
        $this->columns = null;
        $this->className = $className;
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
    public function isField()
    {
        $fields = $this->showFields();
        if (array_search($this->fieldClear, $fields)) {
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

        if (!$this->isField()) {
            $after = '';
            if ($this->prefix) {
                $after = $this->after();
            }

            $dbtype = $this->meta['dbtype'];

            $precision = $this->meta['precision'];
            $default = is_null($this->meta['default']) ? 'NULL' : $this->meta['default'];

            if ((empty($default) and $default != 0) and !empty($this->meta['null'])) {
                $default = NULL;
            }

            $precision = !empty($precision) ? "({$precision})" : '';
            $SQL = "ALTER TABLE {$this->table} ADD {$this->field} {$dbtype}{$precision} NULL DEFAULT {$default}{$after};";
            $this->sql($SQL, __METHOD__);
        }
        return $this;
    }


    /**
     * @param string $name
     * @param string $className
     * @return bool
     */
    public function removeField()
    {
        if ($this->isField()) {
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
        if ($this->isField()) {
            if (!$this->hasIndex()) {
                $this->sql("ALTER TABLE {$this->table} ADD INDEX ({$this->field})", __METHOD__);
            }
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasIndex()
    {
        if ($this->table and $this->field) {
            $q = $this->modx->prepare("SHOW INDEX FROM {$this->table} WHERE key_name = '{$this->fieldClear}';");
            $q->execute();
            $rows = $q->fetchAll(PDO::FETCH_ASSOC);
            return count($rows) > 0 ? true : false;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function removeIndex()
    {
        if ($this->hasIndex()) {
            $this->sql("ALTER TABLE {$this->table} DROP INDEX {$this->field}", __METHOD__);
        }
        return true;
    }

    /**
     *
     */
    public function generateMap()
    {
        $manager = $this->getManager();
        $map = array('fields' => array(), 'fieldMeta' => array());
        if ($manager) {
            $q = $this->modx->newQuery('MsfmFields');
            $q->where(array('enable' => 1));
            $q->sortby('rank', 'ASC');
            if ($fields = $this->modx->getCollection('MsfmFields', $q)) {
                foreach ($fields as $field) {
                    $null = $field->dbnull ? 'true' : 'false';
                    $key = $manager->getIndex('');
                    $default = '';
                    $defaultType = $this->modx->driver->getPhpType($field->dbtype);
                    $phpType = $field->xtype ? $this->xtypeToPhpType($field->xtype, $defaultType) : $defaultType;
                    if ($field->dbdefault == 'user_defined') {
                        $default = $field->default_value;
                    }

                    switch ($defaultType) {
                        case 'integer':
                        case 'boolean':
                        case 'bit':
                            $default = $default != '' ? (integer)$default : 0;
                            break;
                        case 'float':
                        case 'numeric':
                            $default = $default != '' ? (float)$default : 0;
                            break;
                        default:
                            break;
                    }
                    $map['fields'][$field->name] = $default;
                    $map['fieldMeta'][$field->name] = array();
                    $map['fieldMeta'][$field->name]['dbtype'] = $field->dbtype;
                    $map['fieldMeta'][$field->name]['precision'] = $field->dbprecision;
                    $map['fieldMeta'][$field->name]['phptype'] = $phpType;
                    $map['fieldMeta'][$field->name]['default'] = $default;
                    $map['fieldMeta'][$field->name]['null'] = (!empty($null) && strtolower($null) !== 'false') ? true : false;
                }
            }
            $manager->setMap($map);
            $manager->outputMap($this->config['ms2PluginsCorePath']);
        }
    }

    /**
     * @param string $sql
     * @return bool
     */
    private function sql($sql, $method)
    {
        if ($this->table and $this->field) {
            if ($this->modx->exec($sql) !== false) {
                return true;
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $sql);
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Columps ' . print_r($this->showFields(), 1));
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error {$this->table}->{$this->field}: " . print_r($this->modx->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
                throw new Exception('[' . $method . '] ' . print_r($this->modx->errorInfo(),1));
            }
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

        $data = array(
            'fields' => $fields,
            'fieldMeta' => $fieldMeta,
        );
        if ($indexes) {
            $data['indexes'] = $indexes;
        }


        $cache = $this->modx->getCacheManager();

        $content = $this->getTemplateProductDataXtype($xtype, date('d.m.Y', time()));
        if (!$cache->writeFile($pluginsAssetsPath, $content)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось создать файл {$pluginsAssetsPath}", '', __METHOD__, __FILE__, __LINE__);
        }
        $content = $this->getTemplateProductData($data, date('d.m.Y', time()));
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
     * Return the class platform template for the class files.
     *
     * @access public
     * @return string The class platform template.
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


        $template = <<<EOD
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
        return $template;
    }

    /**
     * Return the class platform template for the class files.
     *
     * @access public
     * @return string The class platform template.
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