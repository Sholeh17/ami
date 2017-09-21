<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.input_report_test = {
  dynamicPanel:null,
  gridListFormulir : null,
  gridKaryawan : null,
  store_master_agent : null,
  criteriaEnum:"Reguler",
  scaleEnum:"Lab Scale",
  winListFormulir:null,
  sample:'',
  r_type:'',
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
  getGridModelFormulir : function() {
     // register model
    Ext.define('m_listformulir', {
        extend: 'Ext.data.Model',
        idProperty: 'm_listformulir',
        fields: [
           {name: 'idformulir'},
           {name: 'no_req'},
           {name: 'date_request'},
           {name: 'date_line'},
           {name: 'sample'},
		   {name: 'type_request'},
           {name: 'criteria'},
           {name: 'scale'},
           {name: 'request_by'},
           {name: 'porpose'},
           {name: 'sample_spec'},
           {name: 'notes'},
           {name: 'scale'},
           {name: 'status'},
           {name: 'user_create'}
        ]
    });
    var stored_data = this.getStoredData('m_listformulir','input_report_test/get_formulir_test_is_approved','m_listformulir','20');
    return stored_data;
  },
  getGridMachine : function() {
     // register model
    Ext.define('m_machinetest', {
        extend: 'Ext.data.Model',
        idProperty: 'idmachine',
        fields: [
           {name: 'idmachine'},
           {name: 'name'}
		  
        ]
    });
    var stored_data = this.getStoredData('m_machinetest','input_report_test/get_list_formulir_items','m_machinetest','1000');
    return stored_data;
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
    var stored_data = this.getStoredData('mitemsreport','input_report_test/get_list_reports','mitemsreport','20');
    return stored_data;
  }, 
  
  doLayoutPanel : function(){
        var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	//this.getGridListMachine();   
	this.dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'anchor'
				,margin:'2 2 2 2'
				,items:[
                                        {
                                        //title:'Form Test Request Compound',
                                        items : Ext.widget('panel', {
                                                            
                                                            border: false,
                                                            bodyPadding: 5,
                                                           
                                                            fieldDefaults: {
                                                                labelAlign: 'left',
                                                                labelWidth: 150
                                                                //,labelStyle: 'font-weight:bold'
                                                            },
                                                            items: [
                                                                {
                                                                    xtype : 'combo',
                                                                    name:'list_formulir_test',
                                                                    id:'list_formulir_test',
                                                                    fieldLabel: 'Formulir Test',
                                                                    width: 650,
                                                                    displayField : 'no_req',			
                                                                    tpl: '<tpl for="."><div class="x-boundlist-item">{no_req} - {porpose}</div></tpl>',		
                                                                    valueField	 : 'idformulir',
                                                                    minChars		: 1,
                                                                    queryDelay		: 1,
                                                                    pageSize		: 10,
                                                                    editable	 : true,
                                                                    allowBlank: true,
                                                                    listWidth:680,
                                                                    store		 : this.getGridModelFormulir(),
                                                                    triggerAction: 'all',
                                                                    listeners : {
                                                                            select : function(combo,row,r){
                                                                                   var data = row[0].data;
																				   xK.input_report_test.sample = data.sample;
  																				   xK.input_report_test.r_type = data.type_request;
                                                                                 
																				   Ext.getCmp('list_report_test').reset();
                                                                                   Ext.getCmp('list_report_test').setDisabled(false);
                                                                                   Ext.getCmp('list_report_test').store.getProxy().extraParams = {id_formulir:data.idformulir};											
																				   Ext.getCmp('list_report_test').store.load();    
																					Ext.getCmp('forminputtestmachine').removeAll();  
																				 
																				 
																				   /*Ext.getCmp('machine_test_input').reset();
																				   Ext.getCmp('list_report_test').reset();
                                                                                   Ext.getCmp('machine_test_input').setDisabled(false);
                                                                                   Ext.getCmp('machine_test_input').store.getProxy().extraParams = {id_formulir:data.idformulir};											
										   Ext.getCmp('machine_test_input').store.load();   */ 
										    			
                                                                            }
                                                                    }
                                                                },
                                                                {
                                                                    xtype : 'combo',
                                                                    name:'machine_test_input',
                                                                    id:'machine_test_input',
                                                                    fieldLabel: 'Machine Test',
                                                                    width: 650,
																	hidden:true,
                                                                    displayField : 'name',			
                                                                    tpl: '<tpl for="."><div class="x-boundlist-item">{name}</div></tpl>',		
                                                                    valueField	 : 'idmachine',
                                                                    //minChars		: 1,
                                                                    //queryDelay		: 1,
                                                                    //pageSize		: 10,
                                                                    editable	 : true,
                                                                    allowBlank: false,
                                                                    listWidth:600,
                                                                    disabled:true,
                                                                    store		 : this.getGridMachine(),
                                                                    triggerAction: 'all',
                                                                    listeners : {
                                                                            select : function(combo,row,r){
                                                                                var data = row[0].data;
                                                                                   Ext.getCmp('list_report_test').reset();
                                                                                   Ext.getCmp('list_report_test').setDisabled(false);
                                                                                   Ext.getCmp('list_report_test').store.getProxy().extraParams = {idmachine:data.idmachine};											
										   Ext.getCmp('list_report_test').store.load();    
										    Ext.getCmp('forminputtestmachine').removeAll();            
                                                                            }
                                                                    }
                                                                },
                                                                {
                                                                    xtype : 'combo',
                                                                    name:'list_report_test',
                                                                    id:'list_report_test',
                                                                    fieldLabel: 'Machine Report',
                                                                    width: 650,
																	disabled:true,
                                                                    displayField : 'name_report',			
                                                                    tpl: '<tpl for="."><div class="x-boundlist-item">{name_report}</div></tpl>',		
                                                                    valueField	 : 'idreport',
                                                                    minChars		: 1,
                                                                    queryDelay		: 1,
                                                                    pageSize		: 10,
                                                                    editable	 : true,
                                                                    allowBlank: true,
                                                                    listWidth:380,
                                                                    store		 : this.getModelItemReports(),
                                                                    triggerAction: 'all',
                                                                    listeners : {
                                                                            select : function(combo,value,r){
                                                                                           
                                                                            }
                                                                    }
                                                                },
																{
																	xtype: 'button',
																	cls: 'contactBtn',
																	scale: 'large',
                                                                                                                                        hidden:<?php echo (($role->cr == "true") ? "false" : "true"); ?>,
																	margin: '5 5 5 5',
																	text: 'Genarate form Input',
																	handler: function() {
																		Ext.getCmp('forminputtestmachine').removeAll();    
																		xK.input_report_test.onInputTestForm();
																	}
																},{
																	xtype: 'button',
																	cls: 'contactBtn',
																	scale: 'large',
                                                                    hidden:<?php echo (($role->print == "true") ? "false" : "true"); ?>,
																	margin: '5 5 5 5',
                                                                    disabled:false,
                                                                    id:'btn_print_sheet',
																	text: 'Print Sheet',
																	handler: function() {
																		//Ext.getCmp('forminputtestmachine').removeAll();    
																		xK.input_report_test.onPrintFormSheet(0);
																	}
																},{
																	xtype: 'button',
																	cls: 'contactBtn',
																	scale: 'large',
                                                                    hidden:<?php echo (($role->print == "true") ? "false" : "true"); ?>,
																	margin: '5 5 5 5',
                                                                    disabled:false,
                                                                    id:'btn_print_excel',
																	text: 'Export To Excel',
																	handler: function() {
																		//Ext.getCmp('forminputtestmachine').removeAll();    
																		xK.input_report_test.onPrintFormSheet(1);
																	}
																}
																<? if($this->session->userdata('owner') == 'RD'){} else{ ?>
																,{
																	xtype: 'button',
																	cls: 'contactBtn',
																	scale: 'large',
                                                                     hidden:<?php echo (($role->print == "true") ? "false" : "true"); ?>,
																	margin: '5 5 5 5',
                                                                    disabled:false,
                                                                    id:'btn_quality',
																	text: 'Quality',
																	handler: function() {
																		//Ext.getCmp('forminputtestmachine').removeAll();    
																		xK.input_report_test.onQuality();
																	}
																}
																<? }?>
																]
                                    }),
                                            anchor:'100%'
                                    },
                                   	{
                                    	xtype: 'container',
										id:'forminputtestmachine',
										anchor:'100% 60%'
									},
								   
				]
            }); 		
            
  },
  OnsaveGrid : function() {
    
  },
  OnresetForm: function() {
    Ext.ComponentQuery.query('[name=list_formulir_test]')[0].setValue(""); 
    Ext.ComponentQuery.query('[name=list_report_test]')[0].setValue("");
    Ext.ComponentQuery.query('[name=machine_test_input]')[0].setValue("");
   
  },
  onInputTestForm: function() {
        var list_formulir_test = Ext.ComponentQuery.query('[name=list_formulir_test]')[0].getValue();
	  var list_reports = Ext.ComponentQuery.query('[name=list_report_test]')[0].getValue();
	  var name_report = Ext.ComponentQuery.query('[name=list_report_test]')[0].getRawValue();
	  var machine_test_input = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getValue();
	  var machine_name = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getRawValue();
	  
	 
	  
	  
      if (list_formulir_test == "" || list_formulir_test == null) {
          Ext.Msg.alert('Info',"No formulir harap diisi.",function() {
                });  
          return;
      }
	  
	  if (list_reports == "" || list_reports == null) {
          Ext.Msg.alert('Info',"List Machine Report harap diisi.",function() {
                });  
          return;
      }
	  
	  /*
	  if (machine_test_input == "" || machine_test_input == null) {
          Ext.Msg.alert('Info',"Machine Test harap diisi.",function() {
                });  
          return;
      }*/
	  
      var postdata = "idformulir="+list_formulir_test+"&idreport="+list_reports+"&name_report="+name_report+"&idmachine="+machine_test_input+"&namemachine="+machine_name;
      var loadProsess = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
      loadProsess.show();
	  var dynamicPanel = Ext.create('Ext.Component',{
					   loader: {
						  url: 'input_report_test/genarateinputformtes/?'+postdata,
						  //renderer: 'html',
						  autoLoad: true,
						  scripts: true,
						  callback : function(el,s,x,op) {
								 loadProsess.hide();
						  }
						  },					  
						renderTo: 'forminputtestmachine'
					   });	
	  
        
  },
  onPrintFormSheet : function(is_excel) {
          var list_formulir_test = Ext.ComponentQuery.query('[name=list_formulir_test]')[0].getValue();
	  var list_reports = Ext.ComponentQuery.query('[name=list_report_test]')[0].getValue();
	  var name_report = Ext.ComponentQuery.query('[name=list_report_test]')[0].getRawValue();
	  var machine_test_input = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getValue();
	  var machine_name = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getRawValue();
	  
	 
	  
	  
      if (list_formulir_test == "" || list_formulir_test == null) {
          Ext.Msg.alert('Info',"No formulir harap diisi.",function() {
                });  
          return;
      }
	  
	  if (list_reports == "" || list_reports == null) {
          Ext.Msg.alert('Info',"List Machine Report harap diisi.",function() {
                });  
          return;
      }
	  /*
     if (machine_test_input == "" || machine_test_input == null) {
          Ext.Msg.alert('Info',"Machine Test harap diisi.",function() {
                });  
          return;
      }*/
      
      
        var list_formulir_test = Ext.getCmp('list_formulir_test').getValue();
        var idmachine =Ext.getCmp('machine_test_input').getValue();
        var machine_test_input = Ext.getCmp('machine_test_input').getValue();
        var list_report_test = Ext.getCmp('list_report_test').getValue();
        var namemachine = Ext.getCmp('machine_test_input').getRawValue();
        var namereporttest = Ext.getCmp('list_report_test').getRawValue();
		var param_x ="is_excel="+is_excel+"&list_formulir_test="+list_formulir_test+"&idmachine="+idmachine+"&namemachine="+namemachine+"&namereporttest="+namereporttest+"&machine_test_input="+machine_test_input+"&list_report_test="+list_report_test;
		//Ext.Msg.alert('Info',param_x,function() {});  
		winPopPrint('input_report_test/print_result/?'+param_x,'Print Result Machine Test');
  },
  onQuality : function() {
		var list_formulir_test = Ext.ComponentQuery.query('[name=list_formulir_test]')[0].getValue();
	  var list_reports = Ext.ComponentQuery.query('[name=list_report_test]')[0].getValue();
	  var name_report = Ext.ComponentQuery.query('[name=list_report_test]')[0].getRawValue();
	  var machine_test_input = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getValue();
	  var machine_name = Ext.ComponentQuery.query('[name=machine_test_input]')[0].getRawValue();
	  
      if (list_formulir_test == "" || list_formulir_test == null) {
          Ext.Msg.alert('Info',"No formulir harap diisi.",function() {
                });  
          return;
      }
	  
	  if (list_reports == "" || list_reports == null) {
          Ext.Msg.alert('Info',"List Machine Report harap diisi.",function() {
                });  
          return;
      }
	  /*
     if (machine_test_input == "" || machine_test_input == null) {
          Ext.Msg.alert('Info',"Machine Test harap diisi.",function() {
                });  
          return;
      }*/
      
      
        var list_formulir_test = Ext.getCmp('list_formulir_test').getValue();
        var idmachine =Ext.getCmp('machine_test_input').getValue();
        var machine_test_input = Ext.getCmp('machine_test_input').getValue();
        var list_report_test = Ext.getCmp('list_report_test').getValue();
        var namemachine = Ext.getCmp('machine_test_input').getRawValue();
        var namereporttest = Ext.getCmp('list_report_test').getRawValue();
		var param_x ="list_formulir_test="+list_formulir_test+"&idmachine="+idmachine+"&namemachine="+namemachine+"&namereporttest="+namereporttest+"&machine_test_input="+machine_test_input+"&list_report_test="+list_report_test;
		//Ext.Msg.alert('Info',param_x,function() {});  
		winPopPrint('input_report_test/print_result_quality/?'+param_x,'Print Result Machine Test');
  }
  
}

xK.input_report_test.init();

  
</script>