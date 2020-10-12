msAddField.window.CreateField = function (config) {
    config = config || {}
    config.url = msAddField.config.connector_url

    Ext.applyIf(config, {
        title: _('mscml_field_create'),
        width: 600,
        cls: 'mscml_windows',
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
                xtype: 'textfield',
                fieldLabel: _('mscml_field_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '99%',
                allowBlank: false,
                readOnly: isCreate
            },{
                xtype: 'textfield',
                fieldLabel: _('mscml_field_title'),
                description: _('mscml_field_title_desc'),
                name: 'title',
                id: config.id + '-title',
                anchor: '99%',
                allowBlank: false,
            },{
                xtype: 'textfield',
                fieldLabel: _('mscml_field_help'),
                name: 'help',
                id: config.id + '-help',
                anchor: '99%',
                allowBlank: true,
            }, {
                xtype: 'mscml-combo-typefield',
                fieldLabel: _('mscml_field_type'),
                name: 'type',
                id: config.id + '-type',
                anchor: '40%',
                allowBlank: false,
                readOnly: isCreate
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('mscml_field_product'),
                name: 'product',
                id: config.id + '-product',
                checked: true,
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('mscml_product_show_card'),
                name: 'product_show_card',
                id: config.id + '-product_show_card',
                checked: true,
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('mscml_field_modification'),
                name: 'modification',
                id: config.id + '-modification',
                checked: true,
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('mscml_indexes'),
                name: 'indexes',
                id: config.id + '-indexes',
                checked: true,
                readOnly: isCreate
            }/*, {
                xtype: 'xcheckbox',
                boxLabel: _('mscml_modification_show_card'),
                name: 'modification_show_card',
                id: config.id + '-modification_show_card',
                checked: true,
            }*/
        ]

    }
})
Ext.reg('mscml-field-window-create', msAddField.window.CreateField)

msAddField.window.UpdateField = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('mscml_field_update'),
        baseParams: {
            action: 'mgr/field/update',
        },
    })
    msAddField.window.UpdateField.superclass.constructor.call(this, config)

}
Ext.extend(msAddField.window.UpdateField, msAddField.window.CreateField)
Ext.reg('mscml-field-window-update', msAddField.window.UpdateField)