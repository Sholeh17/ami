<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.fgg = {
  dynamicPanel:null,
  gridListFormulir : null,
  grid_setting_graph : null,
  store_master_agent : null,
  criteriaEnum:"Reguler",
  scaleEnum:"Lab Scale",
  winListFormulir:null,
  sample:'',
  storeFields:'',
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
  
  
 getModelItemReports : function() {
     // register model
    Ext.define('mitemsreport', {
        extend: 'Ext.data.Model',
        idProperty: 'm_listformulir',
        fields: [
           {name: 'idreport'},
           {name: 'name_report'},
           {name: 'orderindex'}
        ]
    });
    var stored_data = this.getStoredData('mitemsreport','report_graph/get_list_item_test_graph','mitemsreport','20');
    stored_data.load();
    return stored_data;
  }, 
  
  get_field_reporttest : function() {
      // register model
    Ext.define('fieldsreportGraph', {
        extend: 'Ext.data.Model',
        idProperty: 'fieldsreport',
        fields: [
           {name: 'textlabel'},
           {name: 'fields'}
        ]
    });
    this.storeFields= this.getStoredData('fieldsreportGraph','report_graph/get_filed_itemtes','fieldsreportGraph','20');
    
  },
  
  getfiledSettingGraph : function() {
     // register model
    Ext.define('m_setting_graph', {
        extend: 'Ext.data.Model',	
        fields: [
            {name:'series'},
            {name:'title_y'},
            {name:'title_x'},
            {name:'y_field_data_name'},
            {name:'x_field_data_name'},
            {name:'x_categorit_axis_name'},
            {name:'y_field_data'},
            {name:'x_field_data'},
            {name:'x_categorit_axis'}
        ]
    });
	
	var stored_data = Ext.create('Ext.data.Store', {			
				autoDestroy: true,
				model: 'm_setting_graph',
				storeId: 'm_setting_graph',
				listeners: {
					load: function() {	
														
					}
				},
				proxy: {	
					url: 'input_report_test/get_list_formulir_items',										
					type: 'ajax',
					reader: {
						type: 'json',						
						root: 'data'
					}
				},				
				sorters: [{
					property: 'start',
					direction: 'ASC'
				}]
			});		
	return stored_data;
    
  },  
  generateGridSeriesSetting : function() {
     var storeMdl = this.getfiledSettingGraph();
     var rowEditing = new Ext.grid.plugin.CellEditing({
                                    clicksToEdit: 1
                                    });
     this.grid_setting_graph = Ext.create('Ext.grid.Panel',  {
            store: storeMdl,    
            plugins: [rowEditing],	
            height:200,
            margin: '25 0 0 0',
            columnLines: true,           
            columns: [{
                        text: 'Series / Legend',
                        sortable: true,                    
                        dataIndex: 'series',
                        editor: {
                            listeners : {
                                focus : function(o) {
                                        o.selectText();
                                }							
                            }
                        },	
                        width: 300
                     },
                     {
                        text: 'Data',
                        sortable: false, 
                        flix:1,
                        renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                          
                            return value;
                        },
                        dataIndex: 'y_field_data',
                        editor: new Ext.form.field.ComboBox({
                                typeAhead: true,
                                triggerAction: 'all',
                                store		 : xK.fgg.storeFields,
                                displayField : 'textlabel',
                                valueField	 : 'fields',
                                name:'cmb_y_field',
                                id:'cmb_y_xield',
                                listeners : {
                                        render : function(cmb) {
                                            /*if (!Ext.isEmpty(Ext.getCmp('list_report_test_graph'))) {
                                                var idreport = Ext.getCmp('list_report_test_graph').getValue();
                                                cmb.store.load({params:{idreport:idreport}});
                                            }  */ 
                                        },
                                        select : function(o,row,r){
                                            
                                            var gR = xK.fgg.grid_setting_graph.getSelectionModel().hasSelection();
                                            var rowX = xK.fgg.grid_setting_graph.getSelectionModel().getSelection()[0];
                                            rowX.set('y_field_data_name',o.getValue()); 
                                            
                                        }
                                }
                            }),	
                        width: 150
                     }/*,
                     {
                        text: 'X Field  (Values)',
                        sortable: true,                    
                        dataIndex: 'x_field_data',
                        editor: new Ext.form.field.ComboBox({
                                typeAhead: true,
                                triggerAction: 'all',
                                store		 : xK.fgg.storeFields,
                                displayField : 'textlabel',
                                valueField	 : 'fields',
                                name:'cmb_x_field',
                                id:'cmb_x_xield',
                                listeners : {
                                        render : function(cmb) {
                                           // if (!Ext.isEmpty(Ext.getCmp('list_report_test_graph'))) {
                                                //var idreport = Ext.getCmp('list_report_test_graph').getValue();
                                              //  cmb.store.load({params:{idreport:idreport}});
                                            //}
                                        },
                                        select : function(o,row,r){
                                            var gR = xK.fgg.grid_setting_graph.getSelectionModel().hasSelection();
                                            var rowX = xK.fgg.grid_setting_graph.getSelectionModel().getSelection()[0];
                                            rowX.set('x_field_data_name',o.getValue());
                                        }
                                }
                            }),	
                        width: 150
                     },{
                        text: 'X Cateogory  (Axis)',
                        sortable: true,                    
                        dataIndex: 'x_categorit_axis',
                        editor: new Ext.form.field.ComboBox({
                                typeAhead: true,
                                triggerAction: 'all',
                                store		 : xK.fgg.storeFields,
                                displayField : 'textlabel',
                                valueField	 : 'fields',
                                name:'cmb_x_axis',
                                id:'cmb_x_axis',
                                listeners : {
                                        render : function(cmb) {
                                            var idreport = Ext.getCmp('list_report_test_graph').getValue();
                                            cmb.store.load({params:{idreport:idreport}});
                                        },
                                        select : function(o,row,r){
                                            var gR = xK.fgg.grid_setting_graph.getSelectionModel().hasSelection();
                                            var rowX = xK.fgg.grid_setting_graph.getSelectionModel().getSelection()[0];
                                            rowX.set('x_categorit_axis_name',o.getValue());
                                        }
                                }
                            }),
                        width: 150
                     },{
                        text: 'Y Title',
                        sortable: true,                    
                        dataIndex: 'title_y',
                        editor: {
                            listeners : {
                                focus : function(o) {
                                        o.selectText();
                                }							
                            }
                        },	
                        width: 200
                     },{
                        text: 'X Title',
                        sortable: true,                    
                        dataIndex: 'title_x',
                        editor: {
                            listeners : {
                                focus : function(o) {
                                        o.selectText();
                                }							
                            }
                        },	
                        width: 200
                     }*/
                    ],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                  
                },
               selectionchange: function(sm, records, rec) {
                    xK.fgg.grid_setting_graph.down('#removeSettingGraph').setDisabled(!records.length);
               }
            },
			tbar: [{
							text: 'Add',							
							iconCls: 'btn-add',							
							handler : function() {	
								var countRow = xK.fgg.grid_setting_graph.store.getCount();
								var r = Ext.create('m_setting_graph', {	
									series:'',
                                                                        title_y:'',
                                                                        title_x:'',
									y_field_data_name : '',
                                                                        x_field_data_name : '',
                                                                        x_categorit_axis_name : '',
                                                                        y_field_data : '',
                                                                        x_field_data : '',
                                                                        x_categorit_axis : ''
								}), edit = rowEditing;
								edit.cancelEdit();
								xK.fgg.grid_setting_graph.store.insert(countRow, r);								
								edit.startEditByPosition({
									row: countRow,
									column: 0
								});
							}
					},
					{
							itemId: 'removeSettingGraph',
							text: 'Remove',														
							iconCls: 'btn-delete',
							handler: function() {
								var countRow = xK.fgg.grid_setting_graph.store.getCount();
								var sm = xK.fgg.grid_setting_graph.getSelectionModel();
								var row = xK.fgg.grid_setting_graph.getSelectionModel().getSelection()[0];
  								
								var edit = rowEditing;
								edit.cancelEdit();
								xK.fgg.grid_setting_graph.store.remove(sm.getSelection());
								if (countRow > 0) {
									edit.startEditByPosition({
									row: (countRow-2),
									column: 0
									});									
								}	
								
							},
							disabled: true	
					}
			]
        });  
        
        
  },
  doLayoutPanel : function(){
        var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
        this.get_field_reporttest();
        xK.fgg.storeFields.load();
	this.generateGridSeriesSetting();   
        
        
        var xCategoris = {
                    xtype: 'fieldcontainer',
                    fieldLabel: 'X Category (Axis)',
                    //labelStyle: 'font-weight:bold;padding:0;',
                    layout: 'hbox',
                    fieldDefaults: {
                        labelAlign: 'left'
                    },

                    items: [ 
                        {
                            xtype: 'textfield',
                            hideLabel: true,
                            name:'cmb_x_axis',
                            id:'cmb_x_axis',
                            width:500
                        }, {
                            xtype: 'label',
                            text: 'Ex: Januari,februari,maret,april...',
                            labelStyle: 'font-weight:italic;padding:0;',
                            margins: '8 0 0 10'
                        }
                        
                ]
                };
        
        
	this.dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'anchor'
				,items:[
                                        {
                                        title:'Setting Graph',
                                        
                                        items : Ext.widget('form', {
                                                            
                                                            border: false,
                                                            bodyPadding: 5,
                                                            buttonAlign:'left',
                                                            fieldDefaults: {
                                                                labelAlign: 'left',
                                                                labelWidth: 150
                                                                //,labelStyle: 'font-weight:bold'
                                                            },
                                                            items: [
                                                                
                                                                {
                                                                    xtype : 'combo',
                                                                    typeAhead: true,
                                                                    name:'id_report_test',
                                                                    id:'id_report_test',
                                                                    fieldLabel: 'Machine Report',
                                                                    width: 480,
                                                                    displayField : 'name_report',			
                                                                    tpl: '<tpl for="."><div class="x-boundlist-item">{name_report}</div></tpl>',		
                                                                    valueField	 : 'idreport',
                                                                    remote:'local',
                                                                    editable	 : false,
                                                                    allowBlank: false,
                                                                    store		 : this.getModelItemReports(),
                                                                    triggerAction: 'all',
                                                                    listeners : {
                                                                            select : function(combo,value,r){
                                                                              
                                                                                var idreport = combo.getValue();
                                                                                if (!Ext.isEmpty(Ext.getCmp('cmb_y_field'))) {
                                                                                    Ext.getCmp('cmb_y_field').store.getProxy().extraParams = {idreport:idreport};											
                                                                                    Ext.getCmp('cmb_y_field').store.load();
                                                                                }
                                                                                
                                                                                
                                                                               if (!Ext.isEmpty(Ext.getCmp('cmb_y_xield'))) {
                                                                                    Ext.getCmp('cmb_y_xield').store.getProxy().extraParams = {idreport:idreport};											
                                                                                    Ext.getCmp('cmb_y_xield').store.load();
                                                                                }
                                                                                
                                                                                
                                                                                if (!Ext.isEmpty(Ext.getCmp('cmb_x_axis'))) {
                                                                                    Ext.getCmp('cmb_x_axis').store.getProxy().extraParams = {idreport:idreport};											
                                                                                    Ext.getCmp('cmb_x_axis').store.load();
                                                                                }
                                                                                
                                                                                //Regetting Data.
                                                                                xK.fgg.get_data_config(idreport);
                                                                                
                                                                                
                                                                            }
                                                                    }
                                                                },
                                                                new Ext.form.field.ComboBox({
                                                                    typeAhead: true,
                                                                    fieldLabel: 'Tipe Graph',
                                                                    width: 380,
                                                                    name:'type_graph',
                                                                    id:'type_graph',
                                                                    allowBlank: false,
                                                                    triggerAction: 'all',
                                                                    store: [
                                                                        ['Line','Line'],
                                                                        ['Bar','Bar']
                                                                    ]
                                                                }),     
                                                                        
                                                                        xK.fgg.grid_setting_graph,
                                                                //xCategoris
                                                                
                                                                    new Ext.form.field.ComboBox({
                                                                    typeAhead: true,
                                                                    triggerAction: 'all',
                                                                    width: 380,
                                                                    fieldLabel: 'X Cateogory  (Axis)',
                                                                    store		 : xK.fgg.storeFields,
                                                                    displayField : 'textlabel',
                                                                    valueField	 : 'fields',
                                                                    name:'cmb_x_axis',
                                                                    id:'cmb_x_axis',
                                                                    listeners : {
                                                                            render : function(cmb) {
                                                                                
                                                                            },
                                                                            select : function(o,row,r){
                                                                                
                                                                            }
                                                                    }
                                                                })     
                                                                
                                                        ],
                                                            buttons: [
                                                                    {
                                                                            text: 'Save Setting',
                                                                            handler: this.OnsaveGrid
                                                                    }]
                                    }),
                                    
                                            anchor:'100%'
                                    }
                                    
								   
				]
            }); 		
            
  },
  OnsaveGrid : function() {
    var param_x = "";
    var j = 0;
    var grid_item_trx = xK.fgg.grid_setting_graph.getStore();
    
    grid_item_trx.each(function(rec) {
            param_x += "&data[items]["+j+"][series]="+rec.get('series');
            param_x += "&data[items]["+j+"][title_y]="+rec.get('title_y');
            param_x += "&data[items]["+j+"][title_x]="+rec.get('title_x');
            param_x += "&data[items]["+j+"][y_field_data]="+rec.get('y_field_data_name');
            param_x += "&data[items]["+j+"][x_field_data]="+rec.get('x_field_data_name');
            param_x += "&data[items]["+j+"][x_categorit_axis]="+rec.get('x_categorit_axis_name');
            j++;
    });
    
    if (Ext.getCmp('id_report_test').getValue() == null || Ext.getCmp('id_report_test').getValue() == "") {
        Ext.Msg.alert('Info',"Input Report Test");
        return;
    }
    
    
    if (Ext.getCmp('type_graph').getValue() == null || Ext.getCmp('type_graph').getValue() == "") {
        Ext.Msg.alert('Info',"Input tipe Graph");
        return;
    }
    
    
    if (j <= 0) {
        Ext.Msg.alert('Info',"Config Graph kosong");
        return;
    }
    
    
    var form = this.up('form').getForm();
    
        form.submit({  
                url:'report_graph/save_config',
                waitMsg:'Waiting, proccess saving....!!',
                params:param_x,
                method:'POST',
                success: function(result,opt) {
                    var jsonData = opt.result;
                       
                        Ext.Msg.alert('Info',jsonData.msg,function() {
                        }); 

                },
                failure: function(result,opt) {
                        Ext.Msg.alert('Erorr!!', opt.result.msg);
                }
        }); 
    
  },
  OnresetForm: function() {
    Ext.ComponentQuery.query('[name=list_formulir_test_graph]')[0].setValue(""); 
    Ext.ComponentQuery.query('[name=list_report_test_graph]')[0].setValue("");
   
  },
  get_data_config: function(idreport) {
         Ext.Ajax.request({
                    url: 'report_graph/get_data_config',    // where you wanna post
                    params:'id_report_test='+idreport,
                    success: function(response){																
                            var jsonData = Ext.decode(response.responseText);
                            var data = jsonData.data;
                            var countData = jsonData.count * 1;
                            
                            Ext.getCmp('type_graph').setValue("");
                            //Ext.getCmp('cmb_x_axis').setValue("");
                            console.log(data);
                            if (countData > 0) { 	
                                xK.fgg.grid_setting_graph.store.loadData(data,false);
                                
                                if (!Ext.isEmpty(data[0].type_graph)) {
                                    Ext.getCmp('type_graph').setValue(data[0].type_graph);
                                } 
                                if (!Ext.isEmpty(data[0].x_categorit_axis)) {
                                    Ext.getCmp('cmb_x_axis').setValue(data[0].x_categorit_axis);
                                }
                                
                            } else {
                                xK.fgg.grid_setting_graph.store.removeAll();
                            }
                            
                            

                    }

            });
  }  

}

xK.fgg.init();

  
</script>