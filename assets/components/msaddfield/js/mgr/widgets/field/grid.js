msAddField.grid.Field = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'msaddfield-grid-field'
    }
    var processor = 'mgr/field/'
    Ext.applyIf(config, {
        baseParams: {
            action: processor + 'getlist',
        },
        save_action: processor + 'updatefromgrid',
    })
    msAddField.grid.Field.superclass.constructor.call(this, config)
    config.processor = processor
}
Ext.extend(msAddField.grid.Field, msAddField.grid.Default, {

    getFields: function () {
        return [
            'id', 'name', 'type','product', 'title', 'modification','modification_show_card', 'product_show_card', 'is_product','is_modification',  'actions'
        ]
    },
    getColumns: function () {
        return [
            {header: _('msaddfield_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('msaddfield_field_type'), dataIndex: 'type', sortable: true, width: 40},
            {header: _('msaddfield_name'), dataIndex: 'name', sortable: true, width: 40},
            {header: _('msaddfield_field_title'), dataIndex: 'title', sortable: true, width: 40},
            {header: _('msaddfield_field_product'), dataIndex: 'product', width: 40, renderer: msAddField.utils.renderBoolean},
            {header: _('msaddfield_field_product_is'), dataIndex: 'is_product', width: 40, renderer: msAddField.utils.renderBoolean},
            {header: _('msaddfield_field_modification'), dataIndex: 'modification', width: 40, renderer: msAddField.utils.renderBoolean},
            {header: _('msaddfield_field_modification_is'), dataIndex: 'is_modification', width: 40, renderer: msAddField.utils.renderBoolean},
            {
                header: _('msaddfield_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: msAddField.utils.renderActions
            }
        ]
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.updateField(grid, e, row)
            },
        }
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('msaddfield_field_create'),
            handler: this.createField,
            scope: this
        }, '->', this.getSearchField()]
    },

    createField: function (btn, e) {
        var w = MODx.load({
            xtype: 'msaddfield-field-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        w.reset()
        w.setValues({active: true})
        w.show(e.target)
    },

    updateField: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data
        }
        else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/field/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'msaddfield-field-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                }
                            }
                        })
                        w.reset()
                        w.setValues(r.object)
                        w.show(e.target)
                    }, scope: this
                }
            }
        })
    },

    _updatetable: function () {
    this._actions('updatetable')
},
})
Ext.reg('msaddfield-grid-field', msAddField.grid.Field)