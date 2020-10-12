<?php

class msafField extends xPDOSimpleObject
{
    /* @var msAddFieldFieldManagement $manager */
    protected $manager = null;

    public $metaData = array(
        'price' => array(
            'title' => 'Цена (0.00)',
            'fieldMeta' => array(
                'default' => '0.0',
                'dbtype' => 'decimal',
                'precision' => '12,2',
                'phptype' => 'float',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'numberfield',
                'decimalPrecision' => 2
            )
        ),
        'decimal' => array(
            'title' => 'Вес и другие размеры (0.000)',
            'fieldMeta' => array(
                'default' => '0.000',
                'dbtype' => 'decimal',
                'precision' => '13,3',
                'phptype' => 'float',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'numberfield',
                'decimalPrecision' => 3
            )
        ),
        'numberfield' => array(
            'title' => 'Цифры',
            'fieldMeta' => array(
                'default' => 0,
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'numberfield',
            )
        ),
        'textfield' => array(
            'title' => 'Текст до 255 символов',
            'fieldMeta' => array(
                'default' => null,
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'textfield',
            )
        ),
        'tinyint' => array(
            'title' => 'Болева (Да или Нет)',
            'fieldMeta' => array(
                'default' => 0,
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
            ),
            'xtype' => array(
                'xtype' => 'xcheckbox',
                'inputValue' => 1,
                'checked' => 'parseInt(config.record.new)',
            )
        ),
        'json' => array(
            'title' => 'Множественные значения',
            'fieldMeta' => array(
                'dbtype' => 'text',
                'phptype' => 'json',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'minishop2-combo-options',
            )
        ),
        'textarea' => array(
            'title' => 'Текстовое значение большое',
            'fieldMeta' => array(
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => true,
            ),
            'xtype' => array(
                'xtype' => 'textarea',
            )
        ),
    );

    /**
     * @return boolean
     */
    public function isIndexes()
    {
        return $this->get('indexes');
    }

    /**
     * @return array
     */
    public function getIndexData()
    {
        $name = $this->get('name');
        return array(
            'alias' => $name,
            'primary' => false,
            'unique' => false,
            'type' => 'BTREE',
            'columns' => array(
                $name => array(
                    'length' => '',
                    'collation' => 'A',
                    'null' => false,
                ),
            )
        );
    }

    /**
     * @return bool|array
     */
    public function getMeta()
    {
        $type = $this->get('type');
        if (isset($this->metaData[$type])) {
            $value = $this->metaData[$type];
            return $value['fieldMeta'];
        }
        return false;
    }

    /**
     * @return bool|array
     */
    public function getMetaXtype()
    {
        $type = $this->get('type');
        if (isset($this->metaData[$type])) {
            $value = $this->metaData[$type];
            return $value['xtype'];
        }
        return false;
    }

    private function loadFieldManagement()
    {
        if (is_null($this->manager)) {
            /* @var msAddField $msix */
            $msix = $this->xpdo->getService('msaddfield', 'msAddField', MODX_CORE_PATH . 'components/msaddfield/model/');
            if (!$this->manager = $msix->loadFieldManagement()) {
                return false;
            }
        }
        return $this->manager;
    }

    /**
     * @return bool
     */
    public function extension()
    {
        $this->updateLexicon();
        return $this->loadFieldManagement()->extensionProduct();
    }

    /**
     * @return array
     */
    private function getMinishopField()
    {
        $SystemSetting = $this->xpdo->getObject('modSystemSetting', 'ms2_product_extra_fields');
        $value = $SystemSetting->get('value');
        return array_map('trim', explode(',', $value));
    }

    /**
     * Сохранение новый полей для вывода в карточке товара
     * @param array $new_array
     * @return bool
     */
    private function saveSettingField(array $new_array = array())
    {
        if (count($new_array) > 0) {
            $value = implode(',', $new_array);
            /* @var modSystemSetting $SystemSetting */
            if ($SystemSetting = $this->xpdo->getObject('modSystemSetting', 'ms2_product_extra_fields')) {
                if ($value != $SystemSetting->get('value')) {
                    $SystemSetting->set('value', $value);
                    return $SystemSetting->save();
                } else {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Добавление в словари имя поля для minishop
     * @return modLexiconEntry
     */
    private function getLexicon($name)
    {
        $criteria = array(
            'name' => $name,
            'namespace' => 'minishop2',
            'topic' => 'product',
            'language' => 'ru',
        );

        /* @var modLexiconEntry $object */
        if (!$object = $this->xpdo->getObject('modLexiconEntry', $criteria)) {
            $object = $this->xpdo->newObject('modLexiconEntry');
            $object->fromArray($criteria);
        }
        return $object;
    }

    /**
     * Вернет ключи для словарей
     * @return array
     */
    private function keysLexicon()
    {
        $name = $this->get('name');
        $title = $this->get('title');
        $help = $this->get('help');
        $array = array(
            'ms2_product_' . $name => $title,
            'ms2_product_' . $name . '_help' => $help
        );
        return $array;
    }

    /**
     * Добавление записи в словарь minishop
     * @return boolean
     */
    private function updateLexicon()
    {
        if ($array = $this->keysLexicon()) {
            foreach ($array as $key => $value) {
                if ($object = $this->getLexicon($key)) {
                    $object->set('value', $value);
                    $object->set('editedon', date('Y-m-d h:i:s'));
                    if (!$object->save()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Удаление записи из словаря minishop
     * @return boolean
     */
    private function removeLexicon()
    {
        if ($array = $this->keysLexicon()) {
            foreach ($array as $key => $value) {
                if ($object = $this->getLexicon($key)) {
                    if (!$object->remove()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }


    /**
     * @return bool
     */
    public function addSettingField()
    {
        $fields = $this->getMinishopField();
        $fields[] = $this->get('name');
        return $this->saveSettingField(array_unique(array_filter($fields)));
    }


    /**
     * Удаление поля в minishop2
     * @return bool
     */
    public function removeSettingField()
    {
        $fields = $this->getMinishopField();
        $name = $this->get('name');
        foreach ($fields as $k => $field) {
            if ($name == $field) {
                unset($fields[$k]);
            }
        }
        return $this->saveSettingField($fields);
    }


    /**
     * @return bool
     */
    public function isShowProduct()
    {
        return $this->get('show_card');
    }

    /**
     * Проверка существования поля в таблице
     * @return bool
     */
    public function isField()
    {
        if ($manager = $this->getManager()) {
            return $manager->isField();
        }
        return false;
    }

    /**
     * @return bool|msAddFieldFieldManagement
     */
    private function getManager()
    {
        if ($manager = $this->loadFieldManagement()) {
            return $manager->process('msProductData', $this->get('name'), null, $this->getMeta());
        }
        return false;
    }

    /**
     * Добавление поля в таблицы и расширение настроек minishop2
     */
    public function addField()
    {
        if ($manager = $this->getManager()) {
            $manager->addField();
            $this->updateLexicon();
            if ($this->get('indexes')) {
                $manager->addIndex();
            }
        }
        $this->loadFieldManagement()->extensionProduct();
    }

    /**
     * Добавление индекса для поля
     */
    public function removeIndex()
    {
        if ($manager = $this->getManager()) {
            $manager->removeIndex();
        }
    }


    /**
     * Проверка разрешено ли индексировать поля
     * @return bool
     */
    public function allowedIndexed()
    {
        $type = $this->type;
        return ($type != 'textarea' and $type != 'json');
    }


    /**
     * Добавление индекса для поля
     */
    public function addIndex()
    {
        if ($manager = $this->getManager()) {
            $manager->addIndex();
        }
    }

    /**
     * Проверка есть ли индекс на поле
     */
    public function hasIndex()
    {
        if ($manager = $this->getManager()) {
            return $manager->hasIndex();
        }
        return false;
    }


    /**
     * Удаление поля в таблицы и расширение настроек minishop2
     */
    public function removeField()
    {
        if ($manager = $this->getManager()) {
            $manager->removeIndex();
            $manager->removeField();
            $this->removeLexicon();
            $this->removeSettingField();
        }
        $this->loadFieldManagement()->extensionProduct();
    }

    public function save($cacheFlag = null)
    {
        if (!$this->isShowProduct()) {
            $this->removeSettingField();
        }
        return parent::save($cacheFlag); // TODO: Change the autogenerated stub
    }

}