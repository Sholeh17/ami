/*! Ext JS 4 SpreadSheets - v1.0-beta1 - 2013-01-01
* http://www.extjs4spreadsheets.com/
* Copyright (c) 2013 Copyright (C) 2012, 2013 Aron Homberg; GPLv3 and commercially licensed. */
Ext.define("Spread.overrides.Column",{
			override:"Ext.grid.Column",
			selectable:!0,
			editable:!0,
			autoCommit:!0,
			cellwriter:null,
			cellreader:null,
			editModeStyling:!0,
			allowedEditKeys:[],
			initialPanelEditModeStyling:!1,
			initComponent:function(){
				this.initDynamicColumnTdCls(),
				this.callParent(arguments)
			},
			initDynamicColumnTdCls:function(){
				this.selectable||(this.editable=!1,this.tdCls="spreadsheet-cell-unselectable"),
				this.editable&&this.editModeStyling&&this.initialPanelEditModeStyling&&(this.tdCls+=" spreadsheet-cell-editable")
			}
}),
Ext.define("Spread.data.TSVTransformer",{
			singleton:!0,
			lineSeparator:"\n",
			columnSeparator:"	",
			transformToTSV:function(e){
				var t=-1,n="";
				for(var r=0;r<e.length;r++)
					e[r].update(),
					t!==e[r].row&&t!==-1&&(n=this.addLineBreak(n)),
					t=e[r].row,
					n=this.addValue(n,e[r]),
					e[r+1]&&e[r+1].column!==e[1].view.getSelectionModel().rootPosition.column&&(n=this.addTabulator(n));
					return n
			},
			transformToArray:function(e){
				var t=[],n=e.split(this.lineSeparator);
				for(var r=0;r<n.length-1;r++)
					t.push(n[r].split(this.columnSeparator));
				return t
			},
			addLineBreak:function(e){
				return e+this.lineSeparator
			},
			addTabulator:function(e){
				return e+this.columnSeparator
			},
			addValue:function(e,t){
				return e+=Spread.data.DataMatrix.getValueOfPosition(t)
			}}
		),
		Ext.define("Spread.data.DataMatrix",{
			singleton:!0,
			getFieldNameForColumnIndex:function(e,t){
				var n=e.getHeaderAtIndex(t);
				if(n)return n.dataIndex;
				throw"No column found for column index: "+t
			},
			setValueForPosition:function(e,t,n,r){
				e.update();
				var i=this.getFieldNameForColumnIndex(e.view,e.column);
				if(!e.record)throw"No record found for row index: "+e.row;
				e.columnHeader.cellwriter&&Ext.isFunction(e.columnHeader.cellwriter)&&(t=e.columnHeader.cellwriter(t,e));
				if(r){
					var s=e.record[e.record.persistenceProperty][i]=t;
					e.record.setDirty()
				}else 
					var s=e.record.set(i,t);
				return n&&e.columnHeader.autoCommit&&e.record.commit(),s
			},
			getValueOfPosition:function(e){
				e.update();
				var t=this.getFieldNameForColumnIndex(e.view,e.column),
				n=null;
				if(!e.record)throw"No record found for row index: "+e.row;
					return n=e.record.get(t),e.columnHeader.cellreader&&Ext.isFunction(e.columnHeader.cellreader)&&(n=e.columnHeader.cellreader(n,e)),n
			}
	}
),
Ext.define("Spread.selection.Position",{
	view:null,
	column:-1,
	row:-1,
	record:null,
	model:null,
	columnHeader:null,
	rowEl:null,
	cellEl:null,
	constructor:function(e,t,n,r,i,s){
		var o=e.getStore().getCount(),
		u=e.headerCt.getGridColumns(!0).length;
		t>=u&&(t=u-1),n>=o&&(n=o-1);
		var i=i||e.getNode(n),
			r=r||e.getStore().getAt(n),
			a=null;
			i?s=s||i.childNodes[t]:s=s||null,
			r&&(a=r.self),
			Ext.apply(this,{
				view:e,column:t,row:n,record:a,model:r.self,columnHeader:e.getHeaderAtIndex(t),rowEl:i,cellEl:s
			})
	},
	update:function(){
		return this.record=this.view.getStore().getAt(this.row),
			   this.record?this.model=this.record.self:this.model=null,
			   this.columnHeader=this.view.getHeaderAtIndex(this.column),
			   this.rowEl=this.view.getNode(this.row),
			   this.cellEl=this.rowEl.childNodes[this.column],this}}),
			   Ext.define("Spread.selection.RangeModel",{
					extend:"Ext.selection.Model",
					alias:"selection.range",
					isRangeModel:!0,
					initialViewRefresh:!0,
					keyNav:null,
					keyNavigation:!1,
					mayRangeSelecting:!1,
					rootPosition:null,
					autoFocusRootPosition:!0,
					enableKeyNav:!0,
					currentSelectionRange:[],
					originSelectionPosition:null,
					currentFocusPosition:null,
					view:null,
					grid:null,
					constructor:function(){
							this.addEvents("deselect","select","beforecellfocus","cellfocus","tabselect","enterselect","keynavigate"),
							this.callParent(arguments)
					},
					bindComponent:function(e){
							var t=this;
							t.view=e,
							t.grid=t.view.ownerCt,
							t.callParent(arguments),
							t.initRootPosition(),
							t.bindUIEvents(),
							t.enableKeyNav&&t.initKeyNav(e)},
							initRootPosition:function(){
								var e=0,t=!1,n=!1;
								while(!t)
									this.view.getHeaderAtIndex(e)||(t=!0,n=!0),
									this.view.getHeaderAtIndex(e)&&this.view.getHeaderAtIndex(e).selectable?t=!0:e++;
									this.rootPosition=new Spread.selection.Position(this.view,e,0,this.view.getStore().getAt(0))
							},
							bindUIEvents:function(){
								var e=this;
								e.view.on({uievent:e.onUIEvent,refresh:e.onViewRefresh,scope:e}),
								e.view.ownerCt.on({columnhide:e.reinitialize,columnmove:e.reinitialize,columnshow:e.reinitialize,scope:e}),
								e.view.store.on("datachanged",e.reinitialize,e)
							},
							reinitialize:function(){
								this.initRootPosition(),
								this.setCurrentFocusPosition(this.rootPosition),
								this.setOriginSelectionPosition(this.rootPosition)
							},
							initKeyNav:function(e){
								var t=this;
								if(!e.rendered){
									e.on("render",Ext.Function.bind(t.initKeyNav,t,[e],0),t,{single:!0});
									return
								}
								e.el.set({tabIndex:-1}),
								t.keyNav=new Ext.util.KeyNav({target:e.el,ignoreInputFields:!1,up:t.onKeyUp,down:t.onKeyDown,right:t.onKeyRight,left:t.onKeyLeft,tab:t.onKeyTab,enter:t.onKeyEnter,scope:t})
							},
							onViewRefresh:function(){
								this.rootPosition.update(),
								this.autoFocusRootPosition&&this.initialViewRefresh&&(this.setCurrentFocusPosition(this.rootPosition),
								this.setOriginSelectionPosition(this.rootPosition),
								this.initialViewRefresh=!1)
							},
							onUIEvent:function(e,t,n,r,i,s,o,u){
								var a=this,
								f=arguments;
								switch(e){
									case"mouseover":
										a.onCellMouseOver.apply(a,f);
									break;
									case"mousedown":
										a.onCellMouseDown.apply(a,f)
								}
								Ext.EventManager.on(document.body,"mouseup",this.onCellMouseUp,this,{buffer:50})
							},
							onCellMouseDown:function(e,t,n,r,i,s,o,u,a){
								if(!a)
									return;
									var f=new Spread.selection.Position(t,i,r,o,u,n);
									this.setCurrentFocusPosition(f)&&(s.shiftKey?this.tryToSelectRange():(this.setOriginSelectionPosition(f),this.mayRangeSelecting=!0))
							},
							getNextRowIndex:function(e,t){
								return t+1<e.getCount()&&++t,t
							},
							onCellMouseOver:function(e,t,n,r,i,s,o,u){
								this.mayRangeSelecting&&this.setCurrentFocusPosition(new Spread.selection.Position(t,i,r,o,u,n))&&this.tryToSelectRange()
							},
							onCellMouseUp:function(){
								this.mayRangeSelecting=!1
							},
							onKeyUp:function(e){
								if(!this.getCurrentFocusPosition())
									return;
								this.keyNavigation=!0,
								this.processKeyNavigation("up",e),
								this.keyNavigation=!1
							},
							onKeyDown:function(e){
								if(!this.getCurrentFocusPosition())
									return;
								this.keyNavigation=!0,
								this.processKeyNavigation("down",e),
								this.keyNavigation=!1
							},
							onKeyLeft:function(e){
								if(!this.getCurrentFocusPosition())
									return;this.
								keyNavigation=!0,
								this.processKeyNavigation("left",e),
								this.keyNavigation=!1
							},
							onKeyRight:function(e){
								if(!this.getCurrentFocusPosition())
									return;
								this.keyNavigation=!0,
								this.processKeyNavigation("right",e),
								this.keyNavigation=!1
							},
							onKeyTab:function(e){
								if(!this.getCurrentFocusPosition()||!e)
									return;
								this.fireEvent("tabselect",this,e),
								this.keyNavigation=!0,
								this.processKeyNavigation("right",e),
								this.keyNavigation=!1
							},
							onKeyEnter:function(e){
								if(!this.getCurrentFocusPosition())
									return;
								this.fireEvent("enterselect",this,e),
								this.keyNavigation=!0,
								this.processKeyNavigation("down",e),
								this.keyNavigation=!1
							},
							setCurrentFocusPosition:function(e){
								return e?e.columnHeader.selectable?this.fireEvent("beforecellfocus",e)!==!1?(this.currentFocusPosition=e,
										this.currentSelectionRange=[],
										this.view.coverCell(e),
										this.fireEvent("cellfocus",e),!0):!1:!1:(this.currentFocusPosition=null,!1)
							},
							getCurrentFocusPosition:function(){
								return this.currentFocusPosition
							},
							setOriginSelectionPosition:function(e){
								this.originSelectionPosition=e
							},
							getOriginSelectionPosition:function(){
								return this.originSelectionPosition
							},
							processKeyNavigation:function(e,t){
								this.fireEvent("keynavigate",this,e,t);
								var n=this.tryMoveToPosition(this.getCurrentFocusPosition(),e,t);
								this.setCurrentFocusPosition(n)&&(t.shiftKey?this.tryToSelectRange():this.setOriginSelectionPosition(n))
							},
							tryMoveToPosition:function(e,t,n){
								var r=this.view.walkCells(e,t,n,!0);
								return!r&&!n.shiftKey&&t==="right"?r=new Spread.selection.Position(e.view,this.rootPosition.column,this.getNextRowIndex(e.view.getStore(),e.row)):r||(r=e),new Spread.selection.Position(this.view,r.column,r.row)
							},
							tryToSelectRange:function(e){
								var t=function(e,t){
										var n=[];
										do 
											n.push(e),e++;
										while(e<=t);return n
									},
									n=this.view.getStore().getCount(),
									r=this.view.headerCt.getGridColumns(!0).length,
									i=this.getOriginSelectionPosition().row,
									s=this.getCurrentFocusPosition().row,
									o=this.getOriginSelectionPosition().column,
									u=this.getCurrentFocusPosition().column,
									a=[],
									f=[],
									l=[],
									c=null;
									s<=i?a=t(s,i):a=t(i,s),
									u<=o?f=t(u,o):f=t(o,u);
									for(var h=0;h<n;h++)
										for(var p=0;p<r;p++)
											Ext.Array.indexOf(a,h)>-1&&Ext.Array.indexOf(f,p)>-1&&(c=(new Spread.selection.Position(this.view,p,h)).update(),
											c.columnHeader.hidden||l.push(c));
											this.currentSelectionRange=l,e||this.view.highlightCells(l)
							},
							getSelectedPositionData:function(){
								var e;return this.currentSelectionRange.length===0?e=[this.currentFocusPosition]:e=this.currentSelectionRange,e
							}
				}
			),
				Ext.define("Spread.util.Clipping",{
					el:null,
					refocusDelay:150,
					initClipping:function(){
						var e=this;Ext.onReady(function(){e.hasClipboard()||e.createClipboard()})
					},
					prepareForClipboardCopy:function(e,t){
						var n=this;
						n.el.dom.style.display="block",
						n.el.dom.value=e,
						n.el.dom.focus(),
						n.el.dom.select(),
						n.refocusView(t)
					},
					prepareForClipboardPaste:function(e,t){
						var n=this;
						n.el.dom.style.display="block",
						n.el.dom.focus(),
						setTimeout(function(){
								e(n.el.dom.value),
								n.el.dom.value=""},150
						)
						,n.refocusView(t)
					},
					createClipboard:function(){this.el=Ext.get(Ext.DomHelper.append(Ext.getBody(),{tag:"textarea",cls:"clipboard-textarea",style:{display:"none",zIndex:-200,position:"absolute",left:"0px",top:"0px",width:"0px",height:"0px"},value:""}))},hasClipboard:function(){var e=Ext.select(".clipboard-textarea").elements[0];return e?(this.el=Ext.get(e),!0):!1},refocusView:function(e){var t=this;setTimeout(function(){e.getEl().focus(),t.el.dom.style.display="none"},t.refocusDelay)}}),
					Ext.define("Spread.grid.plugin.Copyable",{
						extend:"Ext.AbstractComponent",
						alias:"copyable",
						mixins:{clipping:"Spread.util.Clipping"},
						view:null,
						init:function(e){this.addEvents("beforecopy","copy"),
							this.initClipping();
							var t=this;t.view=e,this.initKeyNav(e)},initKeyNav:function(e){var t=this;if(!e.rendered){e.on("render",Ext.Function.bind(t.initKeyNav,t,[e],0),t,{single:!0});return}e.getEl().on("keydown",t.detectCopyKeyStroke,t)},detectCopyKeyStroke:function(e){e.getKey()===e.C&&e.ctrlKey&&this.copyToClipboard()},copyToClipboard:function(){var e=this.view.getSelectionModel(),t=e.getSelectedPositionData();this.fireEvent("beforecopy",this,e,t)!==!1&&(this.prepareForClipboardCopy(Spread.data.TSVTransformer.transformToTSV(t),this.view),this.fireEvent("copy",this,e,t))}}),Ext.define("Spread.grid.plugin.Editable",{extend:"Ext.AbstractComponent",alias:"editable",view:null,autoCommit:!0,stopEditingFocusDelay:50,retryFieldElFocusDelay:20,chunkRenderDelay:5,cellChunkSize:50,activePosition:null,activeCoverEl:null,activeCellTdEl:null,activeCoverElSize:null,activeCoverElPosition:null,cellCoverEditFieldEl:null,isEditing:!1,editModeStyling:!0,editableCellCls:"spreadsheet-cell-editable",editableDirtyCellCls:"spreadsheet-cell-editable-dirty",lastEditFieldValue:null,editableColumns:[],editableColumnIndexes:[],editable:!0,init:function(e){var t=this;t.view=e,this.addEvents("beforeeditfieldblur","editfieldblur","beforecoverdblclick","coverdblclick","beforecoverkeypressed","coverkeypressed","beforeeditingenabled","editingenabled","beforeeditingdisabled","editingdisabled"),this.initCoverEventing()},initCoverEventing:function(){var e=this;this.view.on("afterrender",function(t){e.initEditingColumns(t),e.initEventing(t)})},initEventing:function(e){var t=this,n=e.getCellCoverEl();if(!n)throw"Cover element not found, initializing editing failed! Please check proper view rendering.";t.initTextField(n),n.on("dblclick",t.onCoverDblClick,t),e.getEl().on("keydown",t.onCoverKeyPressed,t),e.on("covercell",t.onCellCovered,t),e.getSelectionModel().on("tabselect",t.blurEditFieldIfEditing,t),e.getSelectionModel().on("enterselect",t.blurEditFieldIfEditing,t),e.getSelectionModel().on("beforecellfocus",t.blurEditFieldIfEditing,t),e.getSelectionModel().on("keynavigate",t.blurEditFieldIfEditing,t)},initEditingColumns:function(e){var t=e.getHeaderCt().getGridColumns();this.editableColumns=[],this.editableColumnIndexes=[];for(var n=0;n<t.length;n++)t[n].editable&&(this.editableColumns.push(t[n]),t[n].columnIndex=n,this.editableColumnIndexes.push(n))},initTextField:function(e){this.cellCoverEditFieldEl||(this.cellCoverEditFieldEl=Ext.get(Ext.DomHelper.append(e,{tag:"input",type:"text",cls:"spreadsheet-cell-cover-edit-field",value:""})),this.cellCoverEditFieldEl.on("keypress",this.onEditFieldKeyPressed,this))},onEditFieldBlur:function(){this.fireEvent("beforeeditfieldblur",this)!==!1&&(this.setEditing(!1),Spread.data.DataMatrix.setValueForPosition(this.activePosition,this.getEditingValue(),this.autoCommit),this.handleDirtyMarkOnEditModeStyling(),this.fireEvent("editfieldblur",this))},handleDirtyMarkOnEditModeStyling:function(){this.displayCellsEditing(!1),this.view.ownerCt.editModeStyling&&this.displayCellsEditing(!0)},blurEditFieldIfEditing:function(e,t){return this.isEditing&&this.onEditFieldBlur(),!0},onEditFieldKeyPressed:function(e){var t=this;e.getKey()===e.ENTER&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyEnter()),e.getKey()===e.TAB&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyTab()),e.getKey()===e.LEFT&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyLeft()),e.getKey()===e.RIGHT&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyRight()),e.getKey()===e.UP&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyUp()),e.getKey()===e.DOWN&&(t.onEditFieldBlur(),t.view.getSelectionModel().onKeyDown()),t.activePosition.columnHeader.allowedEditKeys.length>0&&Ext.Array.indexOf(t.activePosition.columnHeader.allowedEditKeys,String.fromCharCode(e.getCharCode()))===-1&&e.getKey()!==e.BACKSPACE&&e.stopEvent()},onCoverDblClick:function(e,t){this.fireEvent("beforecoverdblclick",this)!==!1&&(this.setEditing(),this.setEditingValue(Spread.data.DataMatrix.getValueOfPosition(this.activePosition)),this.fireEvent("coverdblclick",this))},onCoverKeyPressed:function(e,t){!e.isSpecialKey()&&!e.ctrlKey&&!this.isEditing&&(this.isEditing||this.fireEvent("beforecoverkeypressed",this)!==!1&&(this.setEditing(),this.setEditingValue(""),this.fireEvent("coverkeypressed",this)))},onCellCovered:function(e,t,n,r,i,s){this.activePosition=t,this.activeCellTdEl=r,this.activeCoverEl=n,this.activeCoverElSize=i,this.activeCoverElPosition=s,this.cellCoverEditFieldEl.dom.style.display="none"},setEditing:function(e){var t=this;Ext.isDefined(e)||(e=!0);if(!this.activePosition.columnHeader.editable||!this.editable)return!1;e?this.fireEvent("beforeeditingenabled",this)!==!1&&(t.isEditing=!0,t.cellCoverEditFieldEl.dom.style.display="inline",t.cellCoverEditFieldEl.dom.focus(),setTimeout(function(){t.cellCoverEditFieldEl.dom.focus()},t.retryFieldElFocusDelay),this.fireEvent("editingenabled",this)):this.fireEvent("beforeeditingdisabled",this)!==!1&&(t.cellCoverEditFieldEl.dom.style.display="none",setTimeout(function(){t.view.focus()},t.stopEditingFocusDelay),t.isEditing=!1,this.fireEvent("editingdisabled",this))},setEditingValue:function(e){this.cellCoverEditFieldEl.dom.value=e},getEditingValue:function(){return this.cellCoverEditFieldEl.dom.value},setDisabled:function(e){var t=this,n=function(e){for(var n=0;n<t.editableColumns.length;n++)t.editableColumns[n].editable=e};e?(t.editable=!0,n(!0),t.editModeStyling&&t.displayCellsEditing(!0)):(t.setEditing(!1),t.editable=!1,n(!1),t.editModeStyling&&t.displayCellsEditing(!1))},displayCellsEditing:function(e){var t=this,n=t.activePosition.view.getEl().query(this.activePosition.view.cellSelector),r=t.view.getHeaderCt().getGridColumns(),i=function(s,o){for(var u=s;u<o;u++){if(!n[u]||Ext.Array.indexOf(t.editableColumnIndexes,n[u].cellIndex)<0||r[n[u].cellIndex]&&r[n[u].cellIndex].editModeStyling===!1)continue;e?Ext.fly(n[u]).hasCls(t.editableCellCls)||(Ext.fly(n[u]).hasCls("x-grid-dirty-cell")?Ext.fly(n[u]).addCls(t.editableDirtyCellCls):Ext.fly(n[u]).addCls(t.editableCellCls)):(Ext.fly(n[u]).removeCls(t.editableCellCls),Ext.fly(n[u]).removeCls(t.editableDirtyCellCls))}o<n.length&&(s+=t.cellChunkSize,o+=t.cellChunkSize,setTimeout(function(){i(s,o)},t.chunkRenderDelay))};i(0,t.cellChunkSize)}}),Ext.define("Spread.grid.plugin.Pasteable",{alias:"pasteable",extend:"Ext.AbstractComponent",mixins:{clipping:"Spread.util.Clipping"},view:null,autoCommit:!1,loadMask:!0,useInternalAPIs:!1,init:function(e){this.addEvents("beforepaste","paste"),this.initClipping();var t=this;t.view=e,this.initKeyNav(e)},initKeyNav:function(e){var t=this;if(!e.rendered){e.on("render",Ext.Function.bind(t.initKeyNav,t,[e],0),t,{single:!0});return}e.getEl().on("keydown",t.detectPasteKeyStroke,t)},detectPasteKeyStroke:function(e){e.getKey()===e.V&&e.ctrlKey&&this.pasteFromClipboard()},pasteFromClipboard:function(){var e=this,t=this.view.getSelectionModel(),n=t.getSelectedPositionData();e.loadMask&&e.view.setLoading(!0),this.fireEvent("beforepaste",this,t,n)!==!1&&this.prepareForClipboardPaste(function(r){var i=Spread.data.TSVTransformer.transformToArray(r);e.updateRecordFieldsInStore(i,n,t),e.fireEvent("paste",e,t,n,i),e.loadMask&&e.view.setLoading(!1)},this.view)},updateRecordFieldsInStore:function(e,t,n){function i(e,t){n.currentFocusPosition=t,n.originSelectionPosition=e,n.tryToSelectRange(!0)}var r=this;if(t.length===0||e.length===0)return;if(e.length===1&&e[0].length===1){var s=t[0].update();Spread.data.DataMatrix.setValueForPosition(s,e[0][0],r.autoCommit),r.handleDirtyMarkOnEditModeStyling();return}if(t.length===1){var o=t[0].update(),u=o.column,a=o.row,s=null;a+=e.length-1,u+=e[0].length-1,s=new Spread.selection.Position(r.view,u,a),i(o,s)}t=n.getSelectedPositionData();var f=0,l=0,c=0;for(var h=0;h<t.length;h++)t[h].update(),l=t[h].row-o.row,f=t[h].column-o.column,r.useInternalAPIs||(h==0&&t[h].record.beginEdit(),c!==l&&t[h-1]&&(t[h-1].record.endEdit(),t[h].record.beginEdit())),c=l,Spread.data.DataMatrix.setValueForPosition(t[h],e[l][f],r.autoCommit,r.useInternalAPIs);r.useInternalAPIs?r.view.refresh():t[h-1].record.endEdit(),r.handleDirtyMarkOnEditModeStyling(),r.view.highlightCells(t)},handleDirtyMarkOnEditModeStyling:function(){this.view.editable&&(this.view.editable.displayCellsEditing(!1),this.view.ownerCt.editModeStyling&&this.view.editable.displayCellsEditing(!0))}}),Ext.define("Spread.grid.View",{extend:"Ext.grid.View",alias:"widget.spreadview",autoFocus:!0,autoFocusDelay:50,cellFocusDelay:50,stripeRows:!1,trackOver:!1,spreadViewBaseCls:"spreadsheet-view",cellCoverEl:null,currentCoverPosition:null,currentHighlightPositions:[],cellCoverZIndex:2,selectionCoverZIndex:1,coverPositionTopSubstract:2,coverPositionLeftSubstract:2,coverWidthAddition:3,coverHeightAddition:3,initComponent:function(){this.stripeRows=!1,this.baseCls=this.baseCls+" "+this.spreadViewBaseCls,this.addEvents("beforecovercell","covercell","beforehighlightcells","highlightcells","beforeeditfieldblur","editfieldblur","beforecoverdblclick","coverdblclick","beforecoverkeypressed","coverkeypressed","beforeeditingenabled","editingenabled","beforeeditingdisabled","editingdisabled","beforecopy","copy","beforepaste","paste");var e=this.callParent(arguments);return this.cellCoverEl||this.createCellCoverElement(),this.initPlugins(this.spreadPlugins),this.initRelayEvents(),e},initRelayEvents:function(){this.relayEvents(this.editable,["beforeeditfieldblur","editfieldblur","beforecoverdblclick","coverdblclick","beforecoverkeypressed","coverkeypressed","beforeeditingenabled","editingenabled","beforeeditingdisabled","editingdisabled"]),this.relayEvents(this.copyable,["beforecopy","copy"]),this.relayEvents(this.pasteable,["beforepaste","paste"])},initPlugins:function(e){for(var t=0;t<e.length;t++)this[e[t].alias]=e[t],this[e[t].alias].init(this)},createCellCoverElement:function(){var e=this;this.on("afterrender",function(){this.getEl().set({tabIndex:0}),e.autoFocus&&setTimeout(function(){e.getEl().focus()},e.autoFocusDelay),this.cellCoverEl=Ext.DomHelper.append(this.getEl(),{tag:"div",cls:"spreadsheet-cell-cover"}),Ext.get(this.cellCoverEl).on("mousedown",this.bubbleCellMouseDownToSelectionModel,this),this.headerCt.on("columnresize",function(){this.coverCell()},this)},this)},bubbleCellMouseDownToSelectionModel:function(e,t){var n=t.id.split("_"),r,i,s,o,u;if(n[1]&&Ext.fly(n[1])&&Ext.fly(n[1]).hasCls("x-grid-cell")){n=Ext.fly(n[1]).dom,r=Ext.fly(n).up("tr").dom,u=this.getRecord(r),i=Ext.fly(r).up("tbody").dom;for(var a=0;a<r.childNodes.length;a++)if(r.childNodes[a]===n){o=a;break}for(var a=0;a<i.childNodes.length;a++)if(i.childNodes[a]===r){s=a-1;break}this.getSelectionModel().onCellMouseDown("mousedown",this,n,s,o,e,u,r)}},coverCell:function(e){var t=this,n=t.getCellCoverEl();if(t.fireEvent("beforecovercell",t,e,n)!==!1){e&&t.highlightCells(),e?t.currentCoverPosition=e:e=t.currentCoverPosition,e.update();var r=Ext.get(e.cellEl),i,s;n.setStyle("display","block"),s=r.getXY(),s[0]-=t.coverPositionTopSubstract,s[1]-=t.coverPositionLeftSubstract,n.setXY(s),n.setStyle("z-index",t.cellCoverZIndex),i=r.getSize(),i.width+=t.coverWidthAddition,i.height+=t.coverHeightAddition,n.setSize(i),n.dom.id="coverOf_"+r.dom.id,setTimeout(function(){t.focusCell(e),t.getEl().focus()},t.cellFocusDelay),t.fireEvent("covercell",t,e,n,r,i,s)}},highlightCells:function(e){var t=this,n=function(e){for(var n=0;n<t.currentHighlightPositions.length;n++)Ext.fly(t.currentHighlightPositions[n].update().cellEl).down("div")[e]("spreadsheet-cell-selection-cover")};this.fireEvent("beforehighlightcells",this,e)!==!1&&(this.currentHighlightPositions.length>0&&n("removeCls"),e&&(this.currentHighlightPositions=e,n("addCls")),this.fireEvent("highlightcells",this,e))},getCellCoverEl:function(){return Ext.get(this.cellCoverEl)}}),Ext.define("Spread.grid.column.Header",{extend:"Ext.grid.RowNumberer",alias:"widget.spreadheadercolumn",editable:!1,columnWidth:60,selectable:!1,dataIndex:"id",resizable:!0,constructor:function(e){e.width=e.columnWidth||this.columnWidth,this.callParent(arguments)},renderer:function(e,t,n,r,i,s){var o=this.rowspan;return o&&(t.tdAttr='rowspan="'+o+'"'),t.tdCls=Ext.baseCSSPrefix+"grid-cell-header "+Ext.baseCSSPrefix+"grid-cell-special",e}}),
					Ext.define("Spread.grid.Panel",{
						extend:"Ext.grid.Panel",
						alias:"widget.spread",
						viewType:"spreadview",
						autoFocusRootPosition:!0,
						enableKeyNav:!0,
						editable:!0,
						autoCommit:!1,
						editModeStyling:!0,
						columnLines:!0,
						editablePluginInstance:Ext.create("Spread.grid.plugin.Editable",{}),
						copyablePluginInstance:Ext.create("Spread.grid.plugin.Copyable",{}),
						pasteablePluginInstance:Ext.create("Spread.grid.plugin.Pasteable",{}),
						constructor:function(e){
							var t=this;
							this.addEvents("beforecovercell","covercell","beforehighlightcells","highlightcells","beforeeditfieldblur","editfieldblur","beforecoverdblclick","coverdblclick","beforecoverkeypressed","coverkeypressed","beforeeditingenabled","editingenabled","beforeeditingdisabled","editingdisabled","beforecopy","copy","beforepaste","paste"),
							t.manageViewConfig(e),t.manageSelectionModelConfig(e),t.callParent(arguments),
							t.relayEvents(t.getView(),
							["beforecovercell","covercell","beforehighlightcells","highlightcells","beforeeditfieldblur","editfieldblur","beforecoverdblclick","coverdblclick","beforecoverkeypressed","coverkeypressed","beforeeditingenabled","editingenabled","beforeeditingdisabled","editingdisabled","beforecopy","copy","beforepaste","paste"]),
							t.pasteablePluginInstance&&(t.pasteablePluginInstance.autoCommit=t.autoCommit),t.getView().on("viewready",function(){t.setEditable(t.editable),t.setEditModeStyling(t.editModeStyling)})},
						initComponent:function(){
							return this.initColumns(),
									this.callParent(arguments)
						},
						initColumns:function(){
							for(var e=0;e<this.columns.length;e++)
								this.columns[e].view=this,
								this.columns[e].initialPanelEditModeStyling=this.editModeStyling
						},
						manageViewConfig:function(e){
							var t=this,
								n=function(e){
									e.viewConfig.spreadPlugins=[],
									e.viewConfig.spreadPlugins.push(t.editablePluginInstance,t.copyablePluginInstance,t.pasteablePluginInstance)
								};
								if(e.viewConfig){
									if(e.viewConfig.spreadPlugins&&Ext.isArray(e.viewConfig.spreadPlugins)){
										var r=function(t,n){
												var r=!1;
												for(var i=0;i<e.viewConfig.spreadPlugins.length;i++)
													e.viewConfig.spreadPlugins[i]instanceof t&&(r=!0);
												r||e.viewConfig.spreadPlugins.push(n)
										};
										r(Spread.grid.plugin.Editable,this.editablePluginInstance),
										r(Spread.grid.plugin.Copyable,this.copyablePluginInstance),
										r(Spread.grid.plugin.Pasteable,this.pasteablePluginInstance)
									}else 
									n(e);Ext.isDefined(e.viewConfig.stripeRows)?e.viewConfig.stripeRows=e.viewConfig.stripeRows:e.viewConfig.stripeRows=this.stripeRows}else e.viewConfig={},n(e)
						},
						manageSelectionModelConfig:function(e){
								var t={selType:"range"};Ext.isDefined(e.autoFocusRootPosition)?t.autoFocusRootPosition=e.autoFocusRootPosition:t.autoFocusRootPosition=this.autoFocusRootPosition,Ext.isDefined(e.enableKeyNav)?t.enableKeyNav=e.enableKeyNav:t.enableKeyNav=this.enableKeyNav,this.selModel=t
						},
						setEditable:function(e){
								this.editable=e;
								if(!this.getView().editable||!this.getView().editable.setDisabled)
								throw"You want the grid to be editable, but editing plugin isn't activated!";
								this.getView().editable.setDisabled(this.editable),this.getView().editable.autoCommit=this.autoCommit
						},
						setEditModeStyling:function(e){
							this.editModeStyling=e;
							if(!this.getView().editable||!this.getView().editable.displayCellsEditing)throw"You want the grid to change it's edit mode styling, but editing plugin isn't activated!";this.getView().editable.editModeStyling=this.editModeStyling,this.editModeStyling&&this.editable?this.getView().editable.displayCellsEditing(!0):this.getView().editable.displayCellsEditing(!1)
						},
						isEditable:function(){
							return this.editable
						}
					}
					);