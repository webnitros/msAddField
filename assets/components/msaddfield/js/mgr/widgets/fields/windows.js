msAddField.window.CreateField = function (config) {
    config = config || {}
    config.url = msAddField.config.connector_url

    if (config.record === undefined || config.record.object === 'undefined') {
        config.id = 'msaddfield-field'
    }

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
                                id: config.id +'-name',
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
                                listeners: {
                                    'keyup': {
                                        fn: function (f) {
                                            var title = Ext.util.Format.stripTags(f.getValue())
                                            this.translitAlias(title)
                                        }, scope: this
                                    }
                                    // also do realtime transliteration of alias on blur of pagetitle field
                                    // as sometimes (when typing very fast) the last letter(s) are not catched
                                    , 'blur': {
                                        fn: function (f, e) {
                                            var title = Ext.util.Format.stripTags(f.getValue())
                                            this.translitAlias(title)
                                        }, scope: this
                                    }
                                }

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
            }, {
                xtype: 'label',
                html: 'Если поле отключено то оно не будет отображать в карточке товара и не будет расширена карта полей',
                cls: 'desc-under',
            }, {
                xtype: isCreate ? 'hidden' :'xcheckbox',
                boxLabel: 'Создать в базе данных',
                name: 'сreate_in_base',
                id: config.id + '-сreate_in_base',
                checked: true,
            }, {
                xtype: 'label',
                html: 'Чтобы поле сразу попало в плагины',
                cls: 'desc-under',
            }
        ]
    },

    translitAlias: function (string) {
        if (string === '' || this.config.record.object === 'undefined') {
            return true;
        }

        MODx.Ajax.request({
            url: msAddField.config.connector_url
            , params: {
                action: 'mgr/field/translit'
                , string: string
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        var alias = Ext.getCmp('msaddfield-field-name')
                        if (alias) {
                            alias.setValue(r.object.transliteration);
                        }
                    }, scope: this
                }
            }
        })
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