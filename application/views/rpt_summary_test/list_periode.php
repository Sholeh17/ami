
<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.rpt_summary_test_periode = {
	winprint:null,	
	init:function() {
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
		
		var stored_data = this.getStoredData('mitemsreport','m_machine_detail/get_data_machine','mitemsreport','20');
		return stored_data;
	  },
	doLayoutPanel:function() {
		this.getWindowPrint();
		this.winprint.show();
	},
	getWindowPrint : function() {
		this.winprint = Ext.create('Ext.Window', {
            title: 'Parameter Report Test Periode',
            width: 450,
            height: 190,    
            constrainHeader: true,
			closable:false,
            layout: 'fit',
            modal		: false,
			margins:'2 2 2 2',
            closeAction	: 'hide',
            items: [
				 {
						//title: 'Simple Form',
						bodyPadding: 5,
						width: 200,

						// The form will submit an AJAX request to this URL when submitted
						url: 'save-form.php',

						// Fields will be arranged vertically, stretched to full width
						layout: 'form',
						defaults: {
							//anchor: '100%'
						},

						// The fields
						margins:'2 2 2 2',
						defaultType: 'textfield',
						items: [
							{
								xtype : 'combo',					
								name:'sample',
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
									 
									}
								}					
							},
							{
								xtype : 'combo',
								name:'mesin_report_test',
								fieldLabel: 'Machine Report',
								width: 450,
								disabled:false,
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
							},{
								xtype:'datefield',
								fieldLabel: 'Test. Date From',
								name: 'datefrom',
								value: new Date(),
								format:'Y-m-d',
								allowBlank: false
							},{
							xtype:'datefield',
							fieldLabel: 'Test. Date to',
							format:'Y-m-d',
							value: new Date(),
							name: 'dateto',
							allowBlank: false
						}
						
						],

						// Reset and Submit buttons
						buttons: [{
							text: 'Print',
							formBind: true, //only enabled once the form is valid
							disabled: false,
							handler: function() {								
									
									var datefrom = Ext.ComponentQuery.query('[name=datefrom]')[0].getRawValue();
									var dateto = Ext.ComponentQuery.query('[name=dateto]')[0].getRawValue();
									var sample = Ext.ComponentQuery.query('[name=sample]')[0].getValue();
									var mesin_report_test = Ext.ComponentQuery.query('[name=mesin_report_test]')[0].getValue();
									if (sample == "" || sample == null) {
										alert("Pilih sample....!!!");
										return;
									}
									if (mesin_report_test == null)
										mesin_report_test = "";
										
									var param_x ="sample="+sample+"&datefrom="+datefrom+"&dateto="+dateto+"&mesin_report_test="+mesin_report_test;
									//console.log(param_x);
									//var param_x = first+'/'+last;
									winPopPrint('rpt_summary_test_periode/print_report_formulir?'+param_x,'Print Summary Test Result');
								
							}
						}]
					}
			]
        });
		
		Ext.getCmp(xK.backend.id_tab_main).add(this.winprint);
	}

}

xK.rpt_summary_test_periode.init();

</script>