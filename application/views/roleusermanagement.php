<?php
$CI = get_instance();
?>
<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
var url_api ="";
Ext.onReady(function(){
xK.secassign = {	
	id_tab_main : "<?=$_REQUEST['id_tab'];?>", 	
	panelGrid:null,
	GridHeader:null,
	winTrx:null,
	winTrx2:null,	
	AccessRight:{ },
	init: function() {						
		this.display_panel();			
	},	
	getStoredData : function(name_model,url,id_stored,cnt_page) {
		  var storelib = Ext.create('Ext.data.JsonStore', {
				autoDestroy: true,
				model: name_model,
				listeners: {
					load : function(ds,r){
						
						
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
	getModelGrid1 : function() {
		 // register model
		Ext.define('gridDDROG1', {
			extend: 'Ext.data.Model',
			fields: [
			   {name: 'level'},
			   {name: 'fitur_code'},
			   {name: 'group'},
			   {name: 'name_menu'},
			   {name: 'cr', type: 'bool'},
			   {name: 'rd', type: 'bool'},
			   {name: 'upd', type: 'bool'},
			   {name: 'del', type: 'bool'},
			   {name: 'approve', type: 'bool'},
			   {name: 'posting', type: 'bool'},
			   {name: 'control', type: 'bool'},
			   {name: 'close', type: 'bool'},
			   {name: 'print', type: 'bool'},
			   {name: 'add_rm_cp', type: 'bool'},
			   {name: 'orderId', type: 'int'}
			]
		});

		var stored_data = this.getStoredData('gridDDROG1','roleusermanagement/get_allfitures','gridDDROG1','20');
		return stored_data;
	},
	getModelGrid2 : function() {
		 // register model
		Ext.define('gridDDROG2', {
			extend: 'Ext.data.Model',
			fields: [
			   {name: 'level'},
			   {name: 'fitur_code'},
			   {name: 'group'},
			   {name: 'name_menu'},
			   {name: 'cr', type: 'bool'},
			   {name: 'rd', type: 'bool'},
			   {name: 'upd', type: 'bool'},
			   {name: 'del', type: 'bool'},
			   {name: 'approve', type: 'bool'},
			   {name: 'posting', type: 'bool'},
			   {name: 'control', type: 'bool'},
			   {name: 'close', type: 'bool'},
			   {name: 'print', type: 'bool'},
			   {name: 'add_rm_cp', type: 'bool'},
			   {name: 'orderId', type: 'int'}
			]
		});

		var stored_data = this.getStoredData('gridDDROG2','roleusermanagement/get_allfitures','gridDDROG2','20');
		return stored_data;
	},
	display_panel : function() {			
		var firstGridStore = this.getModelGrid1();
		
		
		// Column Model shortcut array
		var columns = [
			{text: "Fitures", flex: 1, sortable: false, dataIndex: 'fitur_code'}
		];

		// declare the source Grid
		var firstGrid = Ext.create('Ext.grid.Panel', {        
			 viewConfig: {
				plugins: {
					ptype: 'gridviewdragdrop',
					dragGroup: 'firstGridDDGroup',
					dropGroup: 'secondGridDDGroup'
				},
				listeners: {
					drop: function(node, data, dropRec, dropPosition) {
						var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('fitur_code') : ' on empty view';
						//Ext.example.msg("Drag from right to left", 'Dropped ' + data.records[0].get('fitur_code') + dropOn);
						/*
						var gridView = Ext.ComponentQuery.query("gridpanel")[0];
						var column = Ext.create('Ext.grid.column.Column', {text: 'New Column'});
						gridView.headerCt.insert(gridView.columns.length, column);
						gridView.getView().refresh();
						*/
						
					}
				}
			},
			store            : firstGridStore,
			columns          : columns,
			region 	: 'west',							
			width:280,
			itemId: 'grid1',
			stripeRows       : true,
			split:true,
			tools: [{
                type: 'refresh',
                tooltip: 'Reset both grids',
                scope: this,
                handler: this.onResetClick
            }],
			title            : 'All Fitures >> (Drag to Right)',
			margins          : '0 0 0 0'
		});

		var secondGridStore = this.getModelGrid2();
		
		
		var columns = [
			{text: "Fitures", flex: 1, sortable: false, dataIndex: 'fitur_code'},	
			{
				text: 'CRUD Action',
				columns: [	
					{text: "Create", width: 70, sortable: false, dataIndex: 'cr',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Read", width: 70, sortable: false, dataIndex: 'rd',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Update", width: 70, sortable: false, dataIndex: 'upd',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Delete", width: 70, sortable: false, dataIndex: 'del',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					}
				]
			},
			{
				text: 'Other Action',
				columns: [	
					{text: "Control", width: 70, sortable: false, dataIndex: 'control',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') != 'Formulir Test Request')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
                        }
					},
					{text: "Check", width: 70, sortable: false, dataIndex: 'posting',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						return (new Ext.ux.CheckColumn()).renderer(val);
                                            }
					},
					{text: "Approve", width: 70, sortable: false, dataIndex: 'approve',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Employee' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Close", width: 70, sortable: false, dataIndex: 'close',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'View Output Order' || rec.get('fitur_code') == 'View Sales Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Production Capacity Plan' || rec.get('fitur_code') == 'Customer Alocation Plan' || rec.get('fitur_code') == 'Profitability' || rec.get('fitur_code') == 'Employee' || rec.get('fitur_code') == 'Waterfall')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Print", width: 70, sortable: false, dataIndex : 'print',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						if (rec.get('fitur_code') == 'Purchase Order' || rec.get('fitur_code') == 'Seritikasi Approve' || rec.get('fitur_code') == 'Employee')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					},
					{text: "Add RM CP", width: 70, sortable: false, dataIndex : 'add_rm_cp',xtype: 'checkcolumn', renderer :function(val, m, rec) {
						
						if (rec.get('fitur_code') != 'Formulir Test Request')
							return '';
						else
							return (new Ext.ux.CheckColumn()).renderer(val);
						}
					}		
				]
			}
		];
		// create the destination Grid
		var secondGrid = Ext.create('Ext.grid.Panel', {  
			viewConfig: {
				plugins: {
					ptype: 'gridviewdragdrop',
					dragGroup: 'secondGridDDGroup',
					dropGroup: 'firstGridDDGroup'
				},
				listeners: {
					drop: function(node, data, dropRec, dropPosition) {
						var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
						//Ext.example.msg("Drag from left to right", 'Dropped ' + data.records[0].get('name') + dropOn);
					}
				}
			},
			selType: 'rowmodel',
			store            : secondGridStore,
			columns          : columns,
			itemId: 'grid2',
			stripeRows       : true,
			split:true,
			region:'center',
			title            : 'Rule Akses',
			margins          : '0 0 0 0',
			bbar:Ext.create('Ext.toolbar.Toolbar', {
						items  : [{text:'Select All',xtype: 'button',handler:xK.secassign.onSelectAll},{text:'UnSelect', xtype: 'button', handler:xK.secassign.onUnSelectAll},'-',{text:'Save Role',xtype: 'button',iconCls: 'btn-save',handler:xK.secassign.onSaveRole}]})
		});

		
		var level_store = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data : [
				{"id":"USER", "name":"USER"},
				{"id":"GENERAL", "name":"GENERAL"},
                {"id":"ANALISA LAB", "name":"ANALISA LAB"},
				{"id":"SECTION HEAD", "name":"SECTION HEAD"},					
				{"id":"DEPARTMENT HEAD", "name":"DEPARTMENT HEAD"},	
				{"id":"DIVISION HEAD", "name":"DIVISION HEAD"},
				{"id":"ADMIN", "name":"ADMIN"}
			]
		});
		
	
		this.panelGrid =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'				
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height: (xK.panelCenterHeight+12)
				,layout: 'border'				
				,items: [
						{
							xtype : 'form',
							region 	: 'north',
							margins: '5 5 0 5',
							itemId:'form',
							bodyStyle : {padding:5},
							height:40,
							fieldDefaults: {								
								labelWidth: 125
							},
							items : [
								{
									xtype: 'combo',
									typeAhead: true,
									displayField : 'name',
									fieldLabel: 'Select Group Level',
									name:'level',									
									width: 450,	
									itemId:'level',
									valueField : 'id',	
									listeners: {
										select: function(o) {											
											firstGridStore.load({params:{level:o.getValue(),be_get:'left'}});
											xK.secassign.panelGrid.down('#grid2').getStore().load({params:{level:o.getValue(),be_get:'right'}});
										}
									},
									triggerAction: 'all',
									store:level_store,									
									allowBlank: false
								}
							]
						},
						firstGrid						
						,secondGrid
					 ]	 
            });
		

	},	
	 onResetClick: function(){
        var level = xK.secassign.panelGrid.down('#level').getValue();
        xK.secassign.panelGrid.down('#grid1').getStore().load({params:{level:level,be_get:'left'}});
		xK.secassign.panelGrid.down('#grid2').getStore().load({params:{level:level,be_get:'right'}});
        //purge destination grid
        //xK.secassign.panelGrid.down('#grid2').getStore().removeAll();
    },
	onSelectAll : function() {
		var grid_item_trx = xK.secassign.panelGrid.down('#grid2').getStore();	
		
			grid_item_trx.each(function(rec) {									
					rec.set('cr',true);
					rec.set('rd',true);
					rec.set('upd',true);
					rec.set('del',true);
					rec.set('control',true);
					rec.set('posting',true);
					rec.set('approve',true);
					rec.set('close',true);
					rec.set('print',true);
					rec.set('add_rm_cp',true);
			});		
	},
	onUnSelectAll : function() {
		var grid_item_trx = xK.secassign.panelGrid.down('#grid2').getStore();	
		
			grid_item_trx.each(function(rec) {									
					rec.set('cr',false);
					rec.set('rd',false);
					rec.set('upd',false);
					rec.set('del',false);
					rec.set('control',false);
					rec.set('posting',false);
					rec.set('approve',false);
					rec.set('close',false);
					rec.set('print',false);
					rec.set('add_rm_cp',false);
			});
	},
	onSaveRole: function() {
		var form = xK.secassign.panelGrid.down('#form').getForm();
		var items= "";
		var j=0;
		var grid_item_trx = xK.secassign.panelGrid.down('#grid2').getStore();	
		
			grid_item_trx.each(function(rec) {
				var level = xK.secassign.panelGrid.down('#level').getValue()
					,fitur_code	 = rec.get('fitur_code')					
					,group	 = rec.get('group')					
					,name_menu	 = rec.get('name_menu')					
					,cr = rec.get('cr')
					,rd = rec.get('rd')
					,upd = rec.get('upd')
					,del = rec.get('del')
					,control = rec.get('control')
					,posting = rec.get('posting')
					,approve = rec.get('approve')
					,close = rec.get('close')
					,print = rec.get('print')
					,add_rm_cp = rec.get('add_rm_cp')
					,orderId = rec.get('orderId')
				items += "&data["+j+"][level]="+level+"&data["+j+"][fitur_code]="+fitur_code+"&data["+j+"][group]="+group+"&data["+j+"][name_menu]="+name_menu+"&data["+j+"][cr]="+cr+"&data["+j+"][rd]="+rd+"&data["+j+"][upd]="+upd+"&data["+j+"][del]="+del+"&data["+j+"][posting]="+posting+"&data["+j+"][approve]="+approve+"&data["+j+"][close]="+close+"&data["+j+"][print]="+print+"&data["+j+"][add_rm_cp]="+add_rm_cp+"&data["+j+"][orderId]="+orderId+"&data["+j+"][control]="+control;	
				j++;
			});
		if (form.isValid()) {
			form.submit({
							clientValidation: true, 
							params:items,
							url:'roleusermanagement/save',
							waitMsg:'Waiting, proccess saving....!!',
							success: function(result,opt) {
								Ext.Msg.alert('info',"success.!!");							 
							   
							},
							failure: function(result,opt) {
								
							}
			});
		}
	}
}

xK.secassign.init();

});
</script>


