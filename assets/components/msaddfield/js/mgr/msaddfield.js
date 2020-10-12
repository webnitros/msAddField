var msAddField = function (config) {
    config = config || {};
    msAddField.superclass.constructor.call(this, config);
};
Ext.extend(msAddField, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, buttons: {}
});
Ext.reg('msaddfield', msAddField);

msAddField = new msAddField();