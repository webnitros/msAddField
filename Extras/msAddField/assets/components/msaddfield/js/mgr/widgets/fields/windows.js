msAddField.window.CreateField = function (config) {
    config = config || {}
    config.url = msAddField.config.connector_url

    Ext.applyIf(config, {
        title: _('msaddfield_field_create'),
        width: 800,
        cls: 'msaddfield_windows',
        baseParams: {
            action: 'mgr/field/create',
        }
    })
    msAddField.window.CreateField.superclass.constructor.call(this, config)
}
Ext.extend(msAddField.window.CreateField, msAddField.window.Default, {

    getFields: function (config) {

        var isCreate = false
        if (config.record !== undefined) {
            isCreate = true
        }
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},

            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('msaddfield_field_name'),
                                name: 'name',
                                id: config.id + '-name',
                                anchor: '99%',
                                allowBlank: false,
                                vtype: 'alphanum',
                                readOnly: isCreate
                            },
                            {
                                xtype: 'label',
                                html: 'На латинице',
                                cls: 'desc-under',
                            }
                        ],
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'msaddfield-combo-typefield',
                                fieldLabel: _('msaddfield_field_type'),
                                name: 'type',
                                id: config.id + '-type',
                                anchor: '100%',
                                allowBlank: false,
                                readOnly: isCreate
                            },
                            {
                                xtype: 'label',
                                html: 'Выберите тип поля в базе данных',
                                cls: 'desc-under',
                            }
                        ],
                    }]
            },
            /*{
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('msaddfield_field_alias_import'),
                                name: 'alias_import',
                                id: config.id + '-alias_import',
                                anchor: '99%',
                                allowBlank: false
                            },
                            {
                                xtype: 'label',
                                html: 'В алиасе нужно написать название колонки для импорта',
                                cls: 'desc-under',
                            },
                        ],
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('msaddfield_field_default'),
                                name: 'default',
                                id: config.id + '-default',
                                anchor: '100%',
                                allowBlank: false,
                                readOnly: isCreate
                            },
                            {
                                xtype: 'label',
                                html: 'Значение по умолчанию которые будет установленов в этом поле',
                                cls: 'desc-under',
                            }
                        ],
                    }]
            },*/





            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('msaddfield_field_title'),
                                description: _('msaddfield_field_title_desc'),
                                name: 'title',
                                id: config.id + '-title',
                                anchor: '99%',
                                allowBlank: false,
                            },
                            {
                                xtype: 'label',
                                html: 'Наименование в 1с, оно может отличаться от того что отображается на сайте',
                                cls: 'desc-under',
                            }

                        ],
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('msaddfield_field_help'),
                                name: 'help',
                                id: config.id + '-help',
                                anchor: '99%',
                                allowBlank: true,
                            },
                            {
                                xtype: 'label',
                                html: 'Подсказка будет выводиться при наведении на область с полем',
                                cls: 'desc-under',
                            }
                        ],
                    }]
            }
            , {
                xtype: 'xcheckbox',
                boxLabel: _('msaddfield_field_show_card'),
                name: 'show_card',
                id: config.id + '-show_card',
                checked: true,
            },
            {
                xtype: 'label',
                html: 'Отметьте чекбокс если хотите выводить в карточке товара это поле',
                cls: 'desc-under',
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('msaddfield_field_active'),
                name: 'active',
                id: config.id + '-active',
                checked: true,
            },{
                xtype: 'label',
                html: 'Если поле отключено то оно не будет отображать в карточке товара и не будет расширена карта полей',
                cls: 'desc-under',
            }/*, {
                xtype: 'xcheckbox',
                boxLabel: _('msaddfield_field_indexes'),
                name: 'indexes',
                id: config.id + '-indexes',
                checked: true,
                readOnly: isCreate
            }*/
        ]

    }
})
Ext.reg('msaddfield-field-window-create', msAddField.window.CreateField)

msAddField.window.UpdateField = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('msaddfield_field_update'),
        baseParams: {
            action: 'mgr/field/update',
        },
    })
    msAddField.window.UpdateField.superclass.constructor.call(this, config)

}
Ext.extend(msAddField.window.UpdateField, msAddField.window.CreateField)
Ext.reg('msaddfield-field-window-update', msAddField.window.UpdateField)