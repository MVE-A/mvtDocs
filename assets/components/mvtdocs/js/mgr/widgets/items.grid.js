mvtDocs.grid.Items = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'mvtdocs-grid-items';
    }
	if (!config.category) {
        config.category = 0;
    }
	
    Ext.applyIf(config, {
        url: mvtDocs.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/resource/getlist',
			category: config.category
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateItem(grid, e, row);
            }
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
    mvtDocs.grid.Items.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};

Ext.extend(mvtDocs.grid.Items, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = mvtDocs.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },


    updateItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/resource/get',
                resource_id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'mvtdocs-item-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

   

    /*disableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/resource/disable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    enableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/resource/enable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },*/

    getFields: function () {
        return ['id', 'pagetitle', 'category', 'files', 'color', /*'active',*/ 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('mvtdocs_resource_id'),
            dataIndex: 'id',
            sortable: true,
            width: 40,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_resource_pagetitle'),
            dataIndex: 'pagetitle',
            sortable: true,
            width: 150,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_resource_category'),
            dataIndex: 'category',
            sortable: false,
            width: 250,
			renderer: this._renderColor
        }, {
            header: _('mvtdocs_resource_files'),
            dataIndex: 'files',
            sortable: false,
            width: 40,
			renderer: this._renderColor
        }, /* {
            header: _('mvtdocs_resource_active'),
            dataIndex: 'active',
            renderer: mvtDocs.utils.renderBoolean,
            sortable: true,
            width: 40,
        },*/ {
            header: _('mvtdocs_grid_actions'),
            dataIndex: 'actions',
            renderer: mvtDocs.utils.renderActions,
            sortable: false,
            width: 40,
            id: 'actions'
        }];
    },
	
	_renderColor: function (value, cell, row) {
		if (row.data['files'] > 0) {
			return String.format('<span style="color:#{0}"><b>{1}</b></span>', row.data['color'], value);
		}
		else {
			return String.format('<span style="color:#{0}">{1}</span>', row.data['color'], value);
		}
    },


    getTopBar: function () {
        return [{
            xtype: 'mvtdocs-combo-category',
            width: 250,
            listeners:{
				select: {fn: this._filterCategory, scope: this}
			}
        }, {
            text: '<i class="icon icon-refresh"></i> ' + _('mvtdocs_reset_category'),
            handler: this._clearFilter,
            scope: this
        }, '->', {
            xtype: 'mvtdocs-field-search',
            width: 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field);
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('');
                        this._clearSearch();
                    }, scope: this
                },
            }
        }];
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
	
	_filterCategory: function(cb) {
		this.getStore().baseParams.category = cb.value;
		this.getBottomToolbar().changePage(1);
	},
	
	_clearFilter: function(cb) {
		this.getStore().baseParams.category = 0;
		this.getBottomToolbar().changePage(1);
	},
});
Ext.reg('mvtdocs-grid-items', mvtDocs.grid.Items);
