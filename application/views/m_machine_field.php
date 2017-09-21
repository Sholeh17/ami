<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.m_machine_field = {
  formSample : null,
  gridSample : null,
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
  get_field_reporttest : function() {
      // register model
    Ext.define('fieldscmbmachinetes', {
        extend: 'Ext.data.Model',
        idProperty: 'fieldsreport',
        fields: [
           {name: 'idreport'},
           {name: 'name_report'}
        ]
    });
    return this.getStoredData('fieldscmbmachinetes','m_machine_field/get_data_machine_test','fieldscmbmachinetes','20');
    
  },
  getModelKaryawan : function() {
     // register model
    Ext.define('m_machine_field', {
        extend: 'Ext.data.Model',
        idProperty: 'm_machine_field',
        fields: [
           {name: 'idfield'},
           {name: 'idmachinereport'},
           {name: 'namefield'},
           {name: 'textlabel'},
           {name: 'xtype'},
           {name: 'name_report'},
           {name: 'sortorder'},
		   {name: 'UOM'}
        ]
    });

    var stored_data = this.getStoredData('m_machine_field','m_machine_field/get_list_machine_field','m_machine_field','20');
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
        this.gridSample = Ext.widget('gridpanel', {
            store: storeMdl,
            region:'west',			
            width:Ext.getBody().getViewSize().width-470,
            loadMask: true,
            id:'grid_machine_detail_field',
            tbar:[searchField],
            features: [filterfeature],
            title:'LIST MACHINE',
            split: true,            
            columns: [      {
						text: 'ID Report',
						sortable: true,                    
						dataIndex: 'idmachinereport',
						filterable: true,
						filter: {
							type: 'int',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 70
					},{
						text: 'Machine Test',
						sortable: true,                    
						dataIndex: 'name_report',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 200
					},{
						text: 'Label Field',
						sortable: true,                    
						dataIndex: 'textlabel',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Name Field',
						sortable: true,                    
						dataIndex: 'namefield',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'UOM',
						sortable: true,                    
						dataIndex: 'UOM',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Type Field',
						sortable: true,                    
						dataIndex: 'xtype',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'No Column',
						sortable: true,                    
						dataIndex: 'sortorder',
						filterable: true,
						filter: {
							type: 'int',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					}
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
					var r = record;
					xK.m_machine_field.formSample.getForm().loadRecord(record);	
                    xK.m_machine_field.formSample.getForm().findField('id').setValue(record.data.idfield);
					Ext.getCmp('cmb_machine_test').setValue(record.data.idmachinereport);	
					xK.m_machine_field.formSample.getForm().findField('label_field').setValue(record.data.textlabel);
                    xK.m_machine_field.formSample.getForm().findField('name_field').setValue(record.data.namefield);
                    xK.m_machine_field.formSample.getForm().findField('type_field').setValue(record.data.xtype);
                    xK.m_machine_field.formSample.getForm().findField('sort_order').setValue(record.data.sortorder);
					xK.m_machine_field.formSample.getForm().findField('UOM').setValue(record.data.UOM);
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

  genaretformSample : function() { 
      var store_grm = this.get_field_reporttest();
      store_grm.load();
        var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
      this.formSample = Ext.create('Ext.form.Panel', {                
               
                url: 'm_machine_field/save',
                autoScroll:true,				
                id:'form_machine_detail_field',
                
                bodyPadding: 10,
                fieldDefaults: {
                        labelAlign: 'left',
                        labelWidth: 150
                    },
                items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: 'Data Machine Test',
                    labelSeparator : '',
					bodyPadding: 10,
                    labelStyle: 'font-weight:bold;padding:0',
                    fieldDefaults: {
                        labelAlign: 'top'
                    }
                },{
                    xtype:'hidden',
                    name:'id'
                },new Ext.form.field.ComboBox({
                                typeAhead: true,
                                triggerAction: 'all',
                                store		 : store_grm,
                                displayField : 'name_report',
                                allowBlank: false,
                                valueField	 : 'idreport',
                                name:'cmb_machine_test',
                                width: 380,
                                id:'cmb_machine_test',
                                listeners : {
                                        render : function(cmb) {
                                            
                                        },
                                        select : function(o,row,r){
                                            xK.m_machine_field.gridSample.store.load({params:{lookup:o.getValue()}});  
                                        }
                                }
                            })
                ,{
                    xtype: 'textfield',
                    name:'label_field',
                    allowBlank: false,
                    fieldLabel: 'Label Field'
                },{
                    xtype: 'textfield',
                    name:'name_field',
                    allowBlank: false,
                    fieldLabel: 'Name Field',
                    width: 380
                },{
                    xtype: 'textfield',
                    name:'UOM',
                    allowBlank: false,
                    fieldLabel: 'UOM',
                    width: 380
                },new Ext.form.field.ComboBox({
                    typeAhead: true,
                    fieldLabel: 'Tipe Field',
                    width: 380,
                    name:'type_field',
                    id:'type_field',
                    allowBlank: false,
                    triggerAction: 'all',
                    store: [
                        ['textfield','textfield'],
                        ['numberfield','numberfield'],
                        ['datefield','datefield']
                    ]
                }),{
                    xtype: 'numberfield',
                    name:'sort_order',
                    allowBlank: false,
                    value:0,
                    minValue:0,
                    maxValue:50,
                    fieldLabel: 'No Column',
                    width: 250
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
                            
                            
                            var textlable = xK.m_machine_field.formSample.getForm().findField('label_field').getValue();
                            if (textlable.indexOf("\"") != -1 || textlable.indexOf("'") != -1) {
                                alert("Text field Tidak bisa mengandung karakter quote"); 
                                return;
                            }
							
							

                            var regexp = /^[a-zA-Z0-9-_]+$/;
                            var check = xK.m_machine_field.formSample.getForm().findField('name_field').getValue();
                            if (check.search(regexp) == -1) { 
                                alert('Name field hanya bisa mengandung karakter & angka dan tidak bisa spasi'); 
                                return; 
                            }
							
							if (check.indexOf("-") != -1) {
                                alert("Text field Tidak bisa mengandung '-' "); 
                                return;
                            }
                            
                                form.submit({
                                    clientValidation: true,    
                                    waitMsg:'Waiting, proccess saving....!!',
                                    success: function(result,opt) {
                                       Ext.example.msg('Info', opt.result.msg, 10000);
                                       xK.m_machine_field.gridSample.store.load({params:{lookup:Ext.getCmp('cmb_machine_test').getValue()}}); 				   
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
                         var params = 'id='+form.findField('id').getValue();
                         if (form.findField('id').getValue() == "") {
                             Ext.example.msg('Info', 'Select row data, not find ID', 10000);
                             return;
                         }
						 Ext.Msg.confirm("INFO", "Delete?", function(e){if(e == 'yes'){
									 Ext.Ajax.request({
												url:'m_machine_field/delete',
												waitMsg: 'Waiting...',
												method:'POST',
												params:params,
												success:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
														xK.m_machine_field.gridSample.store.load({params:{lookup:Ext.getCmp('cmb_machine_test').getValue()}}); 	
														Ext.getCmp('form_machine_detail_field').getForm().reset();
												},
												failure:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
												}
										});
						}});	
						}						
                    
                },{
                    text: 'Download Master CSV',
                    iconCls:'filebtn',
                    handler: function() {
                        var form = this.up('form').getForm();	
                        var params = 'id_report='+form.findField('cmb_machine_test').getValue()+'&name_report='+form.findField('cmb_machine_test').getRawValue();
                        
                        if (form.findField('cmb_machine_test').getValue() == null) {
                           alert("Select Combox Machine Report Test");
                           return;
                        }
                        location.href="m_machine_field/download_csv/?"+params;
                    }
                }]
            });
  },
  doLayoutPanel : function(){
	 this.genaretformSample();
	 this.getGridListKaryawan();     
	var dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'border'
				,margin:'2 2 2 2'
				,items:[
                                    this.gridSample,
                                    {
                                        region 	: 'center',
                                        layout:'fit',
                                        title:'Form Machine Detail',  
                                        split: true,
                                        items:[this.formSample]
                                    }
				]
            }); 		
            
  }

}

xK.m_machine_field.init();

  
</script>