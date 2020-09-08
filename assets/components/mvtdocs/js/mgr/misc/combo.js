mvtDocs.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    mvtDocs.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(mvtDocs.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('mvtdocs-combo-search', mvtDocs.combo.Search);
Ext.reg('mvtdocs-field-search', mvtDocs.combo.Search);


mvtDocs.combo.Category = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'mvtdocs-combo-category',
        fieldLabel: _('mvtdocs_' + config.name || 'combo-category'),
        fields: ['id', 'pagetitle', 'parents'],
        valueField: 'id',
        displayField: 'pagetitle',
        name: config.name || 'resource-category',
        hiddenName: config.name || 'resource-category',
        allowBlank: false,
        url: mvtDocs.config['connector_url'],
        baseParams: {
            action: 'mgr/resource/getcategory',
            combo: true,
            id: config.value
        },
        tpl: new Ext.XTemplate(''
			+'<tpl for="."><div class="x-combo-list-item mvtdocs-category-list-item">'
			+'<span><small>[{id}]</small> <b>{pagetitle}</b></span><tpl if="parents"><div class="parents"><tpl for="parents"><nobr><small>{pagetitle} / </small></nobr></tpl></div></tpl>'
			+'</div></tpl>', {compiled: true}),
        pageSize: 10,
		itemSelector: 'div.mvtdocs-category-list-item',
        emptyText: _('no'),
        editable: true,
    });
    mvtDocs.combo.Category.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.combo.Category, MODx.combo.ComboBox);
Ext.reg('mvtdocs-combo-category', mvtDocs.combo.Category);

mvtDocs.combo.fileType = function (config) {
    config = config || {};
		  
    Ext.applyIf(config, {
		store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['value']
            ,data: this.getTypes()
        })
        ,mode: 'local'
        ,displayField: 'value'
		,hiddenName: 'type'
        ,valueField: 'value'
		,lazyRender:true
		,emptyText: _('mvtdocs_file_type_empty')
		,listeners: {
            'afterrender': function(combo){ 		
				if(config.file_type) {
					this.setValue(config.file_type);
				}
				else {
					this.setValue(combo.store.data.keys[0]);
				}
            }
       }
    });
    mvtDocs.combo.fileType.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.combo.fileType, MODx.combo.ComboBox, {
    getTypes: function () {
        var array = [];
        var types = MODx.config.mvtdocs_document_types.split(',');
        for (var i = 0; i < types.length; i++) {
            if(types[i] != '') {
				array.push([types[i]]);
			}
        }
        return array;
    }
});
Ext.reg('mvtdocs-combo-filetype', mvtDocs.combo.fileType);

mvtDocs.combo.LinkResource = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'mvtdocs-combo-link-resource',
        fieldLabel: _('mvtdocs_' + config.name || 'combo-link-resource'),
        fields: ['id', 'article', 'pagetitle', 'parents'],
        valueField: 'id',
        displayField: 'pagetitle',
        name: config.name || 'link-resource',
        hiddenName: config.name || 'link-resource',
        allowBlank: false,
		minChars: 2,
        url: mvtDocs.config['connector_url'],
        baseParams: {
            action: 'mgr/resource/getresources',
			resource_id: config.resource_id,
            combo: true
        },
        tpl: new Ext.XTemplate(''
			+'<tpl for="."><div class="x-combo-list-item mvtdocs-resources-list-item">'
			+'<span><small>[{id}]</small> <tpl if="article">арт. {article}</tpl> <b>{pagetitle}</b> </span><tpl if="parents"><div class="parents"><tpl for="parents"><nobr><small>{pagetitle} / </small></nobr></tpl></div></tpl>'
			+'</div></tpl>', {compiled: true}),
        pageSize: 10,
		itemSelector: 'div.mvtdocs-resources-list-item',
        emptyText: _('no'),
        editable: true,
    });
    mvtDocs.combo.LinkResource.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.combo.LinkResource, MODx.combo.ComboBox);
Ext.reg('mvtdocs-combo-link-resource', mvtDocs.combo.LinkResource);

mvtDocs.combo.Itemtype = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['id','value']
            ,data: [
                ['file','Файл'],
                ['link','Ссылка']
            ],
        })
        ,mode: 'local'
		,hiddenName: 'itemtype'
        ,displayField: 'value'
        ,valueField: 'id'
		,listeners: {
            'afterrender': function(combo){           
				this.setValue(combo.store.data.keys[0]);
            },
			select: function(combo){
				if(combo.value == 'file') {
					Ext.getCmp(config.extlinkField).hide();
					Ext.getCmp(config.fileField).show();
				}
				else {
					Ext.getCmp(config.extlinkField).show();
					Ext.getCmp(config.fileField).hide();
				}
			}
       }
    });
    mvtDocs.combo.Itemtype.superclass.constructor.call(this, config);
};
Ext.extend(mvtDocs.combo.Itemtype, MODx.combo.ComboBox);
Ext.reg('mvtdocs-combo-itemtype', mvtDocs.combo.Itemtype);