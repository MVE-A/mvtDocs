mvtDocs.window.FileEdit = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-file-window-edit';
    }
	
    Ext.applyIf(config, {
        title: _('mvtdocs_file_edit'),
		fileUpload: true,
        width: 600,
        autoHeight: true,
        url: mvtDocs.config.connector_url,
		baseParams: {
            action: 'mgr/file/edit',
			resource_id: config.resource_id,
        },
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
			
    mvtDocs.window.FileEdit.superclass.constructor.call(this, config);
	
	this.on('hide', function() {
		var w = this;
		window.setTimeout(function() {
			w.close()
		}, 300);
	});
};
Ext.extend(mvtDocs.window.FileEdit, MODx.Window, {

	getFields: function (config) {
      
		var fields = [
			{
				xtype: 'hidden',
				name: 'id',
				id: config.id + '-id'
			} , {
				xtype: 'textfield',
				fieldLabel: _('mvtdocs_file_name'),
				name: 'name',
				id: config.id + '-name',
				anchor: '90%',
				allowBlank: false,
			}, {
				xtype: 'textfield',
				fieldLabel: _('mvtdocs_file_description'),
				name: 'description',
				id: config.id + '-description',
				anchor: '90%',
				allowBlank: true,
			} , {
				fieldLabel: _('mvtdocs_file_type'),
				xtype: 'mvtdocs-combo-filetype',
				file_type: config.type,
				description: _('mvtdocs_file_type_description'),
				anchor: '90%',
				allowBlank: false,
			}
		];
		
		return fields;
		
    }

});
Ext.reg('mvtdocs-file-window-edit', mvtDocs.window.FileEdit);



mvtDocs.window.FileAdd = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-file-window-add';
    }
	
    Ext.applyIf(config, {
        title: _('mvtdocs-file-add'),
		fileUpload: true,
		enctype : 'multipart/form-data',
        width: 600,
        autoHeight: true,
        url: mvtDocs.config.connector_url,
		baseParams: {
            action: 'mgr/file/add',
			resource_id: config.resource_id,
        },
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
			
    mvtDocs.window.FileAdd.superclass.constructor.call(this, config);
	
	this.on('hide', function() {
		var w = this;
		window.setTimeout(function() {
			w.close()
		}, 300);
	});
};
Ext.extend(mvtDocs.window.FileAdd, MODx.Window, {

	getFields: function (config) {
        
		var fields = [
			/*{
				xtype: 'hidden',
				name: 'resource_id',
				id: config.id + '-resource_id'
			} ,*/ {
				xtype: 'textfield',
				fieldLabel: _('mvtdocs_file_name'),
				name: 'name',
				id: config.id + '-name',
				anchor: '90%',
				allowBlank: false,
			} , {
				xtype: 'textfield',
				fieldLabel: _('mvtdocs_file_description'),
				name: 'description',
				id: config.id + '-description',
				anchor: '90%',
				allowBlank: true,
			} , {
				fieldLabel: _('mvtdocs_file_itemtype'),
				xtype: 'mvtdocs-combo-itemtype',
				anchor: '90%',
				allowBlank: false,
				extlinkField: config.id + '-extlink',
				fileField: config.id + '-filefield',
			} , {
				fieldLabel: _('mvtdocs_file_extlink'),
				name: 'extlink',
				xtype: 'textfield',
				displayed: false,
				anchor: '90%',
				allowBlank: true,
				id: config.id + '-extlink',
				trackLabels: true,
				listeners: {
                    'render': function() {
                        this.hide()
                    }
                },
			} , {
				fieldLabel: _('mvtdocs_file_type'),
				xtype: 'mvtdocs-combo-filetype',
				description: _('mvtdocs_file_type_description'),
				anchor: '90%',
				allowBlank: false,
			}
		];
		
		fields.push({
			layout:'column',
			border: false,
			anchor: '100%',
			id: config.id + '-filefield',
			items: [{
					columnWidth: .4
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [{
						xtype: 'fileuploadfield',
						name: 'loader',
						allowBlank:true,
						anchor: '100%',
						id: 'mvtdocs_file-loader',
						hidden: true,
						listeners: {
							change: function() {
						 
							}
						}
					}, {
						xtype: 'button',
						fieldLabel: _('mvtdocs_file_loader'),
						text: 'Загрузить файл',
						allowBlank: false,
						anchor: '100%',
						listeners: {
							click: {fn: function(){
								document.getElementById('mvtdocs_file-loader-file').click();
								document.getElementById('mvtdocs_file-loader-file').addEventListener('change', function() {
									document.getElementById('mvtdocs_file-holder').innerHTML = document.getElementById('mvtdocs_file-loader-file').files[0].name;
								}, false);
							}, scope: this}
						}
					}]
				} , {
					columnWidth: .6,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border: false,
					items: [{
						id: 'mvtdocs_file-holder',
						anchor: '100%',
						style: 'margin: 36px 15px 0px 0px;overflow: hidden; display: inline-block; padding: 8px; vertical-align: top; width: 292px; height: 18px; background: #b5f3b7; border-radius: 3px; color: #0d60b6;',
					}]
				}]
			} , {
				xtype: 'textfield',
				name: 'file1',
				id: 'mvtdocs_file-file1',
				anchor: '99%',
				allowBlank: true,
				labelStyle: 'display: none;',
				style: {height: '1px', 'min-height': '1px', 'font-size': '1px', color: '#fff', padding: 0, border: 'none'}
			} 
		);	
		
		return fields;
		
    }

});
Ext.reg('mvtdocs-file-window-add', mvtDocs.window.FileAdd);