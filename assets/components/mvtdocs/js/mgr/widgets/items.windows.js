mvtDocs.window.UpdateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('mvtdocs_item_update'),
        width: 750,
        autoHeight: true,
        url: mvtDocs.config.connector_url,
        action: 'mgr/resource/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],
		buttons: [],
    });
    mvtDocs.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
			xtype: 'mvtdocs-grid-files',
            record: config.record,
		}]
    },

	/*
	getFields: function (config) {
        return [{
            xtype: 'modx-tabs',
			deferredRender: false,
			border: true,
			bodyStyle: 'padding:5px;',
			style: 'margin-top: 10px',
            items: [{
                title: '<i class="icon icon-file"></i> Файлы',
                layout: 'form',
                items: [{
					xtype: 'mvtdocs-grid-files',
					record: config.record,
				}]
            } , {
                title: '<i class="icon icon-link"></i></i> Связи', 
                items: []
            }]
        }];
    },
	*/
	
    loadDropZones: function () {
    }

});
Ext.reg('mvtdocs-item-window-update', mvtDocs.window.UpdateItem);