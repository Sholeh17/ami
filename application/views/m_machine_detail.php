<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.m_machine_detail = {
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
    Ext.define('fieldscmbmachine', {
        extend: 'Ext.data.Model',
        idProperty: 'fieldsreport',
        fields: [
           {name: 'idmachine'},
           {name: 'name'}
        ]
    });
    return this.getStoredData('fieldscmbmachine','m_machine_detail/get_data_group_machine','fieldscmbmachine','20');
    
  },
  getModelKaryawan : function() {
     // register model
    Ext.define('m_machine_detail', {
        extend: 'Ext.data.Model',
        idProperty: 'm_machine_detail',
        fields: [
           {name: 'idreport'},
           {name: 'idmachine'},
           {name: 'name'},
           {name: 'name_report'},
           {name: 'orderindex'},
           {name: 'is_graph_report'}
        ]
    });

    var stored_data = this.getStoredData('m_machine_detail','m_machine_detail/get_data_machine','m_machine_detail','20');
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
            id:'grid_machine_detail',
            tbar:[searchField],
            features: [filterfeature],
            title:'LIST MACHINE',
            split: true,            
            columns: [  {
						text: 'Group Machine',
						sortable: true,                    
						dataIndex: 'name',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 200
					},{
						text: 'Machine Test',
						sortable: true,                    
						dataIndex: 'name_report',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					},{
						text: 'Is Graph',
						sortable: true,                    
						dataIndex: 'is_graph_report',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					}
				],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                   var r = record;
				   xK.m_machine_detail.formSample.getForm().loadRecord(record);	
                                   xK.m_machine_detail.formSample.getForm().findField('id').setValue(record.data.idreport);	
                                   
				   Ext.getCmp('cmb_group_machine').setValue(record.data.idmachine);	
				   xK.m_machine_detail.formSample.getForm().findField('name_report').setValue(record.data.name_report);
                                   
				   
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
                region 	: 'center',
                //width:400,
                title:'Form Machine Detail',
                url: 'm_machine_detail/save',
                autoScroll:true,				
                id:'form_machine_detail',
                split: true,
                bodyPadding: 10,
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100                    
                },
                items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: 'Data Machine',
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
                                displayField : 'name',
                                valueField	 : 'idmachine',
                                name:'cmb_group_machine',
                                width: 380,
                                id:'cmb_group_machine',
                                listeners : {
                                        render : function(cmb) {
                                            
                                        },
                                        select : function(o,row,r){
                                           xK.m_machine_detail.gridSample.store.load({params:{group_machine:o.getValue()}});   
                                        }
                                }
                            })
                    ,{
                    xtype: 'textfield',
                    name:'name_report',
                    fieldLabel: 'Machine Test',
                    width: 380
                },
				{
					xtype: 'checkbox',
					name:'is_graph_report',
					checked:false,
					boxLabel: 'Is Graph?',
					inputValue: 'YES'
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
                                       Ext.getCmp('grid_machine_detail').store.load({params:{group_machine:Ext.getCmp('cmb_group_machine').getValue()}});
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
                                        
					hidden:true<?php //echo (($role->del == "true") ? "false" : "true"); ?>,
                    handler: function() {
                         var form = this.up('form').getForm();
                         var params = 'idmaterial='+form.findField('id').getValue();
                         if (form.findField('id').getValue() == "") {
                             Ext.example.msg('Info', 'Select row data, not find ID', 10000);
                             return;
                         }
						 Ext.Msg.confirm("INFO", "Delete?", function(e){if(e == 'yes'){
									 Ext.Ajax.request({
												url:'m_machine_detail/delete',
												waitMsg: 'Waiting...',
												method:'POST',
												params:params,
												success:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
														Ext.getCmp('grid_machine_detail').store.load();
														Ext.getCmp('form_machine_detail').getForm().reset();
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
				,margin:'2 2 2 2'
				,items:[
                                this.gridSample
                                 ,this.formSample
				]
            }); 		
            
  }

}

xK.m_machine_detail.init();

  
</script>