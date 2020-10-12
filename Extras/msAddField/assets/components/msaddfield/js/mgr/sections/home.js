msAddField.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'msaddfield-panel-home',
            renderTo: 'msaddfield-panel-home-div'
        }]
    });
    msAddField.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(msAddField.page.Home, MODx.Component);
Ext.reg('msaddfield-page-home', msAddField.page.Home);