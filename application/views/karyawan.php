<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.spj = {
  formKaryawan : null,
  gridKaryawan : null,
  store_master_agent : null,
  init : function() {
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
  getModelKaryawan : function() {
     // register model
    Ext.define('karyawan_model', {
        extend: 'Ext.data.Model',
        idProperty: 'company',
        fields: [
           {name: 'id_seq_karyawan'},
           {name: 'nik_karyawan'},
           {name: 'name_karyawan'},
		   {name: 'id_atasan_1'},	   
		   {name: 'name_atasan_1'},
		   {name: 'id_atasan_2'},	   
		   {name: 'name_atasan_2'},
		   {name: 'id_atasan_3'},	   
		   {name: 'name_atasan_3'},
           {name: 'date_insert_karyawan'}
        ]
    });

    var stored_data = this.getStoredData('karyawan_model','karyawan/get_data_karyawan','karyawan_model','20');
    return stored_data;
  },  
  getModelAtasan : function() {
     // register model
    Ext.define('atasan_model', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'id_seq_karyawan'},
           {name: 'nik_karyawan'},		   
           {name: 'name_karyawan'}
        ]
    });

    var stored_data = this.getStoredData('atasan_model','karyawan/get_data_karyawan','atasan_model','20');
    return stored_data;
  },  
  getGridListKaryawan : function() {  
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
        var storeMdl = this.getModelKaryawan();
        storeMdl.load();
        var searchField = Ext.create('Ext.ux.form.SearchField',{
    	store: storeMdl,
		width:440});
        this.gridKaryawan = Ext.widget('gridpanel', {
            store: storeMdl,
            region:'west',			
			width:Ext.getBody().getViewSize().width-470,
            loadMask: true,
            id:'grid_karyawan',
            tbar:[searchField],
            features: [filterfeature],
            title:'LIST KARYAWAN',
            split: true,            
            columns: [{
						text: 'NIK',
						sortable: true,                    
						dataIndex: 'nik_karyawan',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Nama Karyawan',
						sortable: true,                    
						dataIndex: 'name_karyawan',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					}/*,{
						text: 'Atasan 1',
						sortable: true,                    
						dataIndex: 'name_atasan_1',
						filterable: true,
						filter: {
						type: 'string'
							// specify disabled to disable the filter menu
							, disabled: false
						},
						width: 200
					},{
						text: 'Atasan 2',
						sortable: true,                    
						dataIndex: 'name_atasan_2',
						filterable: true,
						filter: {
						type: 'string'
							// specify disabled to disable the filter menu
							, disabled: false
						},
						width: 200
					},{
						text: 'Atasan 3',
						sortable: true,                    
						dataIndex: 'name_atasan_3',
						filterable: true,
						filter: {
						type: 'string'
							// specify disabled to disable the filter menu
							, disabled: false
						},
						width: 200
					} */
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                   var r = record;
				   xK.spj.formKaryawan.getForm().loadRecord(record);					   
				   xK.spj.formKaryawan.getForm().findField('id_atasan_1_h').setValue(record.data.id_atasan_1);	
				   xK.spj.formKaryawan.getForm().findField('id_atasan_1').setValue(record.data.id_atasan_1);				   
				   xK.spj.formKaryawan.getForm().findField('id_atasan_1').setRawValue(record.data.name_atasan_1);
				   xK.spj.formKaryawan.getForm().findField('id_atasan_2_h').setValue(record.data.id_atasan_2);
				   xK.spj.formKaryawan.getForm().findField('id_atasan_2').setValue(record.data.id_atasan_2);				   
				   xK.spj.formKaryawan.getForm().findField('id_atasan_2').setRawValue(record.data.name_atasan_2);
				   xK.spj.formKaryawan.getForm().findField('id_atasan_3_h').setValue(record.data.id_atasan_3);
				   xK.spj.formKaryawan.getForm().findField('id_atasan_3').setValue(record.data.id_atasan_3);					   
				   xK.spj.formKaryawan.getForm().findField('id_atasan_3').setRawValue(record.data.name_atasan_3);
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

  },

  genaretformKaryawan : function() { 
		var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
      this.formKaryawan = Ext.create('Ext.form.Panel', {                
                region 	: 'center',
                //width:400,
                title:'FORM KARYAWAN',
                url: 'karyawan/save_karyawan',
                autoScroll:true,				
                id:'form_karyawan',
                split: true,
                bodyPadding: 10,
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100                    
                },
                items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: 'DATA KARYAWAN',
                    labelSeparator : '',
					bodyPadding: 10,
                    labelStyle: 'font-weight:bold;padding:0',
                    fieldDefaults: {
                        labelAlign: 'top'
                    }
                },{
                    xtype: 'hidden',
                    name:'id_seq_karyawan'
                },{
                    xtype: 'textfield',
					name:'nik_karyawan',
                    fieldLabel: 'NIK',
                    width: 380,
                    name:'nik_karyawan',
                    afterLabelTextTpl: required,
                    allowBlank: false
                },{
                    xtype: 'textfield',
					name:'name_karyawan',
                    fieldLabel: 'Nama Karyawan',
                    width: 380,
                    name:'name_karyawan',
					afterLabelTextTpl: required,
                    allowBlank: false
                },{
                    xtype: 'hidden',
					id:'id_jabatan_h',
                    name:'id_jabatan_h'
                },{
                    xtype: 'hidden',
					id:'id_atasan_1_h',
                    name:'id_atasan_1_h'
                },{
					xtype : 'combo',
					name: 'id_atasan_1',					
					hiddenName: 'id_atasan_1',
                    fieldLabel: 'Atasan 1',
                    width: 380,
					displayField : 'name_karyawan',					
					valueField	 : 'id_seq_karyawan',
					tpl: '<tpl for="."><div class="x-boundlist-item">{nik_karyawan} - {name_karyawan}</div></tpl>',	
					minChars		: 1,
					queryDelay		: 1,
					pageSize		: 10,
					editable	 : true,
					hidden:true,
					allowBlank: true,
					listWidth:380,
					store		 : this.getModelAtasan(),
					triggerAction: 'all',
					listeners : {
									select : function(combo,value,r){
											Ext.getCmp('id_atasan_1_h').setValue(value[0].data.id_seq_karyawan);
									}
								}
                },{
                    xtype: 'hidden',
					id:'id_atasan_2_h',
                    name:'id_atasan_2_h'
                },{
					xtype : 'combo',
					name:'id_atasan_2',
                    fieldLabel: 'Atasan 2',
                    width: 380,
					hidden:true,
					displayField : 'name_karyawan',			
					tpl: '<tpl for="."><div class="x-boundlist-item">{nik_karyawan} - {name_karyawan}</div></tpl>',		
					valueField	 : 'id_seq_karyawan',
					minChars		: 1,
					queryDelay		: 1,
					pageSize		: 10,
					editable	 : true,
					allowBlank: true,
					listWidth:380,
					store		 : this.getModelAtasan(),
					triggerAction: 'all',
					listeners : {
									select : function(combo,value,r){
											Ext.getCmp('id_atasan_2_h').setValue(value[0].data.id_seq_karyawan);
									}
								}
                },{
                    xtype: 'hidden',
					id:'id_atasan_3_h',
                    name:'id_atasan_3_h'
                },{
					xtype : 'combo',
					name:'id_atasan_3',
                    fieldLabel: 'Atasan 3',
                    width: 380,
					hidden:true,
					displayField : 'name_karyawan',		
					tpl: '<tpl for="."><div class="x-boundlist-item">{nik_karyawan} - {name_karyawan}</div></tpl>',		
					valueField	 : 'id_seq_karyawan',
					minChars		: 1,
					queryDelay		: 1,
					pageSize		: 10,
					editable	 : true,
					allowBlank: true,
					listWidth:380,
					store		 : this.getModelAtasan(),
					triggerAction: 'all',
					listeners : {
									select : function(combo,value,r){
											Ext.getCmp('id_atasan_3_h').setValue(value[0].data.id_seq_karyawan);
									}
								}
                }
            ],
            buttons: [{
                    text: 'Reset',
					iconCls:'btn-refresh',
                    handler: function() {
                        this.up('form').getForm().reset();
                       
                    }
                }, {
                    text: 'Save',
					iconCls: 'btn-save',							
					hidden:<?php echo (($role->cr == "true") ? "false" : "true"); ?>,
                    handler: function() {                         
                        var form = this.up('form').getForm();						
                        if (form.isValid()) {
                                form.submit({
                                    clientValidation: true,    
                                    waitMsg:'Waiting, proccess saving....!!',
                                    success: function(result,opt) {
                                       Ext.example.msg('Info', opt.result.msg, 10000);
                                       Ext.getCmp('grid_karyawan').store.load();
                                       form.reset();                                      
									   
                                    },
                                    failure: function(result,opt) {
                                        Ext.example.msg('Erorr!!', opt.result.msg, 8000);
                                    }
                                });                        
                        }else {
                                Ext.example.msg('Erorr!!', "Lengkapi fields");
                        }
                    }
                },{
                    text: 'Delete',     
					iconCls: 'btn-delete',										
					hidden:<?php echo (($role->del == "true") ? "false" : "true"); ?>,
                    handler: function() {
                         var form = this.up('form').getForm();
                         var params = 'id_seq_karyawan='+form.findField('id_seq_karyawan').getValue();
                         if (form.findField('id_seq_karyawan').getValue() == "") {
                             Ext.example.msg('Info', 'Select row data, not find ID', 10000);
                             return;
                         }
						 Ext.Msg.confirm("INFO", "Delete?", function(e){if(e == 'yes'){
									 Ext.Ajax.request({
												url:'karyawan/delete_karyawan',
												waitMsg: 'Waiting...',
												method:'POST',
												params:params,
												success:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
														Ext.getCmp('grid_karyawan').store.load();
														Ext.getCmp('form_karyawan').getForm().reset();
												},
												failure:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
												}
										});
						}});	
						}						
                    
                }]
            });
  },
  doLayoutPanel : function(){
	 this.genaretformKaryawan();
	 this.getGridListKaryawan();     
	var dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'border'
				,margin:'2 2 2 2'
				,items:[
                                this.gridKaryawan
                                 ,this.formKaryawan
				]
            }); 		
            
  }

}

xK.spj.init();

  
</script>