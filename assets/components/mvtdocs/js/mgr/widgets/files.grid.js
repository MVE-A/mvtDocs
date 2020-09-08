mvtDocs.grid.Files = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-grid-files';
    }
	
    Ext.applyIf(config, {
        url: mvtDocs.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/file/getlist',
			resource_id: config.record.object.resource_id,
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'mvtdocs-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
		pageSize:5,
        remoteSort: true,
        autoHeight: true,
    });
    mvtDocs.grid.Files.superclass.constructor.call(this, config);

    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(mvtDocs.grid.Files, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = mvtDocs.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },


    getFields: function () {
        return ['id','name', 'description', 'path', 'type', 'added', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('mvtdocs_files_id'),
            dataIndex: 'id',
            sortable: true,
			hidden:true,
            width: 70
        }, {
            header: _('mvtdocs_files_name'),
            dataIndex: 'name',
            sortable: true,
            width: 100,
        }, {
            header: _('mvtdocs_files_type'),
            dataIndex: 'type',
            sortable: true,
            width: 100,
        }, {
            header: _('mvtdocs_files_path'),
            dataIndex: 'path',
            sortable: true,
            width: 200,
        }, {
            header: _('mvtdocs_files_added'),
            dataIndex: 'added',
            sortable: false,
            width: 80,
        }, {
            header: _('mvtdocs_grid_actions'),
            dataIndex: 'actions',
            renderer: mvtDocs.utils.renderActions,
            sortable: false,
            width: 80,
            id: 'actions'
        }];
    },

    getTopBar: function (config) {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('mvtdocs_file_add'),
            handler: this.addFile,
            scope: this
        }];
    },
	
	
	setLinks: function (btn, e) {
		fid = this._getSelectedIds();
		var w = MODx.load({
            xtype: 'mvtdocs-file-links',
            id: Ext.id(),
			file_id: fid[0],
			resource_id: this.config.record.object.resource_id,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
		//w.setValues({file_id:fid[0], this.config.record.object.resource_id});
        w.show(e.target);
	},
	
	
	addFile: function (btn, e) {
        var w = MODx.load({
            xtype: 'mvtdocs-file-window-add',
            id: Ext.id(),
			resource_id: this.config.record.object.resource_id,
			types: this.config.record.object.types,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
						Ext.getCmp('mvtdocs-grid-items').getStore().reload();
                    }, scope: this
                }
            }
        });
        w.reset();
        //w.setValues({active: true,company_id:this.config.record.object.id});
        w.show(e.target);
    },
	
	
	editFile: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;
		
		var w = MODx.load({
            xtype: 'mvtdocs-file-window-edit',
			type: this.menu.record.type,
			resource_id: this.config.record.object.resource_id,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
						Ext.getCmp('mvtdocs-grid-items').getStore().reload();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({id: id, name: this.menu.record.name, description: this.menu.record.description});
        w.show(e.target);
    },
	
	
	removeFile: function (btn, e) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('mvtdocs_files_remove')
                : _('mvtdocs_file_remove'),
            text: ids.length > 1
                ? _('mvtdocs_files_remove_confirm')
                : _('mvtdocs_file_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/file/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
						Ext.getCmp('mvtdocs-grid-items').getStore().reload();
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('mvtdocs-grid-files', mvtDocs.grid.Files);
