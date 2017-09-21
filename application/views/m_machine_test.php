<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.m_machine_test = {
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
            title:'LIST MACHINE TEST',
            split: true,            
            columns: [{
                        text: 'NAME',
                        sortable: true,                    
                        dataIndex: 'name',
                        filterable: true,
                        filter: {
                                type: 'string',
                                // specify disabled to disable the filter menu
                                disabled: false,
                        },
                        width: 100
                     },
                     {
                        text: 'Description',
                        sortable: true,                    
                        dataIndex: 'description',
                        filterable: true,
                        filter: {
                                type: 'string',
                                // specify disabled to disable the filter menu
                                disabled: false,
                        },
                        width: 500
                     }
                    ],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                   var r = record;
				   xK.m_machine_test.formKaryawan.getForm().loadRecord(record);					   
                                           // xK.m_machine_test.formKaryawan.getForm().findField('id_atasan_1_h').setValue(record.data.id_atasan_1);	
				  
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
                title:'FORM MACHINE TEST',
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
                    xtype: 'hidden',
                    name:'id_seq_karyawan'
                },{
                    xtype: 'textfield',
                    name:'name',
                    fieldLabel: 'NAME',
                    width: 380,
                    afterLabelTextTpl: required,
                    allowBlank: false
                },{
                    xtype: 'textarea',
                    name:'description',
                    fieldLabel: 'Description',
                    width: 380,
                    //afterLabelTextTpl: required,
                    allowBlank: true
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

xK.m_machine_test.init();

  
</script>