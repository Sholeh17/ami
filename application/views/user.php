<script>
var height_doc_body = Ext.getCmp('doc-body').getHeight();
xK.spj = {
  formUser : null,
  formRuleuser : null,
  gridUser : null,
  store_master_agent : null,
  init : function() {
      this.doLayoutPanel();
      msk.destroy();
  },
  get_combo_data_agent : function() {
        Ext.regModel('master_agent', {
            fields: [
                {type: 'string', name: 'kode_agen'},
				{type: 'string', name: 'level'},
                {type: 'string', name: 'nama_agen'}
            ]
        });
        var store_master_agent = Ext.create('Ext.data.Store', {
            model: 'master_agent',
            storeId: 'RemoteStates',
            proxy: {
                type: 'ajax',
                url: 'user/get_user_group_agent',
                reader: {
                    type: 'json',
                    root: 'datax',
                    totalProperty: 'totalCount'
                }
            }
        });

        return store_master_agent;
  },
  resetRuleMenu : function() {
                    Ext.getCmp('spj_new').reset();
                    Ext.getCmp('spj_change').reset();
                    Ext.getCmp('spj_delete').reset();
                    Ext.getCmp('spj_approve').reset();
                    Ext.getCmp('spj_print').reset();
					Ext.getCmp('spj_verify').reset();					
                    Ext.getCmp('spj_search').reset();
                    Ext.getCmp('spj_upload').reset();
                    Ext.getCmp('spj_download').reset();
                    Ext.getCmp('spj_sfa').reset();


                    Ext.getCmp('mu_new').reset();
                    Ext.getCmp('mu_edit').reset();
                    Ext.getCmp('mu_search').reset();


                    Ext.getCmp('agent_new').reset();
                    Ext.getCmp('agent_change').reset();
                    Ext.getCmp('agent_delete').reset();
                    Ext.getCmp('agent_search').reset();


                    Ext.getCmp('rspaj_search').reset();
                    Ext.getCmp('rspaj_print').reset();

                    Ext.getCmp('pr_search').reset();
                    Ext.getCmp('pr_print').reset();
					
					Ext.getCmp('pr_search_sheaffer').reset();
                    Ext.getCmp('pr_print_sheaffer').reset();
					
					
					Ext.getCmp('sfa_connect').reset();
  },
  get_level_user : function() {
        Ext.define('level_user', {
                extend: 'Ext.data.Model',
                fields: [      
                    {type: 'string', name: 'value'},
                    {type: 'string', name: 'name'}
                ]
            });
      
         var data_level_user = [
             {"value":"Prescreener","name":"Prescreener"},
             {"value":"Sekretaris","name":"Sekretaris"},
             {"value":"Leader","name":"Leader"},
             {"value":"Administrator","name":"Administrator"}
         ];
         var storelevel_user = Ext.create('Ext.data.Store', {
                autoDestroy: true,
                model: 'level_user',
                data: data_level_user
        });
        return storelevel_user;

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
                    idProperty: 'id_m_agen',
                    totalProperty: 'total'
                }
            },
            remoteSort: true,
            pageSize: cnt_page
        });

    return storelib;
  },
  getModelUser : function() {
     // register model
    Ext.define('user_model', {
        extend: 'Ext.data.Model',
        idProperty: 'company',
        fields: [
           {name: 'id_seq'},
           {name: 'password'},
           {name: 'login_date'},
           {name: 'user_id'},
           {name: 'level'},
		   {name: 'group_user'},
           {name: 'count_login'},
           {name: 'nama_user'},
           {name: 'hp'},
           {name: 'email'},
           {name: 'active'}
        ]
    });

    var stored_data = this.getStoredData('user_model','user/get_data_users','user_model','20');
    return stored_data;
  },

  getGridListUser : function() {
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
        var storeMdl = this.getModelUser();
        storeMdl.load();
        var searchField = Ext.create('Ext.ux.form.SearchField',{
    	store: storeMdl, text: 'Search', width:300});
        this.gridUser = Ext.widget('gridpanel', {
            store: storeMdl,            
            loadMask: true,
            id:'grid_user',
			region:'north',
			border:false,
			split:true,            
            margins:'2 2 2 2',
            tbar:[searchField],
            features: [filterfeature],                
            columns: [{
                    text: 'User',
                    sortable: true,
                    dataIndex: 'user_id',
                    filterable: true,
                    filter: {
                    type: 'string'
                        // specify disabled to disable the filter menu
                        //, disabled: true
                    },
                    width: 200
                },{
                    text: 'Name User',
                    sortable: true,
                    dataIndex: 'nama_user',
                    filterable: true,
                    filter: {
                    type: 'string'
                        // specify disabled to disable the filter menu
                        //, disabled: true
                    },
                    width: 200
                },{
                    text: 'Level',
                    sortable: true,
                    dataIndex: 'level',
                    filterable: true,
                    filter: {
                    type: 'string'
                        // specify disabled to disable the filter menu
                        //, disabled: true
                    },
                    width: 200
                },{
                    text: 'Aktif?',
                    sortable: true,
                    renderer : function(v) {
                        if (v == "1")
                            return "Active";
                        return "Non Active";
                    },
                    dataIndex: 'active',
                    width: 150
                }],
            stripeRows: true,
            listeners: {
                itemclick: function(v, record, html_item, index){
                   var r = record;                   
                   Ext.getCmp('form_user').getForm().loadRecord(r);
                   //Ext.getCmp('password2').setValue(r.data.password);
                   var group_user_json = Ext.JSON.decode(r.data.group_user);
                   Ext.getCmp('group_user').setValue(group_user_json);
                   for(var g in group_user_json) {
                       var jgr = group_user_json[g];
                       console.log(jgr);
                   }                   
                   Ext.getCmp('password').setValue('');
                   Ext.getCmp('password2').setValue('');
                   var userid = r.data.user_id;
                   Ext.Ajax.request({
                          url : 'user/get_data_rules_menu/'+userid,
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json' },                          
                          success: function (response) {
								 xK.spj.resetRuleMenu();	
                                 var jsonResp = Ext.JSON.decode(response.responseText);
                                 try{
                                         for(var k in jsonResp) {
                                           //console.log(k, jsonResp[k]);
                                           var OBJjSON = jsonResp[k];
                                           switch (OBJjSON.controller) {
                                               case 'spj_proposal' :
                                                   var checkbox2 = Ext.getCmp('spj_new'),
                                                        checkbox3 = Ext.getCmp('spj_change'),
                                                        checkbox4 = Ext.getCmp('spj_delete'),
                                                        checkbox5 = Ext.getCmp('spj_approve'),
                                                        checkbox6 = Ext.getCmp('spj_print'),
                                                        checkbox7 = Ext.getCmp('spj_search'),
                                                        checkbox8 = Ext.getCmp('spj_upload'),
                                                        checkbox9 = Ext.getCmp('spj_download'),
														checkbox11 = Ext.getCmp('spj_verify'),
                                                        checkbox10 = Ext.getCmp('spj_sfa');

                                                    checkbox2.setValue(OBJjSON.access_new);
                                                    checkbox3.setValue(OBJjSON.access_change);
                                                    checkbox4.setValue(OBJjSON.access_delete);
                                                    checkbox5.setValue(OBJjSON.access_approve);
                                                    checkbox6.setValue(OBJjSON.access_print);
                                                    checkbox7.setValue(OBJjSON.access_search);
                                                    checkbox8.setValue(OBJjSON.access_upload);
                                                    checkbox9.setValue(OBJjSON.access_download);
                                                    checkbox10.setValue(OBJjSON.access_sync);
													checkbox11.setValue(OBJjSON.access_verify);
                                                   break;

                                               case 'produk_spaj' :
                                                   var checkbox1 = Ext.getCmp('mu_new'),
                                                        checkbox2 = Ext.getCmp('mu_edit'),
                                                        checkbox3 = Ext.getCmp('mu_search');

                                                    checkbox1.setValue(OBJjSON.access_new);
                                                    checkbox2.setValue(OBJjSON.access_change);
                                                    checkbox3.setValue(OBJjSON.access_search);

                                                   break;

                                               case 'agent' :
                                                    var checkbox1 = Ext.getCmp('agent_new'),
                                                    checkbox2 = Ext.getCmp('agent_change'),
                                                    checkbox3 = Ext.getCmp('agent_delete'),
                                                    checkbox4 = Ext.getCmp('agent_search');

                                                    checkbox1.setValue(OBJjSON.access_new);
                                                    checkbox2.setValue(OBJjSON.access_change);
                                                    checkbox3.setValue(OBJjSON.access_delete);
                                                    checkbox4.setValue(OBJjSON.access_search);

                                                   break;

                                               case 'report_spaj' :
                                                        var checkbox1 = Ext.getCmp('rspaj_search'),
                                                            checkbox2 = Ext.getCmp('rspaj_print');
                                                        checkbox1.setValue(OBJjSON.access_search);
                                                        checkbox2.setValue(OBJjSON.access_print);
                                                   break;

                                               case 'report_spaj_contest' :
                                                        var checkbox1 = Ext.getCmp('pr_search'),
                                                            checkbox2 = Ext.getCmp('pr_print');

                                                        checkbox1.setValue(OBJjSON.access_search);
                                                        checkbox2.setValue(OBJjSON.access_print);
                                                   break;
												   
												   
											   case 'report_spaj_sheaffer' :
                                                        var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
                                                            checkbox2 = Ext.getCmp('pr_print_sheaffer');

                                                        checkbox1.setValue(OBJjSON.access_search);
                                                        checkbox2.setValue(OBJjSON.access_print);
                                                   break;	   
											   case 'sfa_connect' :
                                                        var checkbox1 = Ext.getCmp('sfa_connect');
                                                        checkbox1.setValue(OBJjSON.access_sync);                                                        
                                                   break;	   
                                           }

                                         }
                                 }catch(e){}
                                 
                               },
                          failure: function (response) {
                              var jsonResp = Ext.JSON.decode(response.responseText);
                              Ext.Msg.alert("Error",jsonResp.error);
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
                plugins: Ext.create('Ext.ux.ProgressBarPager', {width:150})
            })
        });
  },
  fromRuleMenu : function() {
           var menu_trx = {
            xtype: 'container',
            layout: 'hbox',            
            items: [{
                xtype: 'fieldset',
                flex: 1,
                margins:'5 5 5 5',
                title: 'Transaksi SPAJ',
                defaultType: 'checkbox', // each item will be a checkbox
                layout: 'anchor',                
                width:100,
                defaults: {
                    anchor: '100%',
                    hideEmptyLabel: false,
                    labelWidth: 10
                },
                items: [{
                    boxLabel: 'New',
                    name: 'spj_new',
                    id: 'spj_new',
                    inputValue: '1'
                }, {
                    boxLabel: 'Change',
                    name: 'spj_change',
                    id: 'spj_change',
                    inputValue: '1'
                }, {
                    boxLabel: 'Delete',
                    name: 'spj_delete',
					hidden:true,
                    id: 'spj_delete',
                    inputValue: '1'
                }, {
                    boxLabel: 'Verify Change',
                    name: 'spj_verify',					
                    id: 'spj_verify',
                    inputValue: '1'
                }, {
                    boxLabel: 'Approve',
                    name: 'spj_approve',
                    id: 'spj_approve',
                    inputValue: '1'
                }, {
                    boxLabel: 'Print',
                    name: 'spj_print',
                    id: 'spj_print',
					hidden:true,
                    inputValue: '1'
                }, {
                    boxLabel: 'Search',
                    name: 'spj_search',
                    id: 'spj_search',
                    inputValue: '1'
                }, {
                    boxLabel: 'Upload',
                    name: 'spj_upload',
                    id: 'spj_upload',
                    inputValue: '1'
                }, {
                    boxLabel: 'Downlaod',
                    name: 'spj_download',
                    id: 'spj_download',
                    inputValue: '1'
                }, {
                    boxLabel: 'Sync SFA',
                    name: 'spj_sfa',
                    id: 'spj_sfa',
					hidden:true,
                    inputValue: '1'
                },
                {
                    text: 'Select All',
                    xtype: 'button',
                    handler: function() {                        
                    var checkbox2 = Ext.getCmp('spj_new'),
                            checkbox3 = Ext.getCmp('spj_change'),
                            checkbox4 = Ext.getCmp('spj_delete'),
                            checkbox5 = Ext.getCmp('spj_approve'),
                            checkbox6 = Ext.getCmp('spj_print'),
                            checkbox7 = Ext.getCmp('spj_search'),
                            checkbox8 = Ext.getCmp('spj_upload'),
                            checkbox9 = Ext.getCmp('spj_download'),
                            checkbox10 = Ext.getCmp('spj_sfa'),
							checkbox11 = Ext.getCmp('spj_verify');

                        
                        checkbox2.setValue(true);
                        checkbox3.setValue(true);
                        checkbox4.setValue(true);
                        checkbox5.setValue(true);
                        checkbox6.setValue(true);
                        checkbox7.setValue(true);
                        checkbox8.setValue(true);
                        checkbox9.setValue(true);
                        checkbox10.setValue(true);
						checkbox11.setValue(true);

                    }
                },{
                            text: 'UnSelect',
                            xtype: 'button',
                            handler: function() {

                                var checkbox2 = Ext.getCmp('spj_new'),
                                    checkbox3 = Ext.getCmp('spj_change'),
                                    checkbox4 = Ext.getCmp('spj_delete'),
                                    checkbox5 = Ext.getCmp('spj_approve'),
                                    checkbox6 = Ext.getCmp('spj_print'),
                                    checkbox7 = Ext.getCmp('spj_search'),
                                    checkbox8 = Ext.getCmp('spj_upload'),
                                    checkbox9 = Ext.getCmp('spj_download'),
                                    checkbox10 = Ext.getCmp('spj_sfa'),
									checkbox11 = Ext.getCmp('spj_verify');

                                checkbox2.setValue(false);
                                checkbox3.setValue(false);
                                checkbox4.setValue(false);
                                checkbox5.setValue(false);
                                checkbox6.setValue(false);
                                checkbox7.setValue(false);
                                checkbox8.setValue(false);
                                checkbox9.setValue(false);
                                checkbox10.setValue(false);
								checkbox11.setValue(false);
                            }
                }, {
                    xtype: 'component',
                    margins:'5 5 5 5',
                    width: 10
                },
                {
                xtype: 'fieldset',
                flex: 1,
                bodyPadding: 10,
                title: 'MASTER PRODUCT',
                defaultType: 'checkbox',
				hidden:false,
                layout: 'anchor',
                defaults: {
                    anchor: '100%',
                    hideEmptyLabel: false,
                    labelWidth: 10
                },
                items: [ {
                    boxLabel: 'New',
                    name: 'mu_new',
                    id: 'mu_new',
                    inputValue: '1'
                }, {                    
                    boxLabel: 'Change',
                    name: 'mu_edit',
                    id: 'mu_edit',
                    inputValue: '1'
                },{

                    boxLabel: 'Search',
                    name: 'mu_search',
                    id: 'mu_search',
                    inputValue: '1'
                },
                {
                    text: 'Select All',
                    xtype: 'button',
                    handler: function() {
                        var checkbox1 = Ext.getCmp('mu_new'),
                            checkbox2 = Ext.getCmp('mu_edit'),
                            checkbox3 = Ext.getCmp('mu_search');

                        checkbox1.setValue(true);
                        checkbox2.setValue(true);
                        checkbox3.setValue(true);
                    }
                },{
                            text: 'UnSelect',
                            xtype: 'button',
                            handler: function() {
                                var checkbox1 = Ext.getCmp('mu_new'),
                                    checkbox2 = Ext.getCmp('mu_edit'),
                                    checkbox3 = Ext.getCmp('mu_search');

                                checkbox1.setValue(false);
                                checkbox2.setValue(false);
                                checkbox3.setValue(false);
                            }
                }]
            }

                ]
            }, {
                xtype: 'component',
                width: 10
            }, {
                xtype: 'fieldset',
                flex: 1,
                title: 'REGISTRASI',
                defaultType: 'checkbox', // each item will be a radio button
                layout: 'anchor',
                margins:'5 5 5 5',
                defaults: {
                    anchor: '100%',
                    hideEmptyLabel: false,
                    labelWidth: 10
                },
                items: [
                        {
                        xtype: 'fieldset',
                        flex: 1,
                        title: 'AGENT ',
                        defaultType: 'checkbox', // each item will be a radio button
                        layout: 'anchor',
                        margins:'5 5 5 5',
                        defaults: {
                            anchor: '100%',
                            hideEmptyLabel: false,
                            labelWidth: 10
                        },
                        items: [
                                {
                                    boxLabel: 'New',
                                    name: 'agent_new',
                                    id: 'agent_new',
                                    inputValue: '1'
                                }, {
                                    boxLabel: 'Change',
                                    name: 'agent_change',
                                    id: 'agent_change',
                                    inputValue: 'blue'
                                }, {
                                    boxLabel: 'Delete',
                                    name: 'agent_delete',
                                    id: 'agent_delete',
                                    inputValue: '1'
                                },{

                                    boxLabel: 'Search',
                                    name: 'agent_search',
                                    id: 'agent_search',
                                    inputValue: '1'
                                },
                                {
                                    text: 'Select All',
                                    xtype: 'button',
                                    handler: function() {
                                        var checkbox1 = Ext.getCmp('agent_new'),
                                            checkbox2 = Ext.getCmp('agent_change'),
                                            checkbox3 = Ext.getCmp('agent_delete'),
                                            checkbox4 = Ext.getCmp('agent_search');

                                        checkbox1.setValue(true);
                                        checkbox2.setValue(true);
                                        checkbox3.setValue(true);
                                        checkbox4.setValue(true);
                                    }
                                },{
                                            text: 'UnSelect',
                                            xtype: 'button',
                                            handler: function() {
                                                var checkbox1 = Ext.getCmp('agent_new'),
                                                    checkbox2 = Ext.getCmp('agent_change'),
                                                    checkbox3 = Ext.getCmp('agent_delete'),
                                                    checkbox4 = Ext.getCmp('agent_search');

                                                checkbox1.setValue(false);
                                                checkbox2.setValue(false);
                                                checkbox3.setValue(false);
                                                checkbox4.setValue(false);
                                            }
                                },
								{
									xtype: 'fieldset',
									flex: 1,									
									title: 'SYNC SFA',
									defaultType: 'checkbox',									
									layout: 'anchor',									
									defaults: {
										anchor: '100%',
										hideEmptyLabel: false,
										labelWidth: 10
										//style   :'background-color:#DFE8F6'
									},
									items: [ {
										boxLabel: 'Connect SFA',
										name: 'sfa_connect',
										id: 'sfa_connect',
										inputValue: '1'
									},
									{
										text: 'Select All',
										xtype: 'button',
										handler: function() {
											var checkbox1 = Ext.getCmp('sfa_connect');

											checkbox1.setValue(true);
										}
									},{
												text: 'UnSelect',
												xtype: 'button',
												handler: function() {
													var checkbox1 = Ext.getCmp('sfa_connect');

													checkbox1.setValue(false);
												}
									}]
								}
                            ]
                        }
                        
                    ]
            },
			 {
                xtype: 'component',
                width: 10
            }]
        };

        var menu_report = {
            xtype: 'container',
            layout: 'hbox',
            items: [ {
                xtype: 'fieldset',
                flex: 1,
                title: 'Report',
                defaultType: 'checkbox', // each item will be a radio button
                layout: 'anchor',
                margins:'5 5 5 5',
                defaults: {
                    anchor: '100%',
                    hideEmptyLabel: false,
                    labelWidth: 10
                },
                items: [
                         {
                            xtype: 'fieldset',
                            flex: 1,
                            title: 'SPAJ ',
                            defaultType: 'checkbox', // each item will be a radio button
                            layout: 'anchor',
                            margins:'5 5 5 5',
                            defaults: {
                                anchor: '100%',
                                hideEmptyLabel: false,
                                labelWidth: 10
                            },
                             items: [   {
                                            boxLabel: 'Search',
                                            name: 'rspaj_search',
                                            id: 'rspaj_search',
                                            inputValue: '1'
                                        }, {
                                            boxLabel: 'Print',
                                            name: 'rspaj_print',
                                            id: 'rspaj_print',
                                            inputValue: '1'
                                        },
                                        {
                                            text: 'Select All',
                                            xtype: 'button',
                                            handler: function() {
                                                var checkbox1 = Ext.getCmp('rspaj_search'),
                                                    checkbox2 = Ext.getCmp('rspaj_print');
                                                    //checkbox3 = Ext.getCmp('user_delete');

                                                checkbox1.setValue(true);
                                                checkbox2.setValue(true);
                                                //checkbox3.setValue(true);
                                            }
                                        },{
                                                    text: 'UnSelect',
                                                    xtype: 'button',
                                                    handler: function() {
                                                        var checkbox1 = Ext.getCmp('rspaj_search'),
                                                            checkbox2 = Ext.getCmp('rspaj_print');
                                                            //checkbox3 = Ext.getCmp('user_delete');

                                                        checkbox1.setValue(false);
                                                        checkbox2.setValue(false);
                                                        //checkbox3.setValue(false);
                                                    }
                                        }
                                    ]
                        },

                        {
                            xtype: 'fieldset',
                            flex: 1,
                            title: 'Report Contest',
                            hidden:false,
                            defaultType: 'checkbox', // each item will be a radio button
                            layout: 'anchor',
                            margins:'5 5 5 5',
                            defaults: {
                                anchor: '100%',
                                hideEmptyLabel: false,
                                labelWidth: 10
                            },
                             items: [   {
                                            boxLabel: 'Search',
                                            name: 'pr_search',
                                            id: 'pr_search',
                                            inputValue: '1'
                                        }, {
                                            boxLabel: 'Print',
                                            name: 'pr_print',
                                            id: 'pr_print',
                                            inputValue: '1'
                                        },
                                        {
                                            text: 'Select All',
                                            xtype: 'button',
                                            handler: function() {
                                                var checkbox1 = Ext.getCmp('pr_search'),
                                                    checkbox2 = Ext.getCmp('pr_print');

                                                checkbox1.setValue(true);
                                                checkbox2.setValue(true);
                                                
                                            }
                                        },{
                                                    text: 'UnSelect',
                                                    xtype: 'button',
                                                    handler: function() {
                                                        var checkbox1 = Ext.getCmp('pr_search'),
                                                            checkbox2 = Ext.getCmp('pr_print');

                                                        checkbox1.setValue(false);
                                                        checkbox2.setValue(false);
                                                    }
                                        }
                                    ]
                        },{
                            xtype: 'fieldset',
                            flex: 1,
                            title: 'Report Sheaffer',
                            hidden:false,
                            defaultType: 'checkbox', // each item will be a radio button
                            layout: 'anchor',
                            margins:'5 5 5 5',
                            defaults: {
                                anchor: '100%',
                                hideEmptyLabel: false,
                                labelWidth: 10
                            },
                             items: [   {
                                            boxLabel: 'Search',
                                            name: 'pr_search_sheaffer',
                                            id: 'pr_search_sheaffer',
                                            inputValue: '1'
                                        }, {
                                            boxLabel: 'Print',
                                            name: 'pr_print_sheaffer',
                                            id: 'pr_print_sheaffer',
                                            inputValue: '1'
                                        },
                                        {
                                            text: 'Select All',
                                            xtype: 'button',
                                            handler: function() {
                                                var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
                                                    checkbox2 = Ext.getCmp('pr_print_sheaffer');

                                                checkbox1.setValue(true);
                                                checkbox2.setValue(true);
                                                
                                            }
                                        },{
                                                    text: 'UnSelect',
                                                    xtype: 'button',
                                                    handler: function() {
                                                        var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
                                                            checkbox2 = Ext.getCmp('pr_print_sheaffer');

                                                        checkbox1.setValue(false);
                                                        checkbox2.setValue(false);
                                                    }
                                        }
                                    ]
                        }

                    ]
            }]
        };


        this.formRuleuser = Ext.create('Ext.FormPanel', {
            region:'center',            
            id:'form_rule',
            autoScroll:true,
            title:'Rule Menu',            
            split: true,            
            items:[menu_trx,menu_report]
        });

  },

  genaretformUser : function() {      
      var storelevel_user = this.get_level_user();
      var storeGroupAgen = this.get_combo_data_agent();
      var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';

      this.getGridListUser();

      this.formUser = Ext.create('Ext.form.Panel', {
                region 	: 'west',
                width:700,
                url: 'user/save_user',
                //autoScroll:true,
                waitMsgTarget: true,
                id:'form_user',
                split: true,
                margins:'1 1 1 1',
				 layout: {
                    type: 'border',
                    padding: 5
                },
                //bodyPadding: 10,
                fieldDefaults: {
                    labelAlign: 'top',
                    labelWidth: 100                    
                },
                items: [
				this.gridUser,				
				{
					region: 'north',										
					border:false,
					height:190,
					split:true,
					autoScroll:true,
					//bodyPadding: 10,
					items:[
								{
									xtype: 'fieldcontainer',                    
									fieldLabel: '<font color="red">NEW USER</font>',
									labelSeparator : '',
									labelStyle: 'font-weight:bold;padding:10',
									fieldDefaults: {
										labelAlign: 'top'
									}
								},
								{
									xtype: 'hidden',
									name:'id_seq',
									id:'id_seq'
								},
								{
										xtype:'fieldset',
										title: 'INFO USER',
										margins:'1 1 1 1',
										width: 600,
										layout: 'anchor',
										defaults: {
											anchor: '100%'
										},
										items:[
											{
												xtype: 'textfield',
												fieldLabel: 'Nama',
												width: 600,
												name:'nama_user',
												afterLabelTextTpl: required,
												allowBlank: false
											},
											{
												xtype: 'textfield',
												fieldLabel: 'Mobilie',
												width: 600,
												name:'hp',                                
												allowBlank: true
											},
											{
												xtype: 'textfield',
												fieldLabel: 'Email',
												width: 600,
												name:'email',
												allowBlank: true
											}

										]
								},
								{
									xtype: 'textfield',
									fieldLabel: 'UserID',
									width: 600,
									name:'user_id',
									id:'user_id',
									afterLabelTextTpl: required,
									allowBlank: false
								},{
									xtype: 'textfield',
									fieldLabel: 'Password',
									width: 600,
									inputType:'password',
									name:'password',
									id:'password',
									//afterLabelTextTpl: required,
									allowBlank: true
								},{
									xtype: 'textfield',
									fieldLabel: 'Ulangi Password',
									width: 600,
									inputType:'password',
									name:'password2',
									id:'password2'
								},
								{
									xtype: 'combo',
									fieldLabel: 'Level',
									name:'level',
									id:'level',
									displayField: 'name',
									afterLabelTextTpl: required,
									allowBlank: false,
									valueField:'value',
									listeners: {
									  render : function(){                          
									  },
									  select : function(o) {
										 
									  }
									},
									width: 600,
									store: storelevel_user,
									queryMode: 'local'
								},{
									xtype: 'boxselect',
									fieldLabel: 'Group User',
									name:'group_user[]',
                                                                        id : 'group_user',
									//fieldStyle:'text-transform:uppercase; color:#000000; font-weight:bold;',
									displayField: 'nama_agen',
									valueField:'kode_agen',		
									listConfig: {
															getInnerTpl: function() {
																return '<div data-qtip="{nama_agen}. {level}">{nama_agen} ({level})</div>';
															}
									},					
									listeners: {
									  render : function(){
										  Ext.getCmp('group_user').store.load();						  
									  },
									  select : function(o,r,i){ 							
											
									  }
									},
									width: 600,
									store: storeGroupAgen,
									queryMode: 'local',
									afterLabelTextTpl: required,
									allowBlank: false
								}, {
									xtype: 'checkbox',
									checked: true,
									inputValue: '1',
									name:'active',
									boxLabel: 'Active'
								}
							]
				}
            ],
            buttons: [{
                    text: 'Reset',
					iconCls:'btn-refresh',
                    handler: function() {
                        this.up('form').getForm().reset();                        
                        xK.spj.resetRuleMenu();
                    }
                }, {
                    text: 'Save',  
					iconCls: 'btn-save',	
                    handler: function() {                         
                        var form = this.up('form').getForm();
                        
                        var acces_rule = "&data[spj_proposal][access_new]="+Ext.getCmp('spj_new').getValue();
                            acces_rule += "&data[spj_proposal][access_change]="+Ext.getCmp('spj_change').getValue();
                            acces_rule += "&data[spj_proposal][access_delete]="+Ext.getCmp('spj_delete').getValue();
                            acces_rule += "&data[spj_proposal][access_print]="+Ext.getCmp('spj_print').getValue();
                            acces_rule += "&data[spj_proposal][access_search]="+Ext.getCmp('spj_search').getValue();
                            acces_rule += "&data[spj_proposal][access_download]="+Ext.getCmp('spj_download').getValue();
                            acces_rule += "&data[spj_proposal][access_upload]="+Ext.getCmp('spj_upload').getValue();
                            acces_rule += "&data[spj_proposal][access_sync]="+Ext.getCmp('spj_sfa').getValue();
                            acces_rule += "&data[spj_proposal][access_approve]="+Ext.getCmp('spj_approve').getValue();
							acces_rule += "&data[spj_proposal][access_verify]="+Ext.getCmp('spj_verify').getValue();


                            acces_rule += "&data[produk_spaj][access_new]="+Ext.getCmp('mu_new').getValue();
                            acces_rule += "&data[produk_spaj][access_change]="+Ext.getCmp('mu_edit').getValue();
                            acces_rule += "&data[produk_spaj][access_search]="+Ext.getCmp('mu_search').getValue();


                            acces_rule += "&data[agent][access_new]="+Ext.getCmp('agent_new').getValue();
                            acces_rule += "&data[agent][access_change]="+Ext.getCmp('agent_change').getValue();
                            acces_rule += "&data[agent][access_delete]="+Ext.getCmp('agent_delete').getValue();
                            acces_rule += "&data[agent][access_search]="+Ext.getCmp('agent_search').getValue();


                            acces_rule += "&data[report_spaj][access_search]="+Ext.getCmp('rspaj_search').getValue();
                            acces_rule += "&data[report_spaj][access_print]="+Ext.getCmp('rspaj_print').getValue();


                            acces_rule += "&data[report_spaj_contest][access_search]="+Ext.getCmp('pr_search').getValue();
                            acces_rule += "&data[report_spaj_contest][access_print]="+Ext.getCmp('pr_print').getValue();
							
							acces_rule += "&data[report_spaj_sheaffer][access_search]="+Ext.getCmp('pr_search_sheaffer').getValue();
                            acces_rule += "&data[report_spaj_sheaffer][access_print]="+Ext.getCmp('pr_print_sheaffer').getValue();
							
							
							acces_rule += "&data[sfa_connect][access_sync]="+Ext.getCmp('sfa_connect').getValue();
	
                        

                        if (form.isValid()) {
                            if (form.findField('password').getValue() != form.findField('password2').getValue()) {
                                Ext.example.msg('Erorr!!', "Password tidak sama.!!!", 10);
                                return;
                            }
                                form.submit({
                                    clientValidation: true,
                                    params:acces_rule,
                                    waitMsg:'Waiting, proccess saving....!!',
                                    success: function(result,opt) {
                                       Ext.example.msg('Info', opt.result.msg, 10000);
                                       Ext.getCmp('grid_user').store.load();
                                       form.reset();                                      
                                       xK.spj.resetRuleMenu();
                                    },
                                    failure: function(result,opt) {
                                        Ext.example.msg('Erorr!!', opt.result.msg, 8000);
                                    }
                                });                        
                        }else {
                                Ext.example.msg('Erorr!!', opt.result.msg);
                        }
                    }
                }]
            });
  },

  template_rule : function(group) {
            switch (group) {
             case 'Leader' :
                   var checkbox2 = Ext.getCmp('spj_new'),
                    checkbox3 = Ext.getCmp('spj_change'),
                    checkbox4 = Ext.getCmp('spj_delete'),
                    checkbox5 = Ext.getCmp('spj_approve'),
                    checkbox6 = Ext.getCmp('spj_print'),
                    checkbox7 = Ext.getCmp('spj_search'),
                    checkbox8 = Ext.getCmp('spj_upload'),
                    checkbox9 = Ext.getCmp('spj_download'),														checkbox11 = Ext.getCmp('spj_verify'),
                    checkbox10 = Ext.getCmp('spj_sfa'),
					checkboxsfa = Ext.getCmp('sfa_connect');
                    checkbox2.setValue(0);
                    checkbox3.setValue(0);
                    checkbox4.setValue(0);
                    checkbox5.setValue(1);
                    checkbox6.setValue(1);
                    checkbox7.setValue(1);
                    checkbox8.setValue(0);
                    checkbox9.setValue(1);
                    checkbox10.setValue(1);
					checkboxsfa.setValue(0);
                    

                    var checkbox1 = Ext.getCmp('mu_new'),
                        checkbox2 = Ext.getCmp('mu_edit'),
                        checkbox3 = Ext.getCmp('mu_search');
                        checkbox1.setValue(1);
                        checkbox2.setValue(1);
                        checkbox3.setValue(1);

                                                   

				   //case 'agent' :
					var checkbox1 = Ext.getCmp('agent_new'),
					checkbox2 = Ext.getCmp('agent_change'),
					checkbox3 = Ext.getCmp('agent_delete'),
					checkbox4 = Ext.getCmp('agent_search');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					checkbox3.setValue(1);
					checkbox4.setValue(1);

				   //case 'report_spaj' :
					var checkbox1 = Ext.getCmp('rspaj_search'),
						checkbox2 = Ext.getCmp('rspaj_print');
						checkbox1.setValue(1);
						checkbox2.setValue(1);
                                               
				   //case 'report_contest' :
					var checkbox1 = Ext.getCmp('pr_search'),
						checkbox2 = Ext.getCmp('pr_print');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					
					//case 'report sheaffer' :
					var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
						checkbox2 = Ext.getCmp('pr_print_sheaffer');
					
					checkbox1.setValue(1);
					checkbox2.setValue(1);	
                  break;                  
				  
				case 'Sekretaris' :
                   var checkbox2 = Ext.getCmp('spj_new'),
                    checkbox3 = Ext.getCmp('spj_change'),
                    checkbox4 = Ext.getCmp('spj_delete'),
                    checkbox5 = Ext.getCmp('spj_approve'),
                    checkbox6 = Ext.getCmp('spj_print'),
                    checkbox7 = Ext.getCmp('spj_search'),
                    checkbox8 = Ext.getCmp('spj_upload'),
                    checkbox9 = Ext.getCmp('spj_download'),														
					checkbox11 = Ext.getCmp('spj_verify'),
                    checkbox10 = Ext.getCmp('spj_sfa'),
					checkboxsfa = Ext.getCmp('sfa_connect');
                    checkbox2.setValue(0);
                    checkbox3.setValue(1);
                    checkbox4.setValue(0);
                    checkbox5.setValue(1);
                    checkbox6.setValue(1);
                    checkbox7.setValue(1);
                    checkbox8.setValue(1);
                    checkbox9.setValue(1);
                    checkbox10.setValue(0);
					checkbox11.setValue(1);
					checkboxsfa.setValue(1);
                    

                    var checkbox1 = Ext.getCmp('mu_new'),
                        checkbox2 = Ext.getCmp('mu_edit'),
                        checkbox3 = Ext.getCmp('mu_search');
                        checkbox1.setValue(1);
                        checkbox2.setValue(1);
                        checkbox3.setValue(1);

                                                   

				   //case 'agent' :
					var checkbox1 = Ext.getCmp('agent_new'),
					checkbox2 = Ext.getCmp('agent_change'),
					checkbox3 = Ext.getCmp('agent_delete'),
					checkbox4 = Ext.getCmp('agent_search');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					checkbox3.setValue(1);
					checkbox4.setValue(1);

				   //case 'report_spaj' :
					var checkbox1 = Ext.getCmp('rspaj_search'),
						checkbox2 = Ext.getCmp('rspaj_print');
						checkbox1.setValue(1);
						checkbox2.setValue(1);
                                               
				   //case 'report_contest' :
					var checkbox1 = Ext.getCmp('pr_search'),
						checkbox2 = Ext.getCmp('pr_print');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					
					//case 'report sheaffer' :
					var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
						checkbox2 = Ext.getCmp('pr_print_sheaffer');
					
					checkbox1.setValue(1);
					checkbox2.setValue(1);
					
                  break;                  
				  
				  case 'Prescreener' :
                   var checkbox2 = Ext.getCmp('spj_new'),
                    checkbox3 = Ext.getCmp('spj_change'),
                    checkbox4 = Ext.getCmp('spj_delete'),
                    checkbox5 = Ext.getCmp('spj_approve'),
                    checkbox6 = Ext.getCmp('spj_print'),
                    checkbox7 = Ext.getCmp('spj_search'),
                    checkbox8 = Ext.getCmp('spj_upload'),
                    checkbox9 = Ext.getCmp('spj_download'),														checkbox11 = Ext.getCmp('spj_verify'),
                    checkbox10 = Ext.getCmp('spj_sfa'),
					checkboxsfa = Ext.getCmp('sfa_connect');
                    checkbox2.setValue(1);
                    checkbox3.setValue(1);
                    checkbox4.setValue(1);
                    checkbox5.setValue(0);
                    checkbox6.setValue(1);
                    checkbox7.setValue(1);
                    checkbox8.setValue(1);
                    checkbox9.setValue(1);
                    checkbox10.setValue(0);
					checkbox11.setValue(0);
                    checkboxsfa.setValue(0);

					//produk_spaj	
                    var checkbox1 = Ext.getCmp('mu_new'),
                        checkbox2 = Ext.getCmp('mu_edit'),
                        checkbox3 = Ext.getCmp('mu_search');
                        checkbox1.setValue(0);
                        checkbox2.setValue(0);
                        checkbox3.setValue(0);

                                                   

				   //case 'agent' :
					var checkbox1 = Ext.getCmp('agent_new'),
					checkbox2 = Ext.getCmp('agent_change'),
					checkbox3 = Ext.getCmp('agent_delete'),
					checkbox4 = Ext.getCmp('agent_search');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					checkbox3.setValue(1);
					checkbox4.setValue(1);

				   //case 'report_spaj' :
					var checkbox1 = Ext.getCmp('rspaj_search'),
						checkbox2 = Ext.getCmp('rspaj_print');
						checkbox1.setValue(1);
						checkbox2.setValue(1);
                                               
				   //case 'report_issued' :
					var checkbox1 = Ext.getCmp('pr_search'),
						checkbox2 = Ext.getCmp('pr_print');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
                  break;                  
				  
				  case 'Administrator' :
                   var checkbox2 = Ext.getCmp('spj_new'),
                    checkbox3 = Ext.getCmp('spj_change'),
                    checkbox4 = Ext.getCmp('spj_delete'),
                    checkbox5 = Ext.getCmp('spj_approve'),
                    checkbox6 = Ext.getCmp('spj_print'),
                    checkbox7 = Ext.getCmp('spj_search'),
                    checkbox8 = Ext.getCmp('spj_upload'),
                    checkbox9 = Ext.getCmp('spj_download'),														
					checkbox11 = Ext.getCmp('spj_verify'),
                    checkbox10 = Ext.getCmp('spj_sfa');
					checkboxsfa = Ext.getCmp('sfa_connect');
                    checkbox2.setValue(1);
                    checkbox3.setValue(1);
                    checkbox4.setValue(1);
                    checkbox5.setValue(1);
                    checkbox6.setValue(1);
                    checkbox7.setValue(1);
                    checkbox8.setValue(1);
                    checkbox9.setValue(1);
                    checkbox10.setValue(1);
					checkbox11.setValue(1);
                    checkboxsfa.setValue(1);

					//produk_spaj	
                    var checkbox1 = Ext.getCmp('mu_new'),
                        checkbox2 = Ext.getCmp('mu_edit'),
                        checkbox3 = Ext.getCmp('mu_search');
                        checkbox1.setValue(1);
                        checkbox2.setValue(1);
                        checkbox3.setValue(1);

                                                   

				   //case 'agent' :
					var checkbox1 = Ext.getCmp('agent_new'),
					checkbox2 = Ext.getCmp('agent_change'),
					checkbox3 = Ext.getCmp('agent_delete'),
					checkbox4 = Ext.getCmp('agent_search');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					checkbox3.setValue(1);
					checkbox4.setValue(1);

				   //case 'report_spaj' :
					var checkbox1 = Ext.getCmp('rspaj_search'),
						checkbox2 = Ext.getCmp('rspaj_print');
						checkbox1.setValue(1);
						checkbox2.setValue(1);
                                               
				   //case 'report_contest' :
					var checkbox1 = Ext.getCmp('pr_search'),
						checkbox2 = Ext.getCmp('pr_print');

					checkbox1.setValue(1);
					checkbox2.setValue(1);
					
					
					//case 'report sheaffer' :
					var checkbox1 = Ext.getCmp('pr_search_sheaffer'),
						checkbox2 = Ext.getCmp('pr_print_sheaffer');
					
					checkbox1.setValue(1);
					checkbox2.setValue(1);	
					
                  break; 
               }


  },
  doLayoutPanel : function(){
     this.genaretformUser();
     this.fromRuleMenu();
     var dynamicPanel =  Ext.create('Ext.panel.Panel', {                
                width: '100%',
                height: height_doc_body,
                border:false,
                layout : 'border',
                items:[
                                {
                                            region		: 'north',
                                            id 			: 'title',
                                            html : "<div style='height:30px; width: 2024px;display:table-cell;vertical-align:middle;' class='x-panel-header-default x-panel-header-text-default'>&nbsp;&nbsp;&nbsp;USER</div>",
                                            border		: false
				},this.formRuleuser
                                ,this.formUser
            ]
            });

            Ext.getCmp('doc-body').removeAll();
            Ext.getCmp('doc-body').update('');
            Ext.getCmp('doc-body').add(dynamicPanel);            
  }

}

xK.spj.init();

  
</script>