mvtDocs.window.FileLinkAdd = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-file-links';
    }

    Ext.applyIf(config, {
        title: _('mvtdocs-file-links'),
        width: 700,
        autoHeight: true,
        url: mvtDocs.config.connector_url,
		baseParams: {
            //action: 'mgr/file/addlink',
			//file_id: config.file_id,
        },
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],
		buttons: []
    });
			
    mvtDocs.window.FileLinkAdd.superclass.constructor.call(this, config);
	
	this.on('hide', function() {
		var w = this;
		window.setTimeout(function() {
			w.close()
		}, 300);
	});
};
Ext.extend(mvtDocs.window.FileLinkAdd, MODx.Window, {

	getFields: function (config) {
        return [{
			xtype: 'hidden',
			name: 'file_id',
			id: config.id + '-file_id'
		} , {
			xtype: 'mvtdocs-grid-links',
            file_id: config.file_id,
			resource_id: config.resource_id,
		}]
    },

});
Ext.reg('mvtdocs-file-links', mvtDocs.window.FileLinkAdd);