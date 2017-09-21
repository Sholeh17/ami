<div id="panel_<?=$_REQUEST['id_tab'];?>"> </div>

<script>
Ext.onReady(function(){
xK.sys_cp = {
  id_tab_main : "<?=$_REQUEST['id_tab'];?>", 	  
  formestimasiCatring:null,
  gridHirs : null,
  winTrx:null,
  panelView:null,
  store_master_agent : null,
  init : function() {
	  xK.setTitlePage('Registration');
      this.doLayoutPanel();      
  },
    
  genaretformKaryawan : function() { 		
		var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
        this.formestimasiCatring = Ext.create('Ext.form.Panel', {                                
                autoScroll:true,				
                waitMsgTarget: true,
                split: true,
                bodyPadding: 5,
				buttonAlign:'left',
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100                    
                },
                items: [
                {
                    xtype: 'textfield',					
                    fieldLabel: 'New Password',
					inputType:'password',
                    width: 250,
                    //name:'password',
					id:'password',
					afterLabelTextTpl: required,
                    allowBlank: false
                },
				{
                    xtype: 'textfield',					
                    fieldLabel: 'Retype Password',
					inputType:'password',
                    width: 250,
                    //name:'password2',
					id:'password2',
					afterLabelTextTpl: required,
                    allowBlank: false
                }

            ],
            buttons: [{
                    text: 'Save',
					id:'SaveSysID',
					//iconCls: 'btn-save',					
                    handler:this.onSaveData
                }]
            });
  },  
  windowFormCreate : function() {
		this.genaretformKaryawan();
		this.winTrx =   Ext.create('Ext.window.Window', {								
                header: {
                    titlePosition: 2,
                    titleAlign: 'center'
                },                
                //closeAction: 'hide',
                //width: 300,
                //minWidth: 300,
                //height: 550, 
				closable:false,
				layout: 'fit',
				constrainHeader:true,
                layout: {                    
                    padding: 4
                }
				,items:[this.formestimasiCatring]
				});
		Ext.getCmp(this.id_tab_main).add(this.winTrx);		
  },    
  
  onSaveData : function() {
		var form = xK.sys_cp.winTrx.down('form');
		var passw1 = form.getForm().findField('password').getValue();		
		var passw2 = form.getForm().findField('password2').getValue();		
		if (passw1 != passw2) {
			Ext.Msg.alert('Info!','Password 2 tidak sama!');
			return;
		}
		
		form.getForm().submit({
				method:'POST',				
				param:'&asu=fuck',
				url:'sys_cp/save',			
				clientValidation: true,    
				waitTitle:'Harap Tunggu',
				waitMsg:'Saving data...',
				success:function(opt,response)
				{
					var is_success = response.result.success;			
						if (is_success === true) {																						
							Ext.Msg.alert('Info!', response.result.msg, function() { 
								form.getForm().reset();
							});	 
						} else {
							Ext.Msg.alert('Failed!', response.result.msg);	
						}
				},
				failure:function(form, action)
				{									
					if(action.failureType == 'server')
					{
						var jsonData = Ext.JSON.decode(action.response.responseText);
						Ext.Msg.alert('Failed!', jsonData.msg);										
					}
					else
					{
						Ext.Msg.alert('Warning!', 'Server is unreachable : FORM NULL');
					}										
				}
		});
	
  
		
  },  
  doLayoutPanel : function(){     	      
	 this.windowFormCreate();
	 this.winTrx.show();
  }
  
  

}

xK.sys_cp.init();
});
  
</script>
