var mvtDocs = function (config) {
    config = config || {};
    mvtDocs.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('mvtdocs', mvtDocs);

mvtDocs = new mvtDocs();