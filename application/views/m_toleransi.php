<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.m_toleransi = {
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
                }//'fieldscmbmachinetes','m_toleransi/get_data_machine_test','fieldscmbmachinetes','20'
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
    return this.getStoredData('fieldscmbmachinetes','m_toleransi/get_data_machine_test','fieldscmbmachinetes','20');
    
  },
  get_field_name_rpt : function() {
      // register model
    Ext.define('fieldname_rpt', {
        extend: 'Ext.data.Model',
        idProperty: 'fieldsreport',
        fields: [
           {name: 'idfield'},
           {name: 'namefield'},
           {name: 'textlabel'}
        ]
    });
    return this.getStoredData('fieldname_rpt','m_toleransi/get_name_field_rpt','fieldname_rpt','20');
    
  },
  //data untuk grid
  getDataTolerasi : function() {
    // register model
    Ext.define('m_toleransi', {
        extend: 'Ext.data.Model',
        idProperty: 'm_toleransi',
        fields: [
           {name: 'id'},
           {name: 'compound'},
           {name: 'name_report'},
           {name: 'field_params'},
           {name: 'start_toleransi'},
           {name: 'end_toleransi'},
		   {name: 'remarks'},
		   {name: 'machine_test_report'}
        ]
    });

    var stored_data = this.getStoredData('m_toleransi','m_toleransi/get_list_machine_field_toleransi','m_toleransi','20');
    return stored_data;
  },  
 
  getModelDataItemCompund : function() {
     // register model
    Ext.define('itemCompound', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'idmaterial'},
           {name: 'name'}
        ]
    });

    var stored_data = this.getStoredData('itemCompound','input_report_test/get_item_compound','itemCompound','20');
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
        var storeMdl = this.getDataTolerasi();
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
            title:'LIST MASTER TOLERANSI',
            split: true,            
            columns: [      {
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
						text: 'Field',
						sortable: true,                    
						dataIndex: 'field_params',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Sample',
						sortable: true,                    
						dataIndex: 'compound',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Min',
						sortable: true,                    
						dataIndex: 'start_toleransi',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Max',
						sortable: true,                    
						dataIndex: 'end_toleransi',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 100
					},{
						text: 'Label Report',
						sortable: true,                    
						dataIndex: 'remarks',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 300
					}
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
					var r = record;
					xK.m_toleransi.formSample.getForm().loadRecord(record);	
					xK.m_toleransi.formSample.getForm().findField('id').setValue(record.data.id);  
					Ext.getCmp('cmb_machine_test_f').setValue(record.data.machine_test_report);						
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
      
      var store_frp = this.get_field_name_rpt();
      store_frp.load();
      
      
      var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
      this.formSample = Ext.create('Ext.form.Panel', {                
               
                url: 'm_toleransi/save',
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
                                valueField	 : 'idreport',
                                name:'cmb_machine_test_f',
								id:'cmb_machine_test_f',
                                width: 350,
								allowBlank: false,
                                listeners : {
                                        render : function(cmb) {
                                            
                                        },
                                        select : function(o,row,r){
                                            xK.m_toleransi.gridSample.store.load({params:{lookup:o.getValue()}});  
                                            Ext.getCmp('cmb_field_machine_f_tole').reset();
											//alert(o.getValue());
											Ext.getCmp('cmb_field_machine_f_tole').store.getProxy().setExtraParam("id_rpt", o.getValue());
                                            Ext.getCmp('cmb_field_machine_f_tole').store.load();  
                                        }
                                }
                            })
                ,{
                        xtype : 'combo',					
                        name:'compound',
                        id:'sample_toleransi',
                        fieldLabel: 'Sample',
                        displayField : 'name',					
                        valueField	 : 'idmaterial',
                        tpl: '<tpl for="."><div class="x-boundlist-item">{idmaterial}</div></tpl>',	
                        minChars		: 1,
                        queryDelay		: 1,
                        pageSize		: 10,
                        editable	 : true,		
                        width:350,					
                        store		 : this.getModelDataItemCompund(),
                        triggerAction: 'all',
                        listeners : {
                                        select : function(o,row,r){


                                        }
                        }					
                },new Ext.form.field.ComboBox({
                                typeAhead: true,
                                triggerAction: 'all',
                                fieldLabel: 'Field',
                                store		 : store_frp,
                                displayField : 'namefield',
                                tpl: '<tpl for="."><div class="x-boundlist-item">{textlabel} ({namefield})</div></tpl>',	
                                allowBlank: false,
                                valueField	 : 'namefield',
                                name:'field_params',
                                id:'cmb_field_machine_f_tole',
                                 width:350,
                                listeners : {
                                        render : function(cmb) {
                                            
                                        },
                                        select : function(o,row,r){
                                            
                                        }
                                }
                            })
                ,{
                    xtype: 'numberfield',
                    name:'start_toleransi',
                    allowBlank: false,
                    fieldLabel: 'Min'
                },{
                    xtype: 'numberfield',
                    name:'end_toleransi',
                    allowBlank: false,
                    fieldLabel: 'Max'
                },{
                    xtype: 'textfield',
                    name:'remarks',
                    allowBlank: true,
                    fieldLabel: 'Label Report',
					 maxLength: 100
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
                        var mx = form.findField('end_toleransi').getValue() - form.findField('start_toleransi').getValue();
                        if (mx < 0) {
                            return Ext.example.msg('Info', "Fatal. Field Min Max tidak masuk range yang benar.", 10000);
                        }
                        if (form.isValid()) {
                                form.submit({
                                    clientValidation: true,    
                                    waitMsg:'Waiting, proccess saving....!!',
                                    success: function(result,opt) {
                                       Ext.example.msg('Info', opt.result.msg, 10000);
                                       xK.m_toleransi.gridSample.store.load({params:{id_rpt:Ext.getCmp('cmb_machine_test_f').getValue()}}); 				   
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
												url:'m_toleransi/delete',
												waitMsg: 'Waiting...',
												method:'POST',
												params:params,
												success:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
														xK.m_toleransi.gridSample.store.load();	
														Ext.getCmp('form_machine_detail_field').getForm().reset();
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
	 this.genaretformSample();
	 this.getGridListKaryawan();     
	var dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'border'
				,items:[
                                    this.gridSample,
                                    {
                                        region 	: 'center',
                                        layout:'fit',
                                        title:'Form Master Toleransi',  
                                        split: true,
                                        items:[this.formSample]
                                    }
				]
            }); 		
            
  }

}

xK.m_toleransi.init();

  
</script>