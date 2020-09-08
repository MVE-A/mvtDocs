mvtDocs.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'mvtdocs-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('mvtdocs') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('mvtdocs_items'),
                layout: 'anchor',
                items: [/*{
                    html: _('mvtdocs_intro_msg'),
                    cls: 'panel-desc',
                },*/ {
                    xtype: 'mvtdocs-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    mvtDocs.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.panel.Home, MODx.Panel);
Ext.reg('mvtdocs-panel-home', mvtDocs.panel.Home);
