<meta charset="UTF-8">


<?php
//If not found field of report test so hold the view.
if (!$fields_machine['fields']) {
    echo "<script>Ext.Msg.alert('Info','No found fields report!!');</script>";
    die;
}
?>
<script>
    
xK.forminputtest = {
  dynamicPanel:null,
  gridListFormulir : null,
  gridKaryawan : null,
  store_master_agent : null,
  criteriaEnum:"Reguler",
  scaleEnum:"Lab Scale",
  winListFormulir:null,
  init : function() {
      this.doLayoutPanel();      
  },
  tpl_files: new Ext.XTemplate(
		'<p>File 1: {file_others_1}</p>',
		'<p>File 2: {file_others_2}</p>'
  ),
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
  getGridMachineParamters : function() {
     // register model
    Ext.define('m_machinetest', {
		extend: 'Ext.data.Model',	
        fields: [
				{name:'lock_row'},
				{name:'idcompound'},
				{name:'namecompound'},
				{name:'no_type'},
				{name:'pallet'},
				{name:'no_lot'},
				{name:'time_sample'},
				{name:'batch'},
				{name:'result'},
			<?php 
				$cntFields = count($fields_machine['fields']);
				for($i = 0 ; $i < $cntFields; $i++) {
			?>
           				{name: '<?=trim($fields_machine['fields'][$i]);?>'}
			<?php
					if ($i < ($cntFields-1))
						echo ",";
				}
			?>
        ]
    });
	
	var stored_data = Ext.create('Ext.data.Store', {			
				autoDestroy: true,
				model: 'm_machinetest',
				storeId: 'm_machinetest',
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
  
  getGridListMachine : function() {  
        var storeMdl = this.getGridMachineParamters();
		
		var rowEditing = new Ext.grid.plugin.CellEditing({
						clicksToEdit: 1,
						listeners: {
							edit: function() {									
								
							}
						}
						});
						
		var txtItemCompund = {
							xtype : 'combo',					
							name:'itemcomound',
							itemId: 'itemcomound',
							displayField : 'name',					
							valueField	 : 'name',
							tpl: '<tpl for="."><div class="x-boundlist-item">{idmaterial} - {name}</div></tpl>',	
							minChars		: 1,
							queryDelay		: 1,
							pageSize		: 10,
							editable	 : true,							
							store		 : this.getModelDataItemCompund(),
							triggerAction: 'all',
							listeners : {
									select : function(o,row,r){
										var data = row[0].data;
										 var gR = xK.forminputtest.gridKaryawan.getSelectionModel().hasSelection();
										 var rowX = xK.forminputtest.gridKaryawan.getSelectionModel().getSelection()[0];
										 rowX.set('idcompound',data.idmaterial);
										 rowX.set('namecompound',data.name);
										 
									}
							}					
						};
										
        this.gridKaryawan = Ext.create('Ext.grid.Panel',  {
            store: storeMdl,
			itemId:'removeListItem',            
            plugins: [rowEditing],	
			selModel: {
                			selType: 'cellmodel'
            },	
			hidden:<?php if($fields_machine['fields']) echo "false"; else echo "true"; ?>,
            id:'trxFormTestmachine',
            height:300,
			columnLines: true,           
            columns: [{
                        text: 'Compound',
                        sortable: true,                    
                        dataIndex: 'namecompound',
                        //editor: txtItemCompund,	
                        width: 250
                     },
					 {
                        text: 'Tipe',
                        sortable: true,                    
                        dataIndex: 'no_type',
                        width: 100,
						editor: {													
									listeners : {
										focus : function(o) {
											o.selectText();
										}							
								}
						}
                     },
					  {
                        text: 'Pallet',
                        sortable: true,                    
                        dataIndex: 'pallet',
                        width: 80,
						editor: {													
									listeners : {
										focus : function(o) {
											o.selectText();
										}							
								}
						}
                     },
					  {
                        text: 'No Lot',
                        sortable: true,                    
                        dataIndex: 'no_lot',
                        width: 80,
						editor: {													
									listeners : {
										focus : function(o) {
											o.selectText();
										}							
								}
						}
                     },
					  {
                        text: 'Time Sample<br>(Ex.08:00)',
                        sortable: true,
						align:'center',                    
                        dataIndex: 'time_sample',
                        width: 100,
						editor: {													
									listeners : {
										focus : function(o) {
											o.selectText();
										}							
								}
						}
                     },
					  {
                        text: 'Batch',
                        sortable: true,                    
                        dataIndex: 'batch',
                        width: 60
                     },
					 {
						text: '<?=utf8_encode($name_report);?>',
						columns: [
							<?php 
								$compound = $rowformulir->sample;
								$cntFields = count($fields_machine['fields']);
								for($i = 0 ; $i < $cntFields; $i++) {
									//Get parameter Limit Toleransi
									$limitToleransi = $this->mf->get_limit_toleransi_compund($compound, $idreportmachine, trim($fields_machine['fields'][$i]));
									if ($limitToleransi)
										$limitTolerens = "<br>(".$limitToleransi->start_toleransi." - ".$limitToleransi->end_toleransi.")";
									else
										$limitTolerens = "";
							?>			
								{
										header: '<?=$fields_machine['textlabel'][$i].$limitTolerens;?>',
										dataIndex: '<?=trim($fields_machine['fields'][$i]);?>',
										align:'center',
										menuDisabled: true,
										width: 150,
										<?php if($fields_machine['xtype'][$i] == "datefield") { 
													echo "xtype : 'datecolumn',";
													echo "format: 'Y-m-d H:i:s',";
											  }
										?>
										editor: {
													xtype: '<?=trim($fields_machine['xtype'][$i]);?>',
													<?php if($fields_machine['xtype'][$i] == "datefield") echo "format:'Y-m-d H:i:s',";?>
													listeners : {
														focus : function(o) {
															o.selectText();
														}							
												}
										}	
								}
							<?php
									if ($i < ($cntFields-1))
										echo ",";
								}
							?>
														
						]
					 }
                    ],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                  
                },
               selectionchange: function(sm, records, rec) {
                    xK.forminputtest.gridKaryawan.down('#removeListItem').setDisabled(!records.length);
               }
            },
			tbar: [{
							text: 'Add',							
							iconCls: 'btn-add',							
							handler : function() {	
								var countRow = xK.forminputtest.gridKaryawan.store.getCount();
								var r = Ext.create('m_machinetest', {	
									lock_row:0,
									idcompound : xK.input_report_test.sample,
									namecompound : xK.input_report_test.sample,
									no_type :'', //xK.input_report_test.r_type,
									<?php 
										$cntFields = count($fields_machine['fields']);
										for($i = 0 ; $i < $cntFields; $i++) {
									?>
												<?=trim($fields_machine['fields'][$i]);?>:''
									<?php
											if ($i < ($cntFields-1))
												echo ",";
										}
									?>
								}), edit = rowEditing;
								edit.cancelEdit();
								xK.forminputtest.gridKaryawan.store.insert(countRow, r);								
								edit.startEditByPosition({
									row: countRow,
									column: 0
								});
							}
					},
					{
							itemId: 'removeListItem',
							text: 'Remove',														
							iconCls: 'btn-delete',
							handler: function() {
								var countRow = xK.forminputtest.gridKaryawan.store.getCount();
								var sm = xK.forminputtest.gridKaryawan.getSelectionModel();
								var row = xK.forminputtest.gridKaryawan.getSelectionModel().getSelection()[0];
  								if (row.get('lock_row') == "1")	
									return;
								var edit = rowEditing;
								edit.cancelEdit();
								xK.forminputtest.gridKaryawan.store.remove(sm.getSelection());
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
		
		var countRow = xK.forminputtest.gridKaryawan.store.getCount();
		var r = Ext.create('m_machinetest', {	
			lock_row:1,
			idcompound : xK.input_report_test.sample,
			namecompound : xK.input_report_test.sample,
			no_type : xK.input_report_test.r_type,
			<?php 
				$cntFields = count($fields_machine['fields']);
				for($i = 0 ; $i < $cntFields; $i++) {
			?>
						<?=trim($fields_machine['fields'][$i]);?>:''
			<?php
					if ($i < ($cntFields-1))
						echo ",";
				}
			?>
		}), edit = rowEditing;
		edit.cancelEdit();
		xK.forminputtest.gridKaryawan.store.insert(countRow, r);								
		edit.startEditByPosition({
			row: countRow,
			column: 0
		});
		
		xK.forminputtest.getResultValuesFormTest();

  },
  doLayoutPanel : function() {
	  this.getGridListMachine();
	   var dynamicPanel =  Ext.widget('form', {    
                border: false,
				title:'Form Input Test - <?=utf8_encode($title_report);?>',
                bodyPadding: 10,
				buttonAlign:'center',
				bodyPadding: '2 2 0',
			    items:[
                       this.gridKaryawan,
					   {
						xtype: 'fieldcontainer',
						//fieldLabel: 'File Upload',
						labelStyle: 'font-weight:bold;padding:0;',
						

						fieldDefaults: {
							labelAlign: 'top',
							labelWidth: 150
						},
						items: [
						
							{
								xtype: 'fieldcontainer',
								fieldLabel: 'CSV FILE IMPORT',
								labelStyle: 'font-weight:bold;padding:0;',
								layout: 'hbox',
								width:500,
								defaultType: 'textfield',
								fieldDefaults: {
									labelAlign: 'top'
								},
			
								items: [
									{
										xtype: 'filefield',
										emptyText: 'Select an file csv',
										//fieldLabel: 'File CSV',
										width:300,
										name: 'file1',
										buttonText: 'Browse',
										buttonConfig: {
											iconCls: 'upload-icon'
										}
									},
									{
										xtype: 'button',
										cls: 'contactBtn',
										scale: 'small',
										margin: '0 5 5 15',
										text: 'Upload CSV',
										handler: this.uploadCSV
									}	
								]
							},
							
							{
								xtype: 'fieldcontainer',
								fieldLabel: 'Other Files',
								labelStyle: 'font-weight:bold;padding:0;',
								layout: 'fit',
								width:500,
								defaultType: 'textfield',
								fieldDefaults: {
									labelAlign: 'top'
								},
			
								items: [
									{
										xtype: 'filefield',
										emptyText: 'Select an file',
										//fieldLabel: 'File CSV',
										width:300,
										name: 'file_others_1',
										margin:'5 5 5 5',
										buttonText: 'Browse',
										buttonConfig: {
											iconCls: 'upload-icon'
										}
									},
									{
										xtype: 'filefield',
										emptyText: 'Select an file',
										//fieldLabel: 'File CSV',
										width:300,
										name: 'file_others_2',
										margin:'5 5 5 5',	
										buttonText: 'Browse',
										buttonConfig: {
											iconCls: 'upload-icon'
										}
									}
								]
							},
							{
									id: 'detailPanel',
									region: 'center',
									bodyPadding: 7,
									bodyStyle: "background: #ffffff;",
									html: 'Files.'
							}
						]
					   }
            	],
				buttons: [{
						text: 'Save',
						handler: this.onSave
					},{
						text: 'Print Result',
						disabled:true,
						id:'print_result',
						handler: function() {
							var list_formulir_test = Ext.getCmp('list_formulir_test').getValue();
							var idmachine =Ext.getCmp('machine_test_input').getValue();
							var machine_test_input = Ext.getCmp('machine_test_input').getValue();
							var list_report_test = Ext.getCmp('list_report_test').getValue();
							var namemachine = Ext.getCmp('machine_test_input').getRawValue();
							var namereporttest = Ext.getCmp('list_report_test').getRawValue();
							var param_x ="list_formulir_test="+list_formulir_test+"&idmachine="+idmachine+"&namemachine="+namemachine+"&namereporttest="+namereporttest+"&machine_test_input="+machine_test_input+"&list_report_test="+list_report_test;
							winPopPrint('input_report_test/print_result/?'+param_x,'Print Result Machine Test');
						}
					}]
            });

            Ext.getCmp('forminputtestmachine').removeAll();
            Ext.getCmp('forminputtestmachine').update('');
            Ext.getCmp('forminputtestmachine').add(dynamicPanel);    
			
	  
  },
  onSave:function() {
	    var list_formulir_test = Ext.getCmp('list_formulir_test').getValue();
		var machine_test_input = Ext.getCmp('machine_test_input').getValue();
		var list_report_test = Ext.getCmp('list_report_test').getValue();
		var param_x ="&data[list_formulir_test]="+list_formulir_test+"&data[machine_test_input]="+machine_test_input+"&data[list_report_test]="+list_report_test;
		
		var grid_item_trx = xK.forminputtest.gridKaryawan.getStore();
		var j =0;
		<?php 
				$cntFields = count($fields_machine['fields']);
				for($i = 0 ; $i < $cntFields; $i++) {
		?>
					param_x += "&data[fields][<?=$i;?>]=<?=trim($fields_machine['fields'][$i]);?>";
		
		<?php
					
				}
		?>			
					
	    grid_item_trx.each(function(rec) {
				param_x += "&data[items]["+j+"][idcompound]="+rec.get('idcompound');
				param_x += "&data[items]["+j+"][no_type]="+rec.get('no_type');
				param_x += "&data[items]["+j+"][lock_row]="+rec.get('lock_row');
				param_x += "&data[items]["+j+"][pallet]="+rec.get('pallet');
				param_x += "&data[items]["+j+"][no_lot]="+rec.get('no_lot');
				param_x += "&data[items]["+j+"][batch]="+rec.get('batch');
				param_x += "&data[items]["+j+"][time_sample]="+rec.get('time_sample');
			<?php 
				
				for($i = 0 ; $i < $cntFields; $i++) {
			?>
					
					param_x += "&data[items]["+j+"][<?=trim($fields_machine['fields'][$i]);?>]="+rec.get('<?=trim($fields_machine['fields'][$i]);?>');
						
			<?php
					
				}
			?>
			
			j++;
		});
		
		//console.log(param_x);
	  	var form = this.up('form').getForm();	
		form.submit({  
			url:'input_report_test/saveparamter_test',
			waitMsg:'Waiting, proccess saving....!!',
			params:param_x,
			method:'POST',
			success: function(result,opt) {
			    var jsonData = opt.result;
				//console.log(opt);
				Ext.example.msg('Info', jsonData.msg, 10000);
				Ext.getCmp('print_result').setDisabled(false);
				
			},
			failure: function(result,opt) {
				Ext.example.msg('Erorr!!', opt.result.msg, 8000);
			}
		});                        
		
	  
  },
  
  uploadCSV : function() {
		var list_formulir_test = Ext.getCmp('list_formulir_test').getValue();
		var machine_test_input = Ext.getCmp('machine_test_input').getValue();
		var list_report_test = Ext.getCmp('list_report_test').getValue();
		var param_x ="&data[list_formulir_test]="+list_formulir_test+"&data[machine_test_input]="+machine_test_input+"&data[list_report_test]="+list_report_test;
		
		var form = this.up('form').getForm();	
		form.submit({  
			url:'input_report_test/uploadCSV',
			waitMsg:'Waiting, proccess upload....!!',
			params:param_x,
			method:'POST',
			success: function(result,opt) {
			    var jsonData = opt.result;
				Ext.Msg.alert('Info',jsonData.msg);
				Ext.getCmp('print_result').setDisabled(false);
                                xK.forminputtest.getResultValuesFormTest();
				
			},
			failure: function(result,opt) {
				Ext.Msg.alert('Erorr!!', opt.result.msg);
			}
		});                        
		
  },
  
  getResultValuesFormTest: function() {
	  var idformulir = Ext.getCmp('list_formulir_test').getValue();
	  var idmachine =Ext.getCmp('machine_test_input').getValue();
	  var idreport =Ext.getCmp('list_report_test').getValue();
	  var params  = "idformulir="+idformulir+"&idreport="+idreport+"&idmachine="+idmachine;
	  Ext.Ajax.request({
						url: 'input_report_test/get_item_result_testform/?'+params,    // where you wanna post
						//params:params,
						success: function(response){																
							var jsonData = Ext.decode(response.responseText);
							var data = jsonData.data;
							var countData = jsonData.count * 1;
							
							if (countData > 0) { 	
                            	xK.forminputtest.gridKaryawan.store.loadData(data,false);
								Ext.getCmp('print_result').setDisabled(false);
							} else {
								Ext.getCmp('print_result').setDisabled(true);
							}
							
							
							
							// define a template to use for the detail view
							var bookTplMarkup = [
								'<font color="blue">File 1: <a href="<?php echo base_url(); ?>uploads/other_file/{file_others_1}" target="_blank">{file_others_1}</a> <button onClick="deleteFiles(\'{idformulir}\',\'{idreport}\',\'1\',\'{file_others_1}\')">Delete</button><br/>',
								'File 2: <a href="<?php echo base_url(); ?>uploads/other_file/{file_others_2}" target="_blank">{file_others_2}</a> <button onClick="deleteFiles(\'{idformulir}\',\'{idreport}\',\'2\',\'{file_others_2}\')">Delete</button></font>'
							];
							var bookTpl = Ext.create('Ext.Template', bookTplMarkup);
						
							var datax = jsonData.files.data;
							console.log(datax);
							
							var detailPanel = Ext.getCmp('detailPanel');
							detailPanel.update(bookTpl.apply(datax));
							
							                            
						}
						
					});
  			}
  
  
  

}

xK.forminputtest.init();

function deleteFiles(idformulir,idreport,fl,nf) {
	if (nf == "") {
		alert("File tidak ditemukan!!");
		return;
	}
	
	if (confirm("Yakin dihapus?")) {
		var params = "idformulir="+idformulir+"&idreport="+idreport+"&fl="+fl;
		Ext.Ajax.request({
							url: 'input_report_test/delete_file/?'+params,    // where you wanna post
							//params:params,
							success: function(response){
								reLoadData();
							}
		 });
	}
}


function reLoadData() {
	
	  var idformulir = Ext.getCmp('list_formulir_test').getValue();
	  var idmachine =Ext.getCmp('machine_test_input').getValue();
	  var idreport =Ext.getCmp('list_report_test').getValue();
	  var params  = "idformulir="+idformulir+"&idreport="+idreport+"&idmachine="+idmachine;	
		
	  Ext.Ajax.request({
						url: 'input_report_test/get_item_result_testform/?'+params,    // where you wanna post
						//params:params,
						success: function(response){																
							var jsonData = Ext.decode(response.responseText);
							var data = jsonData.data;
							var countData = jsonData.count * 1;
							
							if (countData > 0) { 	
                            	xK.forminputtest.gridKaryawan.store.loadData(data,false);
								Ext.getCmp('print_result').setDisabled(false);
							} else {
								Ext.getCmp('print_result').setDisabled(true);
							}
							
							
							
							// define a template to use for the detail view
							var bookTplMarkup = [
								'<font color="blue">File 1: <a href="{file_others_1}" target="_blank">{file_others_1}</a> <button onClick="deleteFiles(\'{idformulir}\',\'{idreport}\',\'1\',\'{file_others_1}\')">Delete</button><br/>',
								'File 2: <a href="{file_others_2}" target="_blank">{file_others_2}</a> <button onClick="deleteFiles(\'{idformulir}\',\'{idreport}\',\'2\',\'{file_others_2}\')">Delete</button></font>'
							];
							var bookTpl = Ext.create('Ext.Template', bookTplMarkup);
						
							var datax = jsonData.files.data;
							console.log(datax);
							
							var detailPanel = Ext.getCmp('detailPanel');
							detailPanel.update(bookTpl.apply(datax));
							
							                            
						}
						
					});	 
}

  
</script>