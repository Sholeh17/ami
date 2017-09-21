<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.m_sample = {
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
  getModelKaryawan : function() {
     // register model
    Ext.define('m_sample', {
        extend: 'Ext.data.Model',
        idProperty: 'm_sample',
        fields: [
           {name: 'idmaterial'},
           {name: 'name'},
		   {name: 'category'},
		   {name: 'label_testing_report'},
		   {name: 'no_reg_checksheet'}
        ]
    });

    var stored_data = this.getStoredData('m_sample','m_sample/get_data_sample','m_sample','20');
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
            id:'grid_simple',
            tbar:[searchField],
            features: [filterfeature],
            title:'LIST SAMPLE',
            split: true,            
            columns: [{
						text: 'Name',
						sortable: true,                    
						dataIndex: 'idmaterial',
						filterable: true,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false,
						},
						width: 130
					},{
						text: 'Description',
						sortable: true,                    
						dataIndex: 'name',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					},{
						text: 'Category',
						sortable: true,                    
						dataIndex: 'category',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					},{
						text: 'Lable Report Pengujian',
						sortable: true,                    
						dataIndex: 'label_testing_report',
						filterable: true,
						flex:1,
						filter: {
							type: 'string',
							// specify disabled to disable the filter menu
							disabled: false
						}
						//,width: 200
					},{
						text: 'No Reg Checksheet',
						sortable: true,                    
						dataIndex: 'no_reg_checksheet',
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
				   xK.m_sample.formSample.getForm().loadRecord(record);	
                                   xK.m_sample.formSample.getForm().findField('id').setValue(record.data.idmaterial);	
				   xK.m_sample.formSample.getForm().findField('idmaterial').setValue(record.data.idmaterial);	
				   xK.m_sample.formSample.getForm().findField('name').setValue(record.data.name);
                                   
				   
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
		var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
		
	   var sample_category = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"Compound", "name":"Compound"},
				{"id":"Raw Material", "name":"Raw Material"},
				{"id":"Rubberized", "name":"Rubberized"},	
                                {"id":"Tire", "name":"Tire"},
                                {"id":"Other", "name":"Other"}
			]
		});	
		
      this.formSample = Ext.create('Ext.form.Panel', {                
                region 	: 'center',
                //width:400,
                title:'FORM SAMPLE',
                url: 'm_sample/save',
                autoScroll:true,				
                id:'form_simple',
                split: true,
                bodyPadding: 10,
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100                    
                },
                items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: 'Data Sample',
                    labelSeparator : '',
					bodyPadding: 10,
                    labelStyle: 'font-weight:bold;padding:0',
                    fieldDefaults: {
                        labelAlign: 'top'
                    }
                },{
                    xtype:'hidden',
                    name:'id'
                },{
						xtype: 'combo',
						typeAhead: true,
						displayField : 'name',
						fieldLabel: 'Category',
						name:'category',
						//value:'SEMUA',
						allowBlank:false,
						valueField : 'id',
						width: 380,
						editable	: false,
						triggerAction: 'all',
						store:sample_category,
						allowBlank: false
				},{
                    xtype: 'textfield',
                    name:'idmaterial',
                    fieldLabel: 'Name Sample',
                    width: 380,
                    afterLabelTextTpl: required,
                    allowBlank: false
                },{
                    xtype: 'textfield',
                    name:'name',
                    fieldLabel: 'Description',
                    width: 380
                },{
                    xtype: 'textfield',
                    name:'label_testing_report',
                    fieldLabel: 'Lable Report Pengujian',
                    width: 380
                },{
                    xtype: 'textfield',
                    name:'no_reg_checksheet',
                    fieldLabel: 'No Reg Checksheet',
                    width: 380
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
                                       Ext.getCmp('grid_simple').store.load();
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
												url:'m_sample/delete',
												waitMsg: 'Waiting...',
												method:'POST',
												params:params,
												success:function(result,opt){
														var jsonData = Ext.JSON.decode(result.responseText);
														Ext.example.msg('Info', jsonData.msg, 10000);
														Ext.getCmp('grid_simple').store.load();
														Ext.getCmp('form_simple').getForm().reset();
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

xK.m_sample.init();

  
</script>