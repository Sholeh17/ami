<?php
$CI = get_instance();
?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/resources/ext-theme-access/ext-theme-access-all-rtl.css"> !-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/ux/examples.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/ux/BoxSelect.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/engine/main.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/engine/CheckColumnPatch.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/engine/spread-all.js" charset="UTF-8"></script>
<!--<script type='text/javascript'     src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script> !-->

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ux/grid/css/GridFilters.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ux/grid/css/RangeMenu.css" />


<script>
    
Ext.Ajax.defaultHeaders = {
      'Content-type' : 'charset=utf-8'
  };
  
var url_base = "<?php echo base_url(); ?>";
Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', '<?php echo base_url(); ?>assets/ux');
Ext.require([
    'Ext.grid.*',
	'Ext.form.*',
    'Ext.data.*',
	'Ext.dd.*',
    'Ext.tree.*',    
    'Ext.toolbar.Paging',
    'Ext.util.*',
    'Ext.tip.QuickTipManager',
    'Ext.ux.data.PagingMemoryProxy',
    'Ext.ux.ProgressBarPager',
    'Ext.tab.*',
	'Ext.tab.Panel',	
    'Ext.ux.layout.Center',
    'Ext.window.Window',
    'Ext.form.field.ComboBox',
    'Ext.form.FieldSet',
    'Ext.window.MessageBox',
    'Ext.tip.*',
    'Ext.form.field.File',
    'Ext.example.*',
	'Ext.ux.ajax.SimManager',
    'Ext.ux.IFrame',
    'Ext.ux.form.field.BoxSelect',
    'Ext.ux.grid.FiltersFeature',
    'Ext.layout.container.Column',
    'Ext.ux.form.SearchField',
	'Ext.ux.DataTip',
	'Ext.ux.TabScrollerMenu',
	'Ext.container.Viewport',
	'Ext.selection.CellModel',
	'Ext.ux.statusbar.StatusBar',
	'Ext.selection.CellModel',
	'Ext.chart.*'
]);

Ext.onReady(function(){
xK.backend = {
	treeMenu : null,
	panelsDoc : null,
	id_tab_main:null,
	init: function(){
		this.displayMenu();
		xK.panelCenterHeight = (this.panelsDoc.getHeight() - 50);
	},		
	addNewTab :function(title,id_x) {
			xK.disableBeforeUnload();
			tab_panel_doc = true;
			var i,
            title,
			id_tab  = id_x,
            tabs,
			tabPanel = this.panelsDoc.getComponent('tabPanel');			
			var items = tabPanel.items.items;
			this.id_tab_main= id_tab;
			var exist = false;
			for(var i = 0; i < items.length; i++){
				if(items[i].id === id_tab){
					tabPanel.setActiveTab(i);
					exist = true;
				}
			}							
			
			if (!exist) {
				var laod_tab = xK.maskLoading('Wait loading page..');
				laod_tab.show();	
				Ext.getCmp('menuKantin').collapse();	
						
				tabs = {
					title: title,
					id:id_tab,
					iconCls: id_tab,
					bodyPadding: 0,
					//margins:'2 2 2 2',
					loader: {						
						autoLoad:true,
						url :id_tab+"/?id_tab="+id_tab,
					    scripts: true,
						callback: function() {							
							laod_tab.hide();
							xK.panelCenterHeight = (xK.backend.panelsDoc.getHeight() - 50);	
						}
					},
					tabTip: title,
					active:true,
					closable: true
				};        
				tabPanel.add(tabs);
				tabPanel.setActiveTab(i);
				//tabPanel.getComponent(0).body.update('Done!');
			}
			
	},	
	
	treeMenuPanel : function() {
		var store = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            url: 'roleusermanagement/get_menu_login/'
			}
		});
		this.treeMenu = Ext.create('Ext.tree.Panel', {
				store: store,
				rootVisible: false,
				useArrows: true,		
				border:false,		
				region 	: 'center',        		
				listeners:{
					itemclick: function(view, record, item, index, event){
						//console.log(record);
						if (record.data.leaf) {	
							var nodeId = record.data.id;
							xK.backend.addNewTab(record.data.text,record.data.id);							
						}
					}
				}
			});
		
		
	},	
	
	panelDoc : function() {
		this.panelsDoc =  Ext.create('Ext.panel.Panel', {                                               
                border:false,
				region:'center',
				id:'doc-panel',
				itemId: 'doc-panel',				
                layout: {
					type: 'border',
					padding: 0
				},
                items:[						
						
						{
							xtype : 'tabpanel',
							activeTab : 0,	
							//tabPosition: 'left',
							itemId: 'tabPanel',
							resizeTabs: true,
							enableTabScroll: true,
							defaults: {
								listeners: {
									activate: function(tab, eOpts) {																				
										xK.setTitlePage(tab.title);																				
									}
								},	
								autoScroll: true,
								bodyPadding: 5
							},
							region: 'center',
							border:false,
							items:[
								{
									//title:' SMS', 																		
									layout: 'fit',
									bodyStyle: 'padding:25px',
									contentEl: 'start-div' 
								}
							]
						}
						 
				]
            });
	},
	
	displayMenu : function() {		
		this.treeMenuPanel();
		this.panelDoc();		
		Ext.create('Ext.container.Viewport', {
		layout: 'border',
		id:'bodyLayout',		
		defaults: {
			//collapsible: true,
			//split: true,
			bodyPadding: 0
		},
		items: [{
			xtype: 'box',
			layout		: 'anchor',
			border:false,
            region: 'north',
			cls			: 'docs-header',
			contentEl:'header',
        	height		:  40
		},{
			
			title: 'Menu',
			region:'west',
			id:'menuKantin',
			//margins: '2 2 2 2',
			collapsible: true,
			width: 190,
            //height:400,
			split:true,			
			maxWidth: 280,
			items:[this.treeMenu]
		},
		this.panelsDoc		
		,{
			//xtype: 'box',
            //id: 'header',
            region: 'south',
			margins: '3 0 0 0',
			title:'&copy; 2017 All Right Reserved - PT. Mitra Solusindo Perkasa',
            //html: '',
			height: 35
		}
		],
        renderTo: Ext.getBody()
		});	
	
	}

}
xK.backend.init();


});

window.history.forward();
 function noBack() { window.history.forward(); }
 
</script>
<div id="header">
		<div style="float:right; margin-right:10px; color:#ffffff; padding-top:7px">
			 User: <?=$CI->session->userdata('username');?> | Level: <?=ucfirst(strtolower($this->session->userdata('level')));?> :: [ <?php echo anchor('./','Home','style="color:#ffffff"');?> ] [ <a onClick="javascript:xK.disableBeforeUnload();" href="./auth/logout/">Logout</a> ]
            <!--User: <?=$CI->session->userdata('user_id');?> | Level: <?=ucfirst(strtolower($CI->session->userdata('level')));?> | Group: <?=ucfirst(strtoupper($this->session->userdata('owner') == "RD" ? "R&D":"QUALITY"));?> | Section: <?=ucfirst(strtolower($this->session->userdata('user_section')));?> :: [ <?php echo anchor('./','Home','style="color:#ffffff"');?> ] [ <?php echo anchor('assets/manual/help_v1.docx','Userguide','style="color:#ffffff"');?> ] [ <a onClick="javascript:xK.disableBeforeUnload();" href="./auth/logout/">Logout</a> ]-->
      
		</div>
            <div class="api-title" style="padding-bottom:7px">
			ADVANCED METERING INFRASTRUCTURE
		</div>
	</div>


<div id="contoh"></div>
<div style="display:none;">
<div id="start-div">
            
            
         <br><br><br>
		 <center><img src="<?php echo base_url(); ?>assets/images/pln_logo.png"> </center> 

            
</div>
</div>

