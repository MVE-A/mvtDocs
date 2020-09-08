mvtDocs.grid.Links = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-grid-links';
    }
	
    Ext.applyIf(config, {
        url: mvtDocs.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/file/getlistlinks',
			file_id: config.file_id,
			resource_id: config.resource_id,
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
        remoteSort: true,
        autoHeight: true,
    });
    mvtDocs.grid.Links.superclass.constructor.call(this, config);

    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(mvtDocs.grid.Links, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = mvtDocs.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },


    getFields: function () {
        return ['resource_id', 'pagetitle', 'category', 'type', 'color','actions'];
    },

    getColumns: function () {
        return [{
            header: _('mvtdocs_link_resource_id'),
            dataIndex: 'resource_id',
            sortable: true,
            width: 30,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_link_pagetitle'),
            dataIndex: 'pagetitle',
            sortable: true,
            width: 100,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_link_category'),
            dataIndex: 'category',
            sortable: true,
            width: 100,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_link_type'),
            dataIndex: 'type',
            sortable: true,
            width: 100,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_grid_actions'),
            dataIndex: 'actions',
            renderer: mvtDocs.utils.renderActions,
            sortable: false,
            width: 30,
            id: 'actions'
        }];
    },

    getTopBar: function (config) {
        return [{
            xtype: 'mvtdocs-combo-link-resource',
            width: 350,
			resource_id: config.resource_id
            /*listeners:{
				select: {fn: this._addLink, scope: this}
			}*/
        }, {
            text:  _('mvtdocs_link_add'),
            handler: this.addLink,
            scope: this
        }];
    },
	
	addLink: function (btn, e) {
		var rid = Ext.getCmp('mvtdocs-combo-link-resource').getValue();
		if(rid != '') {
			MODx.Ajax.request({
				url: this.config.url,
				params: {
					action: 'mgr/file/addlink',
					resource_id: rid,
					resource_from: this.config.resource_id,
					file_id: this.config.file_id
				},
				listeners: {
					success: {
						fn: function (r) {
							this.refresh();	
							Ext.getCmp('mvtdocs-grid-items').getStore().reload();							
						}, scope: this
					},
					failure: {
						fn: function (r) {}, scope: this
					}
				}
			});
		}
	},
	
	/*_addLink: function(cb) {
		console.log(cb.value);
	},*/
	

	removeLink: function (btn, e) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('mvtdocs_links_remove')
                : _('mvtdocs_link_remove'),
            text: ids.length > 1
                ? _('mvtdocs_links_remove_confirm')
                : _('mvtdocs_link_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/file/linkremove',
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
	
	_renderColor: function (value, cell, row) {
		return String.format('<span style="color:#{0}">{1}</span>', row.data['color'], value);
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
Ext.reg('mvtdocs-grid-links', mvtDocs.grid.Links);
