<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>
<script>
xK.rpt_control_machine = {
	dynamicPanel:null,	
	init:function() {
		this.doLayoutPanel();
	},
	doLayoutPanel:function() {
		this.dynamicPanel =  Ext.widget('container', {     
				renderTo:'panel_<?=$_REQUEST['id_tab'];?>'
				,id:'panel_panel_<?=$_REQUEST['id_tab'];?>'
				,height:(Ext.getBody().getViewSize().height - 120)
				,layout: 'anchor'
				,items:[
					{
							url:'save-form.php',
							frame:true,
							title: 'Simple Form',
							bodyStyle:'padding:5px 5px 0',
							bodyPadding: 5,
							width: 350,
							fieldDefaults: {
								msgTarget: 'side',
								labelWidth: 75
							},
							defaultType: 'textfield',
							defaults: {
								anchor: '100%'
							},

							items: [{
								fieldLabel: 'First Name',
								name: 'first',
								allowBlank:false
							},{
								fieldLabel: 'Last Name',
								name: 'last'
							},{
								fieldLabel: 'Company',
								name: 'company'
							}, {
								fieldLabel: 'Email',
								name: 'email',
								allowBlank:false,
								vtype:'email'
							}, {
								xtype: 'timefield',
								fieldLabel: 'Time',
								name: 'time',
								minValue: '8:00am',
								maxValue: '6:00pm'
							}],

							buttons: [{
								text: 'Save'
							},{
								text: 'Cancel'
							}]
						}
				
				]
		});
	}
}

xK.rpt_control_machine.init();

</script>