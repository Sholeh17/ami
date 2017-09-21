<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>

<script>
Ext.onReady(function(){
xK.trxsys_reg = {
  id_tab_main : "<?=$_REQUEST['id_tab'];?>", 	  
  formestimasiCatring:null,
  gridHirs : null,
  grid_report: null,
  form_report:null,
  storeDataComDev : null,
  winTrx:null,
  panelView:null,
  store_master_agent : null,
  init : function() {
	  xK.setTitlePage('Registration');
      this.doLayoutPanel();      
  },
  getStoredData : function(name_model,url,id_stored,cnt_page) {
      var storelib = Ext.create('Ext.data.JsonStore', {
            autoDestroy: true,
            model: name_model,
            listeners: {
                load: function() {
                }
            },
            proxy: {
                type: 'ajax',
                url: url,
                reader: {
                    type: 'json',
                    root: 'data',
                    idProperty: id_stored,
                    totalProperty: 'total'
                }
            },
            remoteSort: true,
            pageSize: cnt_page
        });

    return storelib;
  },
  getModelHIRS : function() {
     // register model
    Ext.define('mtrxhirs', {
        extend: 'Ext.data.Model',
        idProperty: 'trxcapu',
        fields: [
           {name: 'id_seq'},
           {name: 'user_id'},
           {name: 'group_user'},
		   {name: 'level'},
		   {name: 'user_section'},
		   {name: 'amount_approve'},
		   {name: 'id_dept'},		   
		   {name: 'nama_user'},		   
		   {name: 'hp'},
		   {name: 'email'},		   
		   {name: 'active'},
		   {name: 'owner'}
		   
        ]
    });

    var stored_data = this.getStoredData('mtrxhirs','sys_reg/get_data','mtrxdepo','20');
    return stored_data;
  },  
  getGridHirs : function() {
        var encode = false;        
        var local = false;
        var filterfeature = {
            ftype: 'filters',
            encode: encode,
            local: local,
            filters: [{
                type: 'boolean',
                dataIndex: 'visible'
            }]
        };
        var storeMdl = this.getModelHIRS();
        storeMdl.load();
        var searchField = Ext.create('Ext.ux.form.SearchField',{
									store: storeMdl,width:440
						 });
						 
						 
		Ext.ux.ajax.SimManager.init({
        delay: 300,
        defaultSimlet: null
		}).register({
			'Urlstatus': {
				data: [
					['Posted', 'Posted'],
					['Draft', 'Draft']
				],
				stype: 'json'
			}
		});

		var optionsStoreStatus = Ext.create('Ext.data.Store', {
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: 'Urlstatus',
				reader: 'array'
			}
		});				 
		
		
		
		Ext.ux.ajax.SimManager.init({
        delay: 300,
        defaultSimlet: null
		}).register({
			'UrlBreak': {
				data: [
					['USER', 'USER'],
					['GENERAL', 'GENERAL'],
                    ['ANALISA LAB', 'ANALISA LAB'],
					['SECTION HEAD', 'SECTION MANAGER'],	
                    ['DEPARTMENT HEAD', 'DEPARTMENT HEAD'],
					['DIVISION HEAD', 'DIVISION HEAD'],
					['ADMIN', 'ADMIN']
					
				],
				stype: 'json'
			}
		});
		
		var optionsStoreGroup = Ext.create('Ext.data.Store', {
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: 'UrlBreak',
				reader: 'array'
			}
		});				 
		
		
		Ext.ux.ajax.SimManager.init({
        delay: 300,
        defaultSimlet: null
		}).register({
			'ListGU': {
				data: [
					['DOMESTIK', 'DOMESTIK'],					
					['EXPORT', 'EXPORT'],					
					['OEM', 'OEM'],
					['ALL', 'ALL']
				],
				stype: 'json'
			}
		});
		
		var optionsStoreGroupList = Ext.create('Ext.data.Store', {
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: 'ListGU',
				reader: 'array'
			}
		});				 
		
		 var rowEditingCatring = Ext.create('Ext.grid.plugin.RowEditing', {
				listeners: {
					cancelEdit: function(rowEditing, context) {						
							xK.trxsys_reg.gridHirs.store.remove(context.record);						
					},
					edit : function(e) {						
					}
				}
			});
		
						 
        this.gridHirs = Ext.create('Ext.grid.Panel', {
            store: storeMdl,
            region:'center',			
            loadMask: true,
			bodyPadding: 1,            
            tbar:[{text:'Tambah',handler:this.onAddForm},{text:'Edit',handler:this.onEditData},'-',searchField],            			
			features: [filterfeature],
            title:'User Registration',
            split: true,            
            columns: [{
						text: 'UserID',
						sortable: true,                    
						dataIndex: 'user_id',
						filterable: true,
						filter: {
							type: 'string'							
						},
						width: 150
					},{
						text: 'Nik',
						sortable: true,                    
						dataIndex: 'nama_user',
						editor: {
							allowBlank: true
						},
						filterable: true,
						filter: {
						type: 'string'
							
						},
						width: 100
					}/*,{
						text: 'Group',
						sortable: true,                    
						dataIndex: 'group_user',
						filterable: true,
						filter: {
							type: 'list',
							store: optionsStoreGroupList
						},
						width: 100
					}*/,{
						text: 'Level',
						sortable: true,                    
						dataIndex: 'level',
						filterable: true,
						filter: {
							type: 'list',
							store: optionsStoreGroup
						},
						width: 100
					},{
						text: 'Section',
						sortable: true,                    
						dataIndex: 'user_section',
						filter: {
							type: 'string'
						},
						width: 100
					},
					{
						text: 'Group',
						sortable: true,                    
						dataIndex: 'owner',
						filter: {
							type: 'string'
						},
						width: 100
					},{
						text: 'Active',
						sortable: true,                    
						dataIndex: 'active',
						filterable: false,
						renderer:function(v) {
							if (v=="1")
								return "Active";	
							return "Non Active";
							
						},
						width: 100
					}
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){					
					//xK.trxsys_reg.onEditData();					
                },
               selectionchange: function(sm, row, rec) {
                    
               }
            },
            bbar: Ext.create('Ext.PagingToolbar', {
                pageSize: 10,
                store: storeMdl,
                displayInfo: true,
                plugins: Ext.create('Ext.ux.ProgressBarPager', {width:200})
            })
        });
		
		this.gridHirs.on('edit', function(f,e) {						
        });

  },
  getModelKaryawan : function() {
     // register model
    Ext.define('karyawan_nik', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'id_seq_karyawan'},
           {name: 'nik_karyawan'},		   
           {name: 'name_karyawan'}
        ]
    });

    var stored_data = this.getStoredData('karyawan_nik','karyawan/get_data_karyawan','karyawan_nik','20');
	stored_data.load();
    return stored_data;
  },
  genaretformKaryawan : function() { 
		this.formreport_test();
		var level_store = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"USER", "name":"USER"},
                {"id":"ANALISA LAB", "name":"ANALISA LAB"},
				{"id":"GENERAL", "name":"GENERAL"},
				{"id":"SECTION HEAD", "name":"SECTION HEAD"},
                {"id":"DEPARTMENT HEAD", "name":"DEPARTMENT HEAD"},
				{"id":"DIVISION HEAD", "name":"DIVISION HEAD"},
				{"id":"ADMIN", "name":"ADMIN"}								
				
			]
		});
		
		var group_store = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"DOMESTIK", "name":"DOMESTIK"},
				{"id":"EXPORT", "name":"EXPORT"},						
				{"id":"OEM", "name":"OEM"},	
				{"id":"ALL", "name":"ALL"}
				
			]
		});
		
		var status_store = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"Draft", "name":"Draft"},
				{"id":"Posted", "name":"Posted"}		
			]
		});
		
		
		var requester_stored = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"CD 1", "name":"CD 1"},
				{"id":"CD 2", "name":"CD 2"},
				{"id":"CD 3", "name":"CD 3"},	
				{"id":"PE", "name":"PE"},
				{"id":"TECHNICAL SERVICE", "name":"TECHNICAL SERVICE"},
				{"id":"QC/QA", "name":"QC/QA"},
				{"id":"R&D CONSTRUCTION", "name":"R&D CONSTRUCTION"},
				{"id":"PRODUCT DEVELOPMET", "name":"PRODUCT DEVELOPMET"},
				{"id":"PRODUCT DEVELOPMET", "name":"PRODUCT DEVELOPMET"},
				{"id":"PID", "name":"PID"},
				{"id":"SECTION LAB", "name":"SECTION LAB"},
				{"id":"R&D CENTER", "name":"R&D CENTER"},
				{"id":"OTHER", "name":"OTHER"}
			]
		});
		
		var owner_stored = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"RD", "name":"RD"},
				{"id":"QUALITY", "name":"QUALITY"}
			]
		});
		
		var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
        this.formestimasiCatring = Ext.create('Ext.form.Panel', {                                
                autoScroll:true,
				id:'form_user',				
                waitMsgTarget: true,
                split: true,
                bodyPadding: 5,
				buttonAlign:'left',
					listeners: {
					beforesubmit: function(form, values, options) {
					  options.method = "post";
					}
				},
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 550                    
                },
                items: [
                {
                    xtype: 'hidden',
                    name:'id_seq'
                },{
					xtype : 'combo',
					name:'nama_user',
                    fieldLabel: 'Nama/Nik',
                    width: 450,
					displayField : 'name_karyawan',			
					tpl: '<tpl for="."><div class="x-boundlist-item">{nik_karyawan} - {name_karyawan}</div></tpl>',	
					valueField	 : 'nik_karyawan',
					minChars		: 1,
					queryDelay		: 1,
					pageSize		: 20,
					editable	 : true,
					allowBlank: false,					
					store		 : this.getModelKaryawan(),
					triggerAction: 'all',
					listeners : {
									select : function(combo,value,r){
											
									}
								}
                },{
                    xtype: 'textfield',					
                    fieldLabel: 'UserID',
                    width: 450,
                    name:'user_id',
					afterLabelTextTpl: required,
                    allowBlank: false
                },{
                    xtype: 'textfield',					
                    fieldLabel: 'Password',
					//inputType:'password',
                    width: 450,
                    name:'password',
					afterLabelTextTpl: required,
                    allowBlank: true
                },				
				{
					xtype: 'combo',
					typeAhead: true,
					displayField : 'name',
					fieldLabel: 'Group',
					name:'group_user',
					width: 450,	
					hidden:true,
					valueField : 'id',	
					//editable	: false,
					
					triggerAction: 'all',
					store:group_store,
					afterLabelTextTpl: required,
                    allowBlank: true,
					listeners: {
						select: function(o) {
							
						}
					}
				},				
				{
					xtype: 'combo',
					typeAhead: true,
					displayField : 'name',
					fieldLabel: 'Level',
					name:'level',
					width: 450,	
					valueField : 'id',	
					listeners: {
						select: function(o) {
							/*switch(o.getValue()) {
								case "BOD":
									xK.trxsys_reg.formestimasiCatring.getForm().findField('amount_approve').setValue(">15%");
								break;
								case "DIVISION HEAD":
									xK.trxsys_reg.formestimasiCatring.getForm().findField('amount_approve').setValue("11-15%");
								break;
								case "SALES MANAGER":
									xK.trxsys_reg.formestimasiCatring.getForm().findField('amount_approve').setValue("5-10%");
								break;
								default :
									xK.trxsys_reg.formestimasiCatring.getForm().findField('amount_approve').setValue("<5%");
								break;
							}*/
							
						}
					},
					triggerAction: 'all',
					store:level_store,
					afterLabelTextTpl: required,
                    allowBlank: false
				},
				{
						xtype: 'combo',
						typeAhead: true,
						displayField : 'name',
						fieldLabel: 'Section',
						name:'user_section',
						id:'user_section',
						itemId: 'user_section',					
						width: 450,	
						//value:'SEMUA',
						valueField : 'id',	
						editable	: false,
						triggerAction: 'all',
						store:requester_stored,
						allowBlank: true
				},
				{
						xtype: 'combo',
						typeAhead: true,
						displayField : 'name',
						fieldLabel: 'Group',
						name:'owner',
						id:'owner',
						itemId: 'owner',					
						width: 450,	
						//value:'SEMUA',
						valueField : 'id',	
						editable	: false,
						triggerAction: 'all',
						store:owner_stored,
						allowBlank: false,
						readOnly: true,
						value: "<?php echo $this->session->userdata('owner');?>",
						listeners: {
							change: function(field, selectedValue) {
								
								xK.trxsys_reg.grid_report.store.load(
									{
										params:	{
													userid:xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').getValue(), 
													owner:selectedValue
												}
									});
							}
					
						}
				},
				{
                    xtype: 'textfield',					
                    fieldLabel: 'Allow-Approve (PO % Profit)',
					hidden:true,
                    width: 90,
                    name:'amount_approve',
					afterLabelTextTpl: required,
                    allowBlank: true
                },
				{
                    xtype: 'checkboxfield',					
                    fieldLabel: 'Active?',
					inputValue: '1',
                    width: 100,
					checked   : true,
                    name:'active'
                },
                                    this.form_report

            ],
            buttons: [{
                    text: 'Save',
					id:'SaveSysID',
					//iconCls: 'btn-save',					
                    handler:this.onSaveData
                },
				{
                    text: 'close',
					iconCls:'btn-refresh',
                    handler: function() {
                        xK.trxsys_reg.winTrx.hide();
                       
                    }
                }]
            });
  }, 
  
  getStoredReportMachine : function() {
         // register model
        Ext.define('m_rm_detail', {
            extend: 'Ext.data.Model',
            idProperty: 'm_rm_detail',
            fields: [
               {name: 'idreport'},
               {name: 'name_report'},
               {name: 'is_check',type: 'bool'}
            ]
        });

        var stored_data = this.getStoredData('m_rm_detail','sys_reg/get_report_machine_test','m_rm_detail','20');
        return stored_data;
  },
  grid_machine_report : function() {
        var storeMdl = this.getStoredReportMachine();
        this.grid_report = Ext.widget('gridpanel', {
            store: storeMdl,
            loadMask: true,
            border:false,
            height:150,
            split: true,            
            columns: [  {
                            text: 'Machine Test',                    
                            dataIndex: 'name_report',
                            width: 330
                        },{
                                text: 'Is Check?',
                                xtype: 'checkcolumn',                   
                                dataIndex: 'is_check'
                        }
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                   
                },
               selectionchange: function(sm, row, rec) {
                    
               }
            }
        });
  },
  formreport_test : function() {
        this.grid_machine_report();
        this.form_report = {
        xtype: 'container',
        layout: 'hbox',
        border:false,
        margin: '10 10 10 10',
        items: [
            {
                xtype: 'fieldset',
                width: 450,
                title:'Machine Report Test',
                border:true,
                defaults: {
                        labelWidth: 100,
                        bodyStyle:'padding:5px'
                },
                items: [this.grid_report]
            }
            
        ]};
  },
  windowFormCreate : function() {
		this.genaretformKaryawan();
                
		this.winTrx =   Ext.create('Ext.window.Window', {								
                header: {
                    titlePosition: 2,
                    titleAlign: 'center'
                },                
                closeAction: 'hide',
               
                layout: 'fit',
                constrainHeader:true,
				modal:true,
                layout: {                    
                    padding: 4
                }
				,items:[this.formestimasiCatring]
		});
		Ext.getCmp(this.id_tab_main).add(this.winTrx);		
  },  
  
  onAddForm:function() {
		xK.trxsys_reg.winTrx.show();
		xK.trxsys_reg.winTrx.setTitle("Add User");
                var form = xK.trxsys_reg.winTrx.down('form');
		form.getForm().reset();	
		Ext.getCmp('SaveSysID').setDisabled(false);
		xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').setDisabled(false);
        //xK.trxsys_reg.grid_report.store.load({params:{userid:xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').getValue()}});
		//xK.trxsys_reg.grid_report.store.load({params:{userid:xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').getValue()}, {owner:xK.trxsys_reg.winTrx.down('form').getForm().findField('owner').getValue()}});
		//xK.trxsys_reg.grid_report.store.load({params:{owner:xK.trxsys_reg.winTrx.down('form').getForm().findField('owner').getValue()}});
		xK.trxsys_reg.grid_report.store.load(
			{
				params:	{
							userid:xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').getValue(), 
							owner:xK.trxsys_reg.winTrx.down('form').getForm().findField('owner').getValue()
						}
			});
  },  
  onEditData:function() {	
		var grid = xK.trxsys_reg.gridHirs;		
		if(!grid.getSelectionModel().hasSelection()) {
			Ext.Msg.alert('','Please Select row data to update');
			return;
		}
		xK.trxsys_reg.winTrx.show();
		xK.trxsys_reg.winTrx.setTitle('Update Data');	
		xK.trxsys_reg.winTrx.down('form').getForm().reset();
		selGrid = grid.getSelectionModel().getSelection()[0];		
		xK.trxsys_reg.winTrx.down('form').getForm().loadRecord(selGrid);
		xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').setDisabled(true);
		xK.trxsys_reg.winTrx.down('form').getForm().findField('password').setValue("");
        xK.trxsys_reg.grid_report.store.load(
			{
				params:	{
							userid:xK.trxsys_reg.winTrx.down('form').getForm().findField('user_id').getValue(), 
							owner:xK.trxsys_reg.winTrx.down('form').getForm().findField('owner').getValue()
						}
			});
		//xK.trxsys_reg.grid_report.store.load({params:});
  },
  onSaveData : function() {
		var form = xK.trxsys_reg.winTrx.down('form');
		var grid = xK.trxsys_reg.gridHirs,
		iddata = form.getForm().findField('id_seq').getValue();		
		
		var grid_report = xK.trxsys_reg.grid_report.store;
		var params = "x=0";
		var j = 0;
		grid_report.each(function(rec) {
				if (rec.get('is_check')) {
					params += "&data["+j+"][idreport]="+rec.get('idreport');
					j++;
				}
		});
        //console.error(params);      
		form.getForm().submit({
				method:'POST',				
				url:'sys_reg/save',			
				//clientValidation: true,    
				waitTitle:'Harap Tunggu',
				waitMsg:'Saving data...',
                params:params,
				success:function(opt,response)
				{
					var is_success = response.result.success;			
						if (is_success === true) {																						
							Ext.Msg.alert('Info!', response.result.msg, function() { 
								xK.trxsys_reg.winTrx.hide();
								grid.store.load();
							});	 
						} else {
							Ext.Msg.alert('Failed!', response.result.msg);	
						}
				},
				failure:function(form, action)
				{									
					if(action.failureType == 'server')
					{
						var jsonData = Ext.JSON.decode(action.response.responseText);
						Ext.Msg.alert('Failed!', jsonData.msg);										
					}
					else
					{
						Ext.Msg.alert('Warning!', 'Server is unreachable : FORM NULL');
					}										
				}
		});
	
  
		
  },  
  doLayoutPanel : function(){     	 
     this.getGridHirs();	 
     var dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height: xK.panelCenterHeight
				,layout: 'border'
				,margin:'2 2 2 2'
				,items: [							
							this.gridHirs						
						]
            }); 
	 this.windowFormCreate();
  }
}

xK.trxsys_reg.init();
});
  
</script>

<div style="display:none;">
<div id="panels-div<?=$_REQUEST['id_tab'];?>">
            <h2>View List</h2>                 
</div>