mvtDocs.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'mvtdocs-panel-home',
            renderTo: 'mvtdocs-panel-home-div'
        }]
    });
    mvtDocs.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.page.Home, MODx.Component);
Ext.reg('mvtdocs-page-home', mvtDocs.page.Home);