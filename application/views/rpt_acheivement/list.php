
<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.rpt_acheivement = {
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
	doLayoutPanel:function() {
		this.getWindowPrint();
		this.winprint.show();
	},
	getWindowPrint : function() {
		this.winprint = Ext.create('Ext.Window', {
            title: 'Acheivement Report',
            width: 400,
            constrainHeader: true,
			closable:false,
            layout: 'fit',
            modal		: false,
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
						defaultType: 'textfield',
						items: [{
							xtype:'datefield',
							fieldLabel: 'Req. Date From',
							name: 'datefrom',
							value: new Date(),
							format:'Y-m-d',
							allowBlank: false
						},{
							xtype:'datefield',
							fieldLabel: 'Req. Date to',
							format:'Y-m-d',
							value: new Date(),
							name: 'dateto',
							allowBlank: false
						}],

						// Reset and Submit buttons
						buttons: [{
							text: 'Print',
							formBind: true, //only enabled once the form is valid
							disabled: false,
							handler: function() {
							
									
									var dte_frm = Ext.ComponentQuery.query('[name=datefrom]')[0].getRawValue();
									var dte_pto = Ext.ComponentQuery.query('[name=dateto]')[0].getRawValue();
									var param_x ="dte_frm="+dte_frm+"&dte_pto="+dte_pto;
									//var param_x = first+'/'+last;
									winPopPrint('rpt_acheivement/print_acheivement_by_user?'+param_x,'Print Formulir Machine Test');
								
							}
						}]
					}
			]
        });
		
		Ext.getCmp(xK.backend.id_tab_main).add(this.winprint);
	}

}

xK.rpt_acheivement.init();

</script>