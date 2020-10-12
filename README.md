Компонент добавляет поля в таблицу ms2_products (всего 7 видов полей). Так же расширяет карту полей объекта msProductData и выводит поле в карточке товара автоматически.

Скачать можно отсюда
https://yadi.sk/d/2AAAOPXohQRQ1g

Типы полей которые может создавать компонент

```php
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
```