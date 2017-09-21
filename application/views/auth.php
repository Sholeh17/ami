<html>
<title>::.. ADVANCED METERING INFRASTRUCTURE ..::</title>
<head>
<!-- <x-bootstrap> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/include-ext.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/options-toolbar.js"></script> !-->
<!-- </x-bootstrap> -->

<!-- shared example code -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/examples.js"></script>

<!-- <script type='text/javascript'     src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>  !-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/engine/main.js"></script>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/resources/ext-theme-access/ext-theme-access-all.css"> !-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ccs_cloud.css" />
</head>

<body>



<script>
var url_base = "<?php echo base_url(); ?>";

Ext.require([
    'Ext.util.*',
    'Ext.tip.QuickTipManager',    
    'Ext.window.Window',
    'Ext.form.FieldSet',
    'Ext.window.MessageBox',
    'Ext.tip.*'
]);

Ext.onReady(function(){
Ext.tip.QuickTipManager.init();

var login = Ext.create('Ext.form.Panel',{
                labelWidth:120,
                url:'auth/submit/', 
                id:'lgn',
                region 	: 'center',
                padding:2,
                defaultType:'textfield',
                height:140,
                items:[{
                            xtype:'box', //buat naruh gambar icon
                            autoEl:{
                            tag:'img',
                            src:'<?php echo base_url();?>assets/images/user.gif'
                            }
                        },
                        {
                            fieldLabel:'Username', //buat ngisi username
                            name:'loginUsername',
                            blankText:'Tidak dapat kosong',
                            id:'loginUsername',
                            listeners : {
                                specialkey : function(o,e) {
                                                    if(e.keyCode == 13) {
                                                        Ext.getCmp('loginPassword').focus(true,10);
                                                    }
                                }
                            },
                            width:300,
                            allowBlank:false
                        },{
                            fieldLabel:'Password', // buat ngisi password
                            name:'loginPassword',
                            blankText:'Tidak dapat kosong',
                            id:'loginPassword',
                            width:300,
                            inputType:'password',
                            listeners : {
                                specialkey : function(o,e) {
                                                    if(e.keyCode == 13) {
                                                        login_submit();
                                                    }

                                }
                            },
                            allowBlank:false
                        }
                        ],
        buttons:[{
                    text:'Login',
                    id:'logPrcs',
                    handler:function(){
                            login_submit();
                        }
                    },
                    {
                        text: 'Reset',
                        handler: function(){
                        login.getForm().reset();
                    }

                }]
                });

var createwindow =  Ext.create('widget.window',{	
    title:'AMI - [LOGIN]',	
    width:360,
    height:190,
    maxHeight:210,
    maxWidth:360,
    id:'flgns',
    closable: false,
	layout: 'fit',
    items:login    
});
createwindow.show();
	

function login_submit()  {
    login.getForm().submit({
                        method:'POST',
                        clientValidation: true,     
                        waitTitle:'Harap Tunggu',
                        waitMsg:'Mengirim data...',
                                success:function(form,res)
                                {	
										location.href="main";
										return;
                                },
								failure:function(form, action)
								{
									//console.log(action);
									if(action.failureType == 'server')
									{

										Ext.Msg.alert('Login Failed!', action.result.errors.reason);
										Ext.getCmp('loginUsername').focus(true,10);
									}
									else
									{
										Ext.Msg.alert('Warning!', 'Authentication server is unreachable : FORM NULL');
										Ext.getCmp('loginUsername').focus(true,10);
									}
										login.getForm().reset();
								}
                                });
}


});


</script>

<div class="cloud x1"></div>
	<!-- Time for multiple clouds to dance around -->
	<div class="cloud x2"></div>
	<div class="cloud x3"></div>
	<div class="cloud x4"></div>
	<div class="cloud x5"></div>

</body>
</htm>