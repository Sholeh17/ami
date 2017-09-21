<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>

var owner = "RD";
<? if($this->session->userdata('owner') == 'RD'){ ?>
	//var category = "";
	//var type_cat = "";
	<? }
	else{ ?>
	//var category = "Raw Material";
	//var type_cat = "OTHER";
	
	owner = "QUALITY";
	<?}?>

	var type_stored = null;
		
		if (owner == 'RD'){
			type_stored = Ext.create('Ext.data.Store', {
				fields: ['id', 'name'],
				data : [
					{"id":"MASTIKASI", "name":"MASTIKASI"},
					{"id":"MB 1", "name":"MB 1"},
					{"id":"MB 2", "name":"MB 2"},	
					{"id":"REM 1", "name":"REM 1"},
					{"id":"REM 2", "name":"REM 2"},
					{"id":"REM 3", "name":"REM 3"},
					{"id":"FM", "name":"FM"},
					{"id":"REM FM", "name":"REM FM"},
					{"id":"RECYCLE", "name":"RECYCLE"},
					{"id":"MC", "name":"MC"},
					{"id":"PCR", "name":"PCR"},
					{"id":"TBR", "name":"TBR"},
					{"id":"SOLID TIRE", "name":"SOLID TIRE"},
					{"id":"WINTER TIRE", "name":"WINTER TIRE"},
					{"id":"OTHER", "name":"OTHER"}
				]
			});
		} 
		else{
			type_stored = Ext.create('Ext.data.Store', {
				fields: ['id', 'name'],
				data : [
					{"id":"OTHER", "name":"OTHER"}
				]
			});
		}
		
var SwitchWin = 0;
xK.form_request_test = {
  dynamicPanel:null,
  gridListFormulir : null,
  griditemtest : null,
  store_master_agent : null,
  criteriaEnum:"Reguler",
  scaleEnum:"Lab Scale",
  winListFormulir:null,
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
  getGridMachine : function() {
     // register model
    Ext.define('m_machinetest', {
        extend: 'Ext.data.Model',
        idProperty: 'idmachine',
        fields: [
		   {name : 'idformuliritem'},
           {name: 'idmachine'},
		    {name:'idformulir'},
           {name: 'name'},
		   {name: 'status_item'}
		  
        ]
    });
    var stored_data = this.getStoredData('m_machinetest','form_request_test/get_data_machine_test','m_machinetest','1000');
    return stored_data;
  },
  getFrawMaterial : function() {
     // register model
    Ext.define('m_frawmaterial', {
        extend: 'Ext.data.Model',
        idProperty: 'idmachine',
        fields: [
		   {name : 'idformulir'},
           {name: 'id_raw_material'},
           {name: 'name'},
		   {name: 'qty'},
		   {name: 'tipe_raw_material'}
		  
        ]
    });
    var stored_data = this.getStoredData('m_frawmaterial','1form_request_test/get_cp_raw_material_test','m_frawmaterial','1000');
	//console.log(stored_data);
    return stored_data;
  },  
 getGridModelFormulir : function() {
     // register model
    Ext.define('m_listformulir', {
        extend: 'Ext.data.Model',
        idProperty: 'm_listformulir',
        fields: [
           {name: 'idformulir'},
           {name: 'no_req'},
		   {name: 'shift_formulir'},
           {name: 'date_request'},
           {name: 'date_line'},
		   {name: 'date_reciept_sample'},
           {name: 'sample'},
           {name: 'sample_category'},
           {name: 'type_request'},
           {name: 'criteria'},
           {name: 'scale'},
           {name: 'request_by'},
		   {name: 'request_by_people'},
           {name: 'porpose'},
           {name: 'sample_spec'},
           {name: 'notes'},
           {name: 'scale'},
           {name: 'status'},
           {name: 'user_create'},
		   {name: 'rir'},
		   
		   {name: 'date_approved'},
		   {name: 'queue_number'}
        ]
    });
    var stored_data = this.getStoredData('m_listformulir','form_request_test/get_list_formulir','m_listformulir','20');
    return stored_data;
  }, 
  getGridListFormulir : function() {
        var storeMdl = this.getGridModelFormulir();
        storeMdl.load();
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
       
        var searchField = Ext.create('Ext.ux.form.SearchField',{
                                        store: storeMdl,width:440
						 });
						 
						 
        Ext.ux.ajax.SimManager.init({
        delay: 300,
        defaultSimlet: null
		}).register({
			'UrlStatus': {
				data: [
					['Draft', 'Draft'],
					['Posting', 'Posting'],
					['Approve', 'Approve'],
					['Closed', 'Closed']
				],
				stype: 'json'
			}
		});

        var listStatusGrid = Ext.create('Ext.data.Store', {
                fields: ['id', 'text'],
                proxy: {
                        type: 'ajax',
                        url: 'UrlStatus',
                        reader: 'array'
                }
        });			

		//var filterRow2 = new Ext.ux.grid.filter.Filter();	
					var filterfeature = {
				ftype: 'filters',
				encode: encode,
				local: local,
				filters: [{
					type: 'boolean',
					dataIndex: 'visible'
				}]
			};
			var searchField = Ext.create('Ext.ux.form.SearchField',{
    	store: storeMdl,
		width:440});
        this.gridListFormulir = Ext.widget('gridpanel', {
tbar:[searchField],
			features: [filterfeature],
            store: storeMdl,            
            loadMask: true,
            id:'gridListFormulir',
			margins:'2 2 2 2',
            anchor:'100% 40%',
			//plugins		: filterRow,
            //selModel: sm, 
            tbar:[
					searchField,'->', 
					{
						text:'QUICK CHECK (Judgement)', 
						iconCls	:'btn-print',
						handler:function() {
							if (xK.form_request_test.gridListFormulir.getSelectionModel().hasSelection()) {
						    	var row = xK.form_request_test.gridListFormulir.getSelectionModel().getSelection()[0];	
							} else {
								 Ext.Msg.alert('Info',"Select row data!!!");
								 return;
							}
							
							var param_x = "idformulir="+row.data.idformulir;
							winPopPrint('form_request_test/print_judgement_quick_check?'+param_x,'QUICK CHECK (Judgement)');	
						}
					}, '-', 
					{
						text:'COMPLITED (Judgement)', 
						iconCls	:'btn-print',
						handler:function() {
							if (xK.form_request_test.gridListFormulir.getSelectionModel().hasSelection()) {
						    	var row = xK.form_request_test.gridListFormulir.getSelectionModel().getSelection()[0];	
							} else {
								 Ext.Msg.alert('Info',"Select row data!!!");
								 return;
							}
							
							var param_x = "idformulir="+row.data.idformulir;
							winPopPrint('form_request_test/print_judgement_complited?'+param_x,'COMPLITED (Judgement)');
						}
					}
					
					, '-', 
					{
						text:'QUALITY (Judgement)', 
						iconCls	:'btn-print',
						handler:function() {
							if (xK.form_request_test.gridListFormulir.getSelectionModel().hasSelection()) {
						    	var row = xK.form_request_test.gridListFormulir.getSelectionModel().getSelection()[0];	
							} else {
								 Ext.Msg.alert('Info',"Select row data!!!");
								 return;
							}
							
							var param_x = "idformulir="+row.data.idformulir;
							winPopPrint('form_request_test/print_judgement_quality?'+param_x,'QUALITY (Judgement)');
						}
					}
			],
            features	: [filterfeature],  
            split		: true,   
			//cm			: grid_colomn_trx,
            columns: [
					 {
                        text: 'No Req',
                        sortable: true,                    
                        dataIndex: 'no_req',
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
					},
					 {
                        text: 'No.RIR/Ref',
                        sortable: true,                    
                        dataIndex: 'rir',
						hidden: (owner == "RD" ? true : false),
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },
					 {
                        text: 'Shift',
                        sortable: true,                    
                        dataIndex: 'shift_formulir',
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Date Request',
                        sortable: true,                    
                        dataIndex: 'date_request',
                        width: 100,
						filter: {
							type: 'date',
							dateFormat: 'Y-m-d',
							disabled: false,
						}
                     },{
                        text: 'Date Line',
                        sortable: true,                    
                        dataIndex: 'date_line',
                        width: 100,
						filter: {
							type: 'date',
							dateFormat: 'Y-m-d',
							disabled: false,
						}
                     },{
                        text: 'Tgl Terima Sample',
                        sortable: true,                    
                        dataIndex: 'date_reciept_sample',
                        width: 100,
						
						filter: {
							type: 'date',
							dateFormat: 'Y-m-d',
							disabled: false,
						}
                     },
					 
					 {
                        text: 'Date Approved',
                        sortable: true,                    
                        dataIndex: 'date_approved',
                        width: 100,
						filter: {
							type: 'date',
							dateFormat: 'Y-m-d',
							disabled: false,
						}
                     },
					 
					 {
                        text: 'No Antrian',
                        sortable: true,                    
                        dataIndex: 'queue_number',
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },
					 
					 {
                        text: 'Status',
                        sortable: true,                    
                        dataIndex: 'status',
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Sample Category',
                        sortable: true,                    
                        dataIndex: 'sample_category',
                        width: 170,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Sample',
                        sortable: true,                    
                        dataIndex: 'sample',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Type',
                        sortable: true,                    
                        dataIndex: 'type_request',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Requester',
                        sortable: true,                    
                        dataIndex: 'request_by',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Request By',
                        sortable: true,                    
                        dataIndex: 'request_by_people',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Purpose',
                        sortable: true,                    
                        dataIndex: 'porpose',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     },{
                        text: 'Sampel Specification',
                        sortable: true,                    
                        dataIndex: 'sample_spec',
                        flex:1,
                        //filterable: true,
                        width: 100,
						filter: {
							type: 'string',
							disabled: false,
						}
                     }
                    ],
            stripeRows: true,
            listeners: {
                itemdblclick: function(v, record, html_item, index){
                   var r = record;
				   
				   if(r.data.status == "DRAFT"){
					   Ext.ComponentQuery.query('[name=queue_number]')[0].setVisible(true);
					   Ext.ComponentQuery.query('[name=date_reciept_sample]')[0].setVisible(false);
				   }
				   else if(r.data.status == "CHECKED"){
					   Ext.ComponentQuery.query('[name=queue_number]')[0].setVisible(false);
					   Ext.ComponentQuery.query('[name=date_reciept_sample]')[0].setVisible(true);
				   }
				   
				   if(SwitchWin == 1){
					   //copy existing
                    Ext.Ajax.request({
                        url: 'form_request_test/get_formulir_item_compound_raw_material?id_formulir='+r.data.idformulir,    // where you wanna post                       
                        success: function(response){								
                                var jsonData = Ext.decode(response.responseText);
								console.log(jsonData);
                                var record = jsonData.data;
								if (record.length > 0) {
									xK.form_request_test.gridFrawamaterial.store.loadData(record,false);	
								} else {
									alert('Data tidak ditemukan, tidak sesuai dengan group section!!');	
									return;	
								}
								xK.form_request_test.winListFormulir.hide();

                        }						
                    });					   
					return;
				   }
				   //load gridFrawamaterial
					 if (r.data.sample_category == 'Compound') {
						 Ext.getCmp('tab_compund').setDisabled(false);
						Ext.Ajax.request({
							url: 'form_request_test/get_formulir_item_compound_raw_material?id_formulir='+r.data.idformulir,    // where you wanna post                       
							success: function(response){								
									var jsonData = Ext.decode(response.responseText);
									var record = jsonData.data;
									xK.form_request_test.gridFrawamaterial.store.loadData(record,false);						
									xK.form_request_test.winListFormulir.hide();

							}						
						});							 
					 } else {
						 Ext.getCmp('tab_compund').setDisabled(true);
					 }
				   
		    		Ext.getCmp('no_req').setValue(r.data.no_req); 
                    Ext.getCmp('idformulir').setValue(r.data.idformulir);
					
					Ext.getCmp('queue_number').setValue(r.data.queue_number);
					
                    Ext.getCmp('daterequest').setValue(r.data.date_request);
                    Ext.getCmp('dateline').setValue(r.data.date_line);
					Ext.getCmp('date_reciept_sample').setValue(r.data.date_reciept_sample);
					
					
					Ext.getCmp('shift_formulir').setValue(r.data.shift_formulir); 
					
                    Ext.getCmp('sample').setValue(r.data.sample);
                    Ext.getCmp('sample_category').setValue(r.data.sample_category);
                    Ext.getCmp('type_request').setValue(r.data.type_request);
					
									
                    Ext.getCmp('req').setValue(r.data.request_by);
					Ext.getCmp('request_by_people').setValue(r.data.request_by_people);
					
					
                    Ext.getCmp('pop').setValue(r.data.porpose);
					
					
                    Ext.getCmp('ssp').setValue(r.data.sample_spec);
                    Ext.getCmp('notes').setValue(r.data.notes);
					
					Ext.getCmp('rir').setValue(r.data.porpose);
					
					//alert(Ext.ComponentQuery.query('[name=scale]')[0].getValue());
                    
					Ext.ComponentQuery.query('[name=criteria]')[0].setValue(r.data.criteria);
                    Ext.ComponentQuery.query('[name=scale]')[0].setValue(r.data.scale);
                   
				   Ext.getCmp('ButtonControl').setDisabled(false);
                    Ext.getCmp('ButtonCheck').setDisabled(false);
                    Ext.getCmp('ButtonApprove').setDisabled(false);
					Ext.getCmp('ButtonBackDraft').setDisabled(false);
					
					Ext.getCmp('ButtonPrint').setDisabled(false);
					Ext.getCmp('sip').setDisabled(false);
					
                    
                    //xK.form_request_test.griditemtest.store.load();
                    Ext.Ajax.request({
                        url: 'form_request_test/get_list_formulir_items?id_formulir='+r.data.idformulir,    // where you wanna post
                       // params:postdata,
                        success: function(response){								
                                var jsonData = Ext.decode(response.responseText);
                                var record = jsonData.data;
								xK.form_request_test.griditemtest.store.loadData(record,false);
                                /*Ext.each(record, function(record) {
                                        console.log(record);
                                        var grid = xK.form_request_test.griditemtest;
                                        var rec = grid.store.getById(record.idmachine); // json.data[i].getId()
                                        if (rec.data.idmachine == record.idmachine){ // select all records have have 's'
                                            grid.getSelectionModel().select(rec,true,false);
                                        }
                                });*/
                               xK.form_request_test.winListFormulir.hide();
							   Ext.getCmp('cencel_item_test').setDisabled(false);

                        }						
                    });
                    
                    
                    
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



        
        this.winListFormulir = Ext.create('Ext.Window', {
            title: 'List Formulir Test',
            width: 1024,
            height: 500,            
            tools: [{
                    type: 'minimize',				
                    handler: function(event, target, owner, tool){
                            owner.up('window').toggleMaximize();
                    }
            },{
                    type: 'maximize',
                    handler: function(event, target, owner, tool){
                            owner.up('window').toggleMaximize();

                    }
            }],
            constrainHeader: true,
            layout: 'fit',
            modal		: true,
            closeAction	: 'hide',
            items: [this.gridListFormulir]
        });
        //Ext.getCmp(this.id_tab_main).add(this.winListFormulir);

            
		
  },
  
  getModelDataItemTestCombo : function() {
     // register model
    Ext.define('itemtestcombo', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'idreport'},
           {name: 'idmachine'},		   
		   {name: 'name_report'}
        ]
    });

    var stored_data = this.getStoredData('itemtestcombo','form_request_test/get_data_machine_report_test','itemtestcombo','20');
    return stored_data;
  },
  windowFormitemTest:null,
  getWindowItem: function() {
	 
	 	 var form = Ext.widget('form', {
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                border: false,
                bodyPadding: 2,
				margin:'5 5 5 5',
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100,
                    labelStyle: 'font-weight:bold'
                },
                items: [
					{
						xtype : 'combo',					
						name:'idreport',
						fieldLabel:'Item Test',
						displayField : 'name_report',					
						valueField	 : 'idreport',
						tpl: '<tpl for="."><div class="x-boundlist-item">{name_report}</div></tpl>',	
						minChars		: 1,
						queryDelay		: 1,
						pageSize		: 10,
						allowBlank: false,
						editable	 : true,							
						store		 : this.getModelDataItemTestCombo(),
						triggerAction: 'all',
						listeners : {
							select : function(o,row,r){
									
							}
						}
					}
				],
				buttons: [{
                    text: 'Add',
                    handler: function() {
                        if (this.up('form').getForm().isValid()) {
						   var id_report = this.up('form').getForm().findField('idreport').getValue();
                           var countRow = xK.form_request_test.griditemtest.store.getCount();
						   if((owner == "QUALITY") && (countRow > 0)){
								alert("Maximum item test is 1 item!");
								return;
							}
                           var r = Ext.create('m_machinetest', {	
                                    name : this.up('form').getForm().findField('idreport').getRawValue(),
									idmachine : id_report
                            });
							var i_cek = 0;
							var griditemtest = xK.form_request_test.griditemtest.getStore();		
								griditemtest.each(function(rec) {
									if (id_report == rec.get('idmachine'))
										i_cek++;
								});
							if (i_cek == 0)	
                            	xK.form_request_test.griditemtest.store.insert(countRow, r);	
                        }
                    }
                }]
			}); 
	  		
	  	this.windowFormitemTest = Ext.create('Ext.Window', {
            title: 'Add Item Test',
            width: 450,
            height: 150, 
            constrainHeader: true,
            layout: 'fit',
            closeAction	: 'hide',
			items:form
        });
		Ext.getCmp('<?=$_REQUEST['id_tab'];?>').add(this.windowFormitemTest);
  },
  getGridListMachine : function() {  
  		this.getWindowItem();
        var storeMdl = this.getGridMachine();
        storeMdl.load();
        var sm = Ext.create('Ext.selection.CheckboxModel');
        this.griditemtest = Ext.widget('gridpanel', {
            store: storeMdl,            
            loadMask: true,
            //id:'grid_machine',
            title:'LIST ITEM TEST',
			height:320,
			tbar:[{text:'Add Item Test', iconCls: 'btn-add',handler:function() {
						//Check IF Compound Sample Category not data Hold
						var contRawMaterialList = xK.form_request_test.gridFrawamaterial.store.getCount();
						var sample_catetory = Ext.ComponentQuery.query('[name=sample_category]')[0].getValue();
						if (sample_catetory == "Compound") {
							if (contRawMaterialList <= 0) {
								alert("Insert Raw Material Compound List!!");
								return;
							}
						}
						
						//alert(owner);
						//alert(contRawMaterialList);
						
						var countRowCheck = xK.form_request_test.griditemtest.store.getCount();
						   if((owner == "QUALITY") && (countRowCheck > 0)){
								alert("Maximum item test is 1 item!");
								return;
							}
				
				
					xK.form_request_test.windowFormitemTest.show();
				}}, {text:'Remove Item',iconCls: 'btn-delete',itemId: 'removeListItem', handler:function() {
					
						
						var countRow = xK.form_request_test.griditemtest.store.getCount();
						var sm = xK.form_request_test.griditemtest.getSelectionModel();
						var sel =sm.getSelection();
						if (sel.length > 0) {
							xK.form_request_test.griditemtest.store.remove(sel);
							return;
						}
						alert("Select Row Item");
					}
				},
				'->',
				{
					text:'Cencel Test',
					disabled:true,
					id:'cencel_item_test',
					iconCls:'icon_rejectdata',
					handler:function() {
						var countRow = xK.form_request_test.griditemtest.store.getCount();
						var sm = xK.form_request_test.griditemtest.getSelectionModel();
						var sel =sm.getSelection();
						if (sel.length > 0) {
							var idformuliritem = sm.getSelection()[0].get('idformuliritem');
							var idformulir = sm.getSelection()[0].get('idformulir');
							var idmachine = sm.getSelection()[0].get('idmachine');
							var postdata = "idformuliritem="+idformuliritem+"&idformulir="+idformulir+"&idmachine="+idmachine;
							Ext.Ajax.request({
								url: 'form_request_test/cencel_item_test/',    // where you wanna post
								params:postdata,
								method: "GET",
								success: function(response){								
										var jsonData = Ext.decode(response.responseText);
										if (!jsonData.success) {
											Ext.Msg.alert('Info',jsonData.msg);
											return;
										}
										Ext.Msg.alert('Info',jsonData.msg,function() {
											//xK.form_request_test.griditemtest.store.load();
											
											
												Ext.Ajax.request({
													url: 'form_request_test/get_list_formulir_items?id_formulir='+idformulir,    // where you wanna post
												   // params:postdata,
												   method: "GET",
													success: function(response){								
															var jsonData = Ext.decode(response.responseText);
															var record = jsonData.data;
															xK.form_request_test.griditemtest.store.loadData(record,false);
														   Ext.getCmp('cencel_item_test').setDisabled(false);
							
													}						
												});
											
										});	
						
								}						
							});
							
							return;
						}
						alert("Select Row Item");
					}
				}
			],
			margin: '0 0 4 0',
            //selModel: sm,            
			bbar:[{text:'Show Item Progress', handler:this.showItemProgress, id:'sip', disabled:true,iconCls: 'application_view_detail'}],
            columns: [{
                        text: 'Item Test',
                        sortable: true,                    
                        dataIndex: 'name',
                        flex:1
                     },
					 {
                        text: 'Status',
                        sortable: true,                    
                        dataIndex: 'status_item',
                        width: 100
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
  
  windowFormRawMaterial:null,
  getWindowItemRowMaterial: function() {
	 
	 	 var form = Ext.widget('form', {
                layout: {
                    type: 'vbox',
                    align: 'fit'
                },
                border: false,
                bodyPadding: 2,
				margin:'5 5 5 5',
                fieldDefaults: {
                    labelAlign: 'top',
                    width: 350,
                    labelStyle: 'font-weight:bold'
                },
                items: [
					{
						xtype : 'combo',					
						name:'f_rawmaterial',
						fieldLabel:'Item Raw Material',
						displayField : 'idmaterial',					
						valueField	 : 'idmaterial',
						tpl: '<tpl for="."><div class="x-boundlist-item">{idmaterial}</div></tpl>',	
						minChars		: 1,
						queryDelay		: 1,
						pageSize		: 10,
						allowBlank: false,
						editable	 : true,							
						store		 : this.getModelDataItemCompund(),
						triggerAction: 'all',
						listeners : {
							render : function() {
								
							},
							select : function(o,row,r){
									
							}
						}
					},{
						xtype : 'textfield',					
						name:'qty_rawmaterial',
						fieldLabel:'Qty',					
						allowBlank: false
					},
					{
						xtype : 'combo',					
						name:'type_rawmaterial',
						id:'type_rawmaterial',
						displayField 	: 'name',
						fieldLabel		: 'Type',				
						valueField	 	: 'id',
						displayField 	: 'name',
						minChars		: 1,
						queryDelay		: 1,
						pageSize		: 10,
						allowBlank: false,
						editable	 : true,							
						store		 : type_stored,
						triggerAction: 'all',
						listeners : {
							render : function() {
								
							},
							select : function(o,row,r){
									
							}
						}
					}
				],
				buttons: [{
                    text: 'Add',
                    handler: function() {
                        if (this.up('form').getForm().isValid()) {
						   var countRow = xK.form_request_test.griditemtest.store.getCount();
						   var name_rw  = this.up('form').getForm().findField('f_rawmaterial').getRawValue();
						   var id_raw  = this.up('form').getForm().findField('f_rawmaterial').getValue();
						   var qty_rawmaterial  = this.up('form').getForm().findField('qty_rawmaterial').getValue();
						   var type_rawmaterial  = this.up('form').getForm().findField('type_rawmaterial').getValue();
                           var r = Ext.create('m_frawmaterial', {
                                    name : this.up('form').getForm().findField('f_rawmaterial').getRawValue(),
									id_raw_material : id_raw,
									qty : qty_rawmaterial,
									tipe_raw_material: type_rawmaterial
                            });
							console.log(r);console.log(countRow);
						   	var i_cek = 0;
							var gridFrawamaterial = xK.form_request_test.gridFrawamaterial.getStore();	
							console.log(gridFrawamaterial);
							gridFrawamaterial.each(function(rec) {
								if ((name_rw == rec.get('name')) && (type_rawmaterial == rec.get('tipe_raw_material')))
									i_cek++;
							});
								
							if (i_cek == 0)	
								xK.form_request_test.gridFrawamaterial.store.insert(countRow, r);
						   
                        }
                    }
                }]
			}); 
	  		
	  	this.windowFormRawMaterial = Ext.create('Ext.Window', {
            title: 'Add Raw Material Compound',
            width: 450,
            height: 230, 
            constrainHeader: true,
            layout: 'fit',
            closeAction	: 'hide',
			items:form
        });
		Ext.getCmp('<?=$_REQUEST['id_tab'];?>').add(this.windowFormRawMaterial);
  },
  
  gridFrawamaterial:null,
  getGridFrawmaterial: function() {
	  this.getWindowItemRowMaterial();
	  var storeFRM = this.getFrawMaterial();
	  var sm = Ext.create('Ext.selection.CheckboxModel');
        this.gridFrawamaterial = Ext.widget('gridpanel', {
            store: storeFRM,            
            loadMask: true,
			id:'tab_compund',
			disabled:true,
            title:'Formula Raw Material Compound',
			height:300,
			tbar:[
				{
					text:'Add Item', iconCls: 'btn-add',handler:function() {
						xK.form_request_test.windowFormRawMaterial.show();
					
						Ext.ComponentQuery.query('[name=f_rawmaterial]')[0].store.getProxy().extraParams = {
							search_filter:1,
							category:'Raw Material'
						 };
						Ext.ComponentQuery.query('[name=f_rawmaterial]')[0].store.load();
					
					}
				}, 
				{
					text:'Remove Item',iconCls: 'btn-delete',itemId: 'removeListItem', handler:function() {
						var countRow = xK.form_request_test.gridFrawamaterial.store.getCount();
						var sm = xK.form_request_test.gridFrawamaterial.getSelectionModel();
						var sel =sm.getSelection();
						if (sel.length > 0) {
							xK.form_request_test.gridFrawamaterial.store.remove(sel);
							return;
						}
						alert("Select Row Item");
					}
				 },
				 '->',
				 {
					text:'Copy Existing', handler:function() {
						//alert("Select Row Item");
							SwitchWin = 1;
							xK.form_request_test.winListFormulir.show();
							xK.form_request_test.gridListFormulir.store.load();						
					}	 
				 }
			],
			margin: '5 5 5 5',
            //selModel: sm,           
            columns: [{
                        text: 'Raw material',
                        sortable: true,                    
                        dataIndex: 'id_raw_material',
                        flex:1,
                        //filterable: true,
                        width: 100
                     },{
                        text: 'Qty',
                        sortable: true,                    
                        dataIndex: 'qty',
                        flex:1,
                        //filterable: true,
                        width: 100
                     },
					 {
                        text: 'Tipe',
                        sortable: true,                    
                        dataIndex: 'tipe_raw_material',
                        flex:1,
                        //filterable: true,
                        width: 100
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
  
  doLayoutPanel : function(){
        var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	this.getGridListMachine();    
        this.getGridListFormulir();
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
		
		var requester_stored = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"CD 1", "name":"CD 1"},
				{"id":"CD 2", "name":"CD 2"},
				{"id":"CD 3", "name":"CD 3"},	
				{"id":"PE", "name":"PE"},
				{"id":"SECTION LAB", "name":"SECTION LAB"},
				{"id":"OTHER", "name":"OTHER"}
			]
		});
		
		
		var group_shiftX = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"Non Shift", "name":"Non Shift"},
				{"id":"Shift 1", "name":"Shift 1"},
				{"id":"Shift 2", "name":"Shift 2"},
				{"id":"Shift 3", "name":"Shift 3"}
				
			]
		});  
		
		
	//Grid Formula Raw Material
	this.getGridFrawmaterial();	
		
		
	this.dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				//,layout: 'anchor'
				,margin:'2 2 2 2'
				,border:false
				,items:[
                        {
                            items : Ext.widget('form', {
                                        border: false,
                                        bodyPadding: 2,
										margins: '2 2 2 2',
										autoScroll:true,
                                        fieldDefaults: {
											labelAlign: 'left',
                                            labelWidth: 150,
											//width:650,
											margin: '5 5 5 5'
                                        },
                                        items: [
																{
                                                                    name:"idformulir",
																	id:"idformulir",
                                                                    xtype:"hidden"
                                                                },
																
																{
																	region: 'center',
																	margin: '2 2 2 2',
																	layout: 'column',
																	border:false,
																	defaultType: 'container',
																	layout: 'hbox',
																	items: [
																		{
																			//columnWidth: 1/2,
																			//spadding: '5 0 5 5',
																			width: 500,
																			margin: '2 2 2 2',
																			items:[
																					{
																						name: 'no_req',
																						xtype: 'textfield',
																						itemId: 'no_req',
																						id: 'no_req',
																						emptyText:'Auto Code',
																						width:450,
																						readOnly:true,
																						fieldLabel: 'No Req',
																						allowBlank: true
																					 },
																					 
																					 {
																						name: 'queue_number',
																						xtype: 'numberfield',
																						itemId: 'queue_number',
																						id: 'queue_number',
																						width:450,
																						fieldLabel: 'No Antrian',
																						allowBlank: false,
																						value: 0,
																						minValue: 0,
																						maxValue: 100,
																						hidden: true,
																					 },
																					 
																					 
																					  {
																							xtype: 'combo',
																							typeAhead: true,
																							displayField : 'name',
																							fieldLabel: 'Shift',
																							name:'shift_formulir',
																							id: 'shift_formulir',
																							valueField : 'id',
																							listeners: {
																								select: function(o) {
																									
																								}
																							},
																							triggerAction: 'all',
																							editable:false,
																							store:group_shiftX,
																							allowBlank: false
																						},
																					 {
																						xtype: 'datefield',
																						fieldLabel: 'Date Request',
																						width:450,
																						format:'Y-m-d',
																						value:'<?=date("Y-m-d");?>',
																						name:"daterequest",
																						id:"daterequest",
																						itemId:"daterequest"
																					},{
																						xtype: 'datefield',
																						fieldLabel: 'Dateline',
																						width:450,
																						name:"dateline",
																						id:"dateline",
																						format:'Y-m-d',
																						value:'<?=date("Y-m-d");?>',
																						itemId:"dateline"
																					},{
																						xtype: 'datefield',
																						fieldLabel: 'Tgl Terima Sample',
																						width:450,
																						name:"date_reciept_sample",
																						id:"date_reciept_sample",
																						format:'Y-m-d',
																						//value:'<?=date("Y-m-d");?>',
																						itemId:"date_reciept_sample",
																						hidden: true,
																					},
																					
																					{
																						xtype: 'textfield',
																						fieldLabel: 'No.RIR/Ref',
																						width:450,
																						name:"rir",
																						id:"rir",
																						allowBlank: (owner == "RD" ? true : false),
																						hidden: (owner == "RD" ? true : false),
																						/*if (owner == 'RD'){
																							allowBlank: true,
																						}
																						else{ 
																							allowBlank: false,
																						}*/
																						//format:'Y-m-d',
																						//value:'<?=date("Y-m-d");?>',
																						itemId:"rir_id"
																					},
																					
																					
																					{
																							xtype: 'combo',
																							typeAhead: true,
																							displayField : 'name',
																							fieldLabel: 'Sample Category',
																							name:'sample_category',
																							id:'sample_category',
																							itemId: 'sample_category',
																							//value:'SEMUA',
																							//value:category,
																							valueField : 'id',
																							width:450,	
																							editable	: false,
																							triggerAction: 'all',
																							store:sample_category,
																							allowBlank: false,
																							listeners : {
																									render : function(cmb) {
																										
																									},
																									change : function(o,row,r){
																										//alert('change');
																										 Ext.ComponentQuery.query('[name=sample]')[0].store.getProxy().extraParams = {
																												search_filter:1,
																												category:o.getValue()
																										 };
																										 Ext.ComponentQuery.query('[name=sample]')[0].store.load();
																										 
																										 
																										 //Check Role Add Raw Material
																										 <?php if ($role->add_rm_cp == "false") { ?>
																												return;
																										 <?php } ?>
																										 
																										 if (o.getValue() == 'Compound') {
																											 Ext.getCmp('tab_compund').setDisabled(false);
																										 } else {
																											 Ext.getCmp('tab_compund').setDisabled(true);
																											 xK.form_request_test.gridFrawamaterial.getStore().removeAll();
																										 }
																										 
																									},
																									afterRender: function(combo) {
																										//alert('afterRender');
																										if (owner == 'RD'){}else{
																											combo.select(combo.getStore().getAt(1));
																										}
																										
																									}
																							}
																						},{
																						xtype : 'combo',					
																						name:'sample',
																						id:'sample',
																						itemId: 'sample',
																						fieldLabel: 'Sample',
																						displayField : 'idmaterial',					
																						valueField	 : 'idmaterial',
																						tpl: '<tpl for="."><div class="x-boundlist-item">{idmaterial}</div></tpl>',	
																						minChars		: 1,
																						queryDelay		: 1,
																						pageSize		: 10,
																						width:450,
																						editable	 : true,			
																						store		 : this.getModelDataItemCompund(),
																						triggerAction: 'all',
																						listeners : {
																										select : function(o,row,r){
																										 Ext.ComponentQuery.query('[name=f_rawmaterial]')[0].store.getProxy().extraParams = {
																												search_filter:1,
																												//category:'Raw Material'
																										 };
																										 Ext.ComponentQuery.query('[name=f_rawmaterial]')[0].store.load();
																										}
																						}					
																					},{
																							xtype: 'combo',
																							typeAhead: true,
																							displayField : 'name',
																							fieldLabel: 'Type',
																							name:'type_request',
																							id:'type_request',
																							itemId: 'type_request',					
																							width: 450,	
																							//editable: owner == 'QUALITY' ? false : true,
																							//value:'SEMUA',
																							valueField : 'id',	
																							editable	: false,
																							triggerAction: 'all',
																							store:type_stored,
																							allowBlank: false,
																							listeners:{
																								
																											afterRender: function(combo) {
																											//alert('afterRender');
																											if (owner == 'RD'){}else{
																												combo.select(combo.getStore().getAt(0));
																												combo.setReadOnly(true);
																											}
																											
																										}
																							}
																					},
																					{
																						xtype: 'textfield',
																						fieldLabel: 'Requester*',
																						name:'req',
																						id:'req',
																						allowBlank: false,
																						value:'<?=$this->session->userdata('user_section');?>',
																						readOnly:true
																					}
																					/*{
																							xtype: 'combo',
																							typeAhead: true,
																							displayField : 'name',
																							fieldLabel: 'Requester',
																							name:'req',
																							id:'req',
																							itemId: 'req',					
																							width: 450,	
																							//value:'SEMUA',
																							valueField : 'id',	
																							editable	: false,
																							triggerAction: 'all',
																							store:requester_stored,
																							allowBlank: false
																					}*/
																					,{
																						xtype: 'textfield',
																						fieldLabel: 'Request By',
																						value:'<?=$this->session->userdata('user_id');?>',
																						width:450,
																						readOnly:true,		
																						name:"request_by_people",
																						id:"request_by_people",
																						itemId:"request_by_people",
																						allowBlank: false
																					}
																			
																			]
																		},
																		{
																			//columnWidth: 650,
																			//spadding: '5 0 5 5',
																			margin: '2 2 2 2',
																			items:[
																					{
																						xtype: 'textarea',
																						fieldLabel: 'Purpose Project',
																						width:550,
																						height:50,
																						margins: '0 0 5 5',
																						name:"pop",
																						id:"pop",
																						allowBlank: false
																					},
																					{
																						xtype: 'textarea',
																						fieldLabel: 'Sample Specification',
																						width:550,
																						height:50,
																						margins: '0 0 0 5',
																						name:"ssp",
																						id:"ssp",
																						allowBlank: false
																					},
																					{
																						xtype: 'textarea',
																						fieldLabel: 'Note',
																						width:550,
																						 height:50,
																						margins: '0 0 0 5',
																						name:"notes",
																						id:"notes"
																					},
																					
																					{
																						xtype: 'fieldcontainer',
																						//fieldLabel: 'Your Name',
																						labelStyle: 'font-weight:bold;padding:0;',
																						layout: 'hbox',
																						width:800,
																						height:100,
																						fieldDefaults: {
																							labelAlign: 'top'
																						},
						
																						items: [     
																								{
																								xtype:'fieldset',
																								border:false,
																								margins: '2 2 2 -10',
																								width:300,
																								items :[
																									{
																									xtype      : 'radiogroup',
																									labelStyle: 'font-weight:bold',
																									fieldLabel : 'Criteria',
																									listeners: {
																									change : function(obj, value){ 
																										xK.form_request_test.criteriaEnum= value.criteria;
																									   }
																									},
																									items      : [
																											{
																												
																											   
																												name: 'criteria',
																												checked:true,
																												 hideEmptyLabel: false,
																												inputValue     : 'Reguler',
																												boxLabel: 'Low'
																											},
																											{
																												
																												name: 'criteria',
																											   
																												fieldLabel: '',
																												labelSeparator: '',
																												hideEmptyLabel: false,
																												inputValue     : 'Urgent',
																												boxLabel: 'Medium'
																											},{
																											   
																												name: 'criteria',
																												fieldLabel: '',
																												labelSeparator: '',
																												hideEmptyLabel: false,
																												inputValue     : 'Top Urgent',
																												boxLabel: 'High'
																											} 
																									]}
																								]
																								},
																								
																								{
																								xtype:'fieldset',
																								border:false,
																								margins: '1 1 1 1',
																								//width:700,
																								//height:150,
																								items :[
																									{
																									xtype      : 'radiogroup',
																									fieldLabel : 'Scale',
																									labelStyle: 'font-weight:bold',
																									margins: '1 1 1 1',
																									listeners: {
																									change : function(obj, value){ 
																										xK.form_request_test.scaleEnum= value.scale;
																										
																									   }
																									},
																									items      : [
																												{
																													
																													name: 'scale',
																													hideEmptyLabel: false,
																													checked:true,
																													inputValue: 'Lab Scale',
																													boxLabel: 'Lab Scale'
																												},
																												{
																													
																													name: 'scale',
																													fieldLabel: '',
																													labelSeparator: '',
																													hideEmptyLabel: false,
																													inputValue: 'Trial Produksi',
																													boxLabel: 'Trial Prod'
																												},{
																													
																													name: 'scale',
																													fieldLabel: '',
																													labelSeparator: '',
																													hideEmptyLabel: false,
																													inputValue: 'Mas Prod',
																													boxLabel: 'Mas Prod'
																												} ,{
																													
																													name: 'scale',
																													fieldLabel: '',
																													labelSeparator: '',
																													hideEmptyLabel: false,
																													inputValue: 'Regular',
																													boxLabel: 'Regular'
																												} 
																									]}
																								]
																								}
																						]}
																			]
																		}
																	
																	]
																},
															
															
                                                                
                                                                
                                                            
                                                                ]
                            }),
                            anchor:'100%'
                        },
									
									
									{
										xtype : 'tabpanel',
										activeTab : 0,	
										//tabPosition: 'left',
										itemId: 'tabPanelFormReq',
										margins: '2 2 2 2',
										resizeTabs: true,
										enableTabScroll: true,
										border:0,
										defaults: {
											autoScroll: true,
											bodyPadding: 5
										},
										region: 'center',
										items:[
											this.griditemtest,
											this.gridFrawamaterial
										]
									},
                                    {
                                    xtype: 'container',
									border:false,
									margins: '2 2 2 2',
                                    layout: {
                                        type: 'hbox',
                                        pack: 'center'
                                    },
                                    items: [
                                            {
                                                xtype: 'button',
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                margins: '0 10 0 0',
                                                text: 'New Formulir',
                                                hidden:<?php echo (($role->cr == "true") ? "false" : "true"); ?>,
                                                handler: function() {
                                                    xK.form_request_test.OnresetForm();
                                                }
                                            },{
                                                xtype: 'button',
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                margins: '0 0 0 5',
                                                hidden:<?php echo (($role->cr == "true") ? "false" : "true"); ?>,
                                                text: 'Save',
                                                handler: function() {
                                                    xK.form_request_test.OnsaveGrid();
                                                }
                                            },{
                                                xtype: 'button',
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                margins: '0 0 0 5',
                                                hidden:<?php echo (($role->del == "true") ? "false" : "true"); ?>,
                                                text: 'Delete',
                                                handler: function() {
                                                    xK.form_request_test.OnDeleteGrid();
                                                }
                                            },{
                                                xtype: 'button',
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                margins: '0 0 0 5',
                                                text: 'Copy',
                                                hidden:<?php echo (($role->cr == "true") ? "false" : "true"); ?>,
                                                handler: function() {
													var idformulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
													if (idformulir == "" || idformulir == null) {
														Ext.Msg.alert('Info',"Select list formulir untuk di copy!!");
														return;
													}
													
													Ext.Msg.confirm('Konfirmasi', 'Yakin untuk copy formulir ini menjadi formulir baru?', function (id, value) {
														if (id === 'yes') {
															Ext.ComponentQuery.query('[name=idformulir]')[0].setValue('');
                                                    		xK.form_request_test.OnsaveGrid();
														}
													});
													
													
                                                }
                                            },{
                                                xtype: 'button',
                                                margins: '0 0 0 5',
                                                disabled:true,
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                id:'ButtonControl',
                                                hidden:<?php echo (($role->control == "true") ? "false" : "true"); ?>,
                                                text: 'Control',
                                                handler: function() {
                                                    xK.form_request_test.onControlFormulir();
                                                }
                                            },{
                                                xtype: 'button',
                                                margins: '0 0 0 5',
                                                disabled:true,
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                id:'ButtonCheck',
                                                hidden:<?php echo (($role->posting == "true") ? "false" : "true"); ?>,
                                                text: 'Check',
                                                handler: function() {
                                                    xK.form_request_test.onCheckFormulir();
                                                }
                                            },{
                                                xtype: 'button',
                                                margins: '0 0 0 5',
                                                disabled:true,
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                id:'ButtonApprove',
                                                hidden:<?php echo (($role->approve == "true") ? "false" : "true"); ?>,
                                                text: 'Approve',
                                                handler: function() {
                                                    xK.form_request_test.onApporveFormulir();
                                                }
                                            },{
                                                xtype: 'button',
                                                margins: '0 0 0 5',
                                                disabled:true,
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                id:'ButtonBackDraft',
                                                hidden:<?php echo (($role->del == "true") ? "false" : "true"); ?>,
                                                text: 'Back To Draft',
                                                handler: function() {
                                                    xK.form_request_test.onBacktoDraft();
                                                }
                                            },{
                                                xtype: 'button',
                                                margins: '0 0 0 5',
                                                cls: 'contactBtn',
                                                scale: 'large',
												id:'ButtonPrint',
                                                disabled:true,
                                                hidden:<?php echo (($role->print == "true") ? "false" : "true"); ?>,
                                                text: 'Print Formulir',
                                                handler: function() {
														var idformulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
														var param_x ="idformulir="+idformulir;
														winPopPrint('form_request_test/print_formulir/?'+param_x,'Print Formulir Machine Test');
													}
                                            }<?php if ($role->rd == "true") { ?>,{
                                                xtype: 'button',
                                                cls: 'contactBtn',
                                                scale: 'large',
                                                margins: '0 5 0 5',
                                                text: 'List Formulir',
                                                handler: function() {
													SwitchWin = 0;
                                                    xK.form_request_test.winListFormulir.show();
                                                    xK.form_request_test.gridListFormulir.store.load();
													
                                                }
                                            }<?php } ?>]   
                                    ,anchor:'100% 8%'
                                    }
				]
            }); 		
            
  },
  OnsaveGrid : function() {
    var postdata = "";
    var grid = xK.form_request_test.griditemtest;
    var record = grid.getSelectionModel().getSelection();
    
	var j = 0;
	var k = 0;
	
	var griditemtest = xK.form_request_test.griditemtest.getStore();		
	griditemtest.each(function(rec) {
		//console.log(rec);
		postdata += "&data[items]["+j+"][idmachine]="+rec.get('idmachine');
		j++;
	});
	
	var gridFrawamaterial = xK.form_request_test.gridFrawamaterial.getStore();
	gridFrawamaterial.each(function(rec) {
		postdata += "&data[itemsraw]["+k+"][id_raw_material]="+rec.get('id_raw_material'); 
		postdata += "&data[itemsraw]["+k+"][qty]="+rec.get('qty');
		postdata += "&data[itemsraw]["+k+"][tipe_raw_material]="+rec.get('tipe_raw_material');
		k++;
	});	
	
    
    var noreq = Ext.ComponentQuery.query('[name=no_req]')[0].getValue(); 
	var shift_formulir = Ext.getCmp('shift_formulir').getValue(); 
	var queue_number = Ext.getCmp('queue_number').getValue();
	
	if (shift_formulir == "" || shift_formulir == null) {
        Ext.Msg.alert('Info',"Harap Shift diisi", function() {
           
        });
        return;
    } 
	//alert(shift_formulir);
    /*if (noreq == "") {
        Ext.Msg.alert('Info',"No Request harap diisi", function() {
            Ext.ComponentQuery.query('[name=no_req]')[0].focus();
        });
        return;
    } */
    var idformulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
    var daterequest = Ext.ComponentQuery.query('[name=daterequest]')[0].getRawValue();
    var dateline = Ext.ComponentQuery.query('[name=dateline]')[0].getRawValue();
	var date_reciept_sample = Ext.ComponentQuery.query('[name=date_reciept_sample]')[0].getRawValue();
    var sample = Ext.getCmp('sample').getValue();
    var sample_category = Ext.getCmp('sample_category').getValue();
    var type_request = Ext.ComponentQuery.query('[name=type_request]')[0].getValue();
    var request_by_people = Ext.ComponentQuery.query('[name=request_by_people]')[0].getValue();	
   
   
   	if (sample_category == "Compound") {
		var msn_test = xK.form_request_test.griditemtest.store.getCount();
		var rm_item = xK.form_request_test.gridFrawamaterial.store.getCount();
		if (rm_item <= 0 ) {
			Ext.Msg.alert('Info','Item Raw Material harus disi!!');
			return;
		}
		
		if (msn_test < 1){
			Ext.Msg.alert('Info','Item Raw Material harus disi!!');
			return;
		}
		else{
			if(owner == "RD"){
				var isFound = false;
				griditemtest.each(function(rec) {
					if(rec.get('name').indexOf("Rheometer") > -1){
						isFound = true;
					}
				});
				
				if(!isFound){
					Ext.Msg.alert('Info','Sample Compound harus harus ada item Rheometer!!');
					return;
				}
			}
		}
	}
    if (sample_category == null) {
        Ext.Msg.alert('Info',"Sample Category harap diisi", function() {
            Ext.ComponentQuery.query('[name=sample_category]')[0].focus();
        });
        return;
    }
   
    if (sample == null || sample == "") {
        Ext.Msg.alert('Info',"Sample harap diisi", function() {
            Ext.ComponentQuery.query('[name=sample]')[0].focus();
        });
        return;
    }
    
    var req = Ext.ComponentQuery.query('[name=req]')[0].getValue();
    
    if (req == "") {
        Ext.Msg.alert('Info',"Requester harap diisi", function() {
            Ext.ComponentQuery.query('[name=req]')[0].focus();
        });
        return;
    }
    
    var pop = Ext.ComponentQuery.query('[name=pop]')[0].getValue();
    if (pop == "") {
        Ext.Msg.alert('Info',"Purpose Project harap diisi", function() {
            Ext.ComponentQuery.query('[name=pop]')[0].focus();
        });
        return;
    }
    var ssp = Ext.ComponentQuery.query('[name=ssp]')[0].getValue();
    
    if (ssp == "") {
        Ext.Msg.alert('Info',"Sample Specification harap diisi", function() {
            Ext.ComponentQuery.query('[name=ssp]')[0].focus();
        });
        return;
    }
    
    var notes = Ext.ComponentQuery.query('[name=notes]')[0].getValue();
    
    var criteriaEnum = xK.form_request_test.criteriaEnum;
    var scaleEnum = xK.form_request_test.scaleEnum;
    
	var rir = Ext.getCmp('rir').getValue(); 
	
    if (j == 0) {
        Ext.Msg.alert('Info',"Item test harap diisi");
        return;
    }
    
    postdata += "&data[no_req]="+noreq+"&data[date_request]="+daterequest
			+"&data[queue_number]="+queue_number
			 +"&data[shift_formulir]="+shift_formulir
             +"&data[date_line]="+dateline+"&data[idformulir]="+idformulir
			 +"&data[date_reciept_sample]="+date_reciept_sample
             +"&data[sample_category]="+sample_category+"&data[sample]="+sample+"&data[request_by]="+req+"&data[type_request]="+type_request
                +"&data[request_by_people]="+request_by_people
             +"&data[criteria]="+criteriaEnum+"&data[scale]="+scaleEnum
             +"&data[porpose]="+pop+"&data[sample_spec]="+ssp
             +"&data[notes]="+notes+"&data[rir]="+rir;
	//alert(postdata);
	//return;
    var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
    x.show();
	
    Ext.Ajax.request({
        url: 'form_request_test/save_formulir/',    // where you wanna post
		method: 'POST',
        params:postdata,
        success: function(response){								
                var jsonData = Ext.decode(response.responseText);
                x.hide();
				if (!jsonData.success) {
					Ext.Msg.alert('Info',jsonData.msg);
					return;
				}
                Ext.Msg.alert('Info',jsonData.msg,function() {
                        xK.form_request_test.OnresetForm();
                });

        }
    });
    
    
  },
  OnresetForm: function() {
	
    Ext.ComponentQuery.query('[name=no_req]')[0].setValue(""); 
    Ext.ComponentQuery.query('[name=idformulir]')[0].setValue("");
	Ext.ComponentQuery.query('[name=queue_number]')[0].setValue("0");
	
    Ext.ComponentQuery.query('[name=daterequest]')[0].setValue("<?=date("Y-m-d");?>");
    Ext.ComponentQuery.query('[name=dateline]')[0].setValue("<?=date("Y-m-d");?>");
	Ext.ComponentQuery.query('[name=sample_category]')[0].setValue(owner == "RD" ? "" : "Raw Material"); 
	
    Ext.ComponentQuery.query('[name=sample]')[0].setValue("");
    //Ext.ComponentQuery.query('[name=req]')[0].setValue("");
    //Ext.ComponentQuery.query('[name=request_by_people]')[0].setValue("");
    Ext.ComponentQuery.query('[name=pop]')[0].setValue("");
    Ext.ComponentQuery.query('[name=ssp]')[0].setValue("");
    Ext.ComponentQuery.query('[name=notes]')[0].setValue("");
	
	Ext.ComponentQuery.query('[name=rir]')[0].setValue("");
	
    xK.form_request_test.griditemtest.store.removeAll();
	xK.form_request_test.gridFrawamaterial.store.removeAll();
	
	Ext.getCmp('cencel_item_test').setDisabled(true);
	
	if(owner == 'RD'){
		Ext.ComponentQuery.query('[name=type_request]')[0].setValue("");
	}
	
	Ext.ComponentQuery.query('[name=queue_number]')[0].setVisible(false);
	Ext.ComponentQuery.query('[name=date_reciept_sample]')[0].setVisible(false);
  },
  onControlFormulir:function(){
	  var idFormulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
		var idSample = Ext.ComponentQuery.query('[name=sample_category]')[0].getValue();
	  //var idOwner = owner;
	  
	  var sample_category = Ext.getCmp('sample_category').getValue();
	  
	  //+'&idSample='+idSample+'&idOwner='+owner
	  
	  if (sample_category == "Compound") {
			var msn_test = xK.form_request_test.griditemtest.store.getCount();
			var rm_item = xK.form_request_test.gridFrawamaterial.store.getCount();
			if (rm_item <= 0 ) {
				Ext.Msg.alert('Info','Item Raw Material harus disi!!');
				return;
			}
		}
	  
	  
      if (idFormulir == "") {
          Ext.Msg.alert('Info',"Tidak ada data.",function() {
                });  
          return;
      }
	  
	  //tambahkan logik untuk control
	  
      var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
        x.show();
		
		Ext.Ajax.request({
        url: 'form_request_test/control_formulir/?idformulir='+idFormulir+'&idSample='+idSample+'&idOwner='+owner,    // where you wanna post
        //params:postdata,
		method:'GET',
        success: function(response){								
                var jsonData = Ext.decode(response.responseText);
                x.hide();
				if (!jsonData.success) {
					 Ext.Msg.alert('Info', jsonData.msg);
					return;
				}
                Ext.Msg.alert('Info',jsonData.msg,function() {
				
                        xK.form_request_test.OnresetForm();
                });

        }						
    }); 
  },
  onCheckFormulir:function() {
      var idFormulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
	  
	  
	 
	  if (sample_category == "Compound") {
			var msn_test = xK.form_request_test.griditemtest.store.getCount();
			var rm_item = xK.form_request_test.gridFrawamaterial.store.getCount();
			if (rm_item <= 0 ) {
				Ext.Msg.alert('Info','Item Raw Material harus disi!!');
				return;
			}
		}
	  
	  
      if (idFormulir == "") {
          Ext.Msg.alert('Info',"Tidak ada data.",function() {
                });  
          return;
      }
	  
	  //tambahkan logik untuk control
	  
      var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
        x.show();
      Ext.Ajax.request({
        url: 'form_request_test/check_formulir/?idformulir='+idFormulir,    // where you wanna post
        //params:postdata,
		method:'GET',
        success: function(response){								
                var jsonData = Ext.decode(response.responseText);
                x.hide();
				if (!jsonData.success) {
					 Ext.Msg.alert('Info', jsonData.msg);
					return;
				}
                Ext.Msg.alert('Info',jsonData.msg,function() {
				
                        xK.form_request_test.OnresetForm();
                });

        }						
    });  
  },
  onApporveFormulir: function() {
      var idFormulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
      if (idFormulir == "") {
          Ext.Msg.alert('Info',"Tidak ada data.",function() {
                });  
          return;
      }
	  
	  var sample_category = Ext.getCmp('sample_category').getValue();
	 
	  /*if (sample_category == "Compound") {
			var msn_test = xK.form_request_test.griditemtest.store.getCount();
			var rm_item = xK.form_request_test.gridFrawamaterial.store.getCount();
			if (rm_item <= 0 ) {
				Ext.Msg.alert('Info','Item Raw Material harus disi!!');
				return;
			}
		}*/
	  
      var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
        x.show();
      Ext.Ajax.request({
        url: 'form_request_test/approve_formulir/?idformulir='+idFormulir,    // where you wanna post
        //params:postdata,
		method:'GET',
        success: function(response){								
                var jsonData = Ext.decode(response.responseText);
                x.hide();
				
				if (!jsonData.success) {
					 Ext.Msg.alert('Info', jsonData.msg);
					return;
				}
				
                Ext.Msg.alert('Info',jsonData.msg,function() {
				
                        xK.form_request_test.OnresetForm();
                });

        }						
    });  
  },
  onBacktoDraft:function() {
	  var idFormulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
	  var no_req = Ext.ComponentQuery.query('[name=no_req]')[0].getValue();
      if (idFormulir == "") {
          Ext.Msg.alert('Info',"Tidak ada data.",function() {
                });  
          return;
      }
	  
	  Ext.Msg.confirm('Info',"Yakin untuk mengembalikan ke Draft data formulir "+no_req+" ini?", function(cf) {
			if (cf === "yes") {
				  var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
					x.show();
				  Ext.Ajax.request({
					url: 'form_request_test/backtodraft_formulir/?idformulir='+idFormulir,    // where you wanna post
					//params:postdata,
					method:'GET',
					success: function(response){								
							var jsonData = Ext.decode(response.responseText);
							x.hide();
							if (!jsonData.success) {
								 Ext.Msg.alert('Info', jsonData.msg);
								return;
							}
							Ext.Msg.alert('Info',jsonData.msg,function() {
									xK.form_request_test.OnresetForm();
							});
			
					}						
				});  
			}
	  });
  },
  OnDeleteGrid : function() {
	  var idFormulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
	  var no_req = Ext.ComponentQuery.query('[name=no_req]')[0].getValue();
      if (idFormulir == "") {
          Ext.Msg.alert('Info',"Tidak ada data.",function() {
                });  
          return;
      }
	  
	  Ext.Msg.confirm('Info',"Yakin untuk Delete data formulir "+no_req+" ini?", function(cf) {
			if (cf === "yes") {
				  var x = new Ext.LoadMask(Ext.getBody(), {width:600,msg:"Saving..."});
					x.show();
				  Ext.Ajax.request({
					url: 'form_request_test/delete_formulir/?idformulir='+idFormulir,    // where you wanna post
					//params:postdata,
					method:'GET',
					success: function(response){								
							var jsonData = Ext.decode(response.responseText);
							x.hide();
							Ext.Msg.alert('Info',jsonData.msg,function() {
									xK.form_request_test.OnresetForm();
							});
			
					}						
				});  
			}
	  });
  },
  showItemProgress : function() {
		var idformulir = Ext.ComponentQuery.query('[name=idformulir]')[0].getValue();
		var param_x ="idformulir="+idformulir;
		winPopPrint('form_request_test/print_item_progres/?'+param_x,'Print Item progress Machine Test');	
  }

}

xK.form_request_test.init();

  
</script>