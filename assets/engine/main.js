var msk = null;
var tab_panel_doc = false
var xK = {        
	panelCenterHeight:0,
	enableBeforeUnload : function(o) {
		window.onbeforeunload = function (e) {
			return "Discard changes?";
		};
	},
	disableBeforeUnload : function () {
		window.onbeforeunload = null;
	},
	maskLoading : function(msgx) {
           if (!msgx)
               msgx ="Please wait...";
           return new Ext.LoadMask(Ext.getBody(), {msg:msgx});
        },
	setTitlePage:function(title) {
		if (navigator.userAgent.indexOf('Firefox') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Firefox') + 8)) >= 3.6){//Firefox
			document.getElementById('title_dd').innerHTML = title;
		 }else if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15){//Chrome
			document.getElementById('title_dd').innerHTML = title;
		 }else if(navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Version') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Version') + 8).split(' ')[0]) >= 5){//Safari
			document.getElementById('title_dd').innerHTML = title;
		 }else{
			document.title = title;
		 }
	
		
	},	
	getMontIndo:function(j) {
		var arrMonth = new Array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		return arrMonth[j];
	},
	findDstoreItemDuplicate: function(dsx, id_compare)
        {
            var i_cek = 0;
            var r = dsx.getRange(0);
            for(var i=0;i<r.length;i++){
                if (r[i].data.sku == id_compare)
                    i_cek++;
                //alert(r[i].data.sku+ " - "+id_compare);
            }
            if (i_cek!=0)
                return true;
            return false;
	},	
	onFocusCB : function(o){
		o.store.baseParams['query'] = o.getRawValue().trim()==""?"":o.getRawValue().trim();
		o.store.removeAll();
		o.store.reload({
			params :{
				start : 0
				//limit : 20,
				//query : ''
			}
		});
	},
	money : function(duit)
	{
		//this.debug(duit);
		//duit = "0";
		if(!duit)duit = 0;
		if(duit && duit=="")duit = 0;
		if(typeof duit == "string"){
			duit = parseFloat(duit);
		}
		var Min = false;
		if(duit<0){
			Min = true;
			duit = -duit;
		}
		
		var sp		= typeof duit == "string" ? duit.split("."):(duit.toString()).split(".");
		var str		= sp[0];
		var l 		= (str.length)-1;
		var tmp 	= "";
		var hasil 	= "";
		var counter = 1;
		for(var i=l;i>=0;i--)
		{
			if((counter) % 3 == 0 && i!=0)
				tmp += str.charAt(i)+",";
			else
			{
				tmp += str.charAt(i);
			}
			counter++;
		}
	
		for(var i=(tmp.length)-1;i>=0;i--)
		{
			hasil += tmp.charAt(i);
		}
		return (Min?"-":"")+(parseInt(sp[1])?(sp[1]=="00"?hasil:hasil+"."+(sp[1].length==1?(sp[1])+"0":(sp[1]).substr(0,2))):hasil+".00");
	},
	onlyNumbers : function(inputString)
	{
	  	var searchForNumbers = /[^\d]+/g;
	  	return inputString.replace(searchForNumbers,"");
	},
	toFloat : function(numb)
	{
		numb = typeof numb == "string" ? numb:numb.toString();
		var x1 = numb.split(".");
		//alert(x1);
		return x1[1]? parseFloat(this.onlyNumbers(x1[0])+"."+x1[1]):parseInt(this.onlyNumbers(x1[0]));
	},
	setDestroy : function(d){
		this.oD = d;
	},

	destroy : function(){
		if(this.oD){
			this.oD.call(this);
		}
		else{
			Ext.getCmp("doc-body").body.removeAllListeners();
			Ext.getCmp("doc-body").update("");
		}
		this.oD = null;
	}

};

xK.cM = {
	c : [],
        c_href : [],
        add_history : function(o) {
            this.c_href.push(o);
        },
	add : function(o){
		if(o.text.search(/Login/) != -1)
		{
			//alert('session anda sudah habis, silahkan anda login kembali');
			//window.location='auth/login/logout';
			//return;
		}

		this.c.push(o);
	},
	isAvailable : function(href){
		var r = null;
		for(var i=0;i<this.c.length;i++){
			//window.console.debug(this.c[i].href+"||"+href);
                        //window.console.debug(this.c[i].scripts);
			if(this.c[i].href==href){
				r = {
					script : this.c[i].text,
					status : this.c[i].scripts
				}
				break;
			}
		}
		return r;
	},
	check : function(href){
		var c = this.isAvailable(href);                
		if(c){
			if(c.status) {
                            this.eval(c.script);
                        } else {                            
                            Ext.getCmp('doc-body').update(c.script, true); 
							msk.hide();	
                        }
		}

		return c;
	},

	eval : function(html){
        var hd = document.getElementsByTagName("head")[0];

        var re = /(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/img;
        var srcRe = /\ssrc=[\'\"]([^\'\"]*)[\'\"]/i;
        var match;
        while(match = re.exec(html)){
            var srcMatch = match[0].match(srcRe);
            if(srcMatch && srcMatch[1]){
				//window.console.debug("true");
               	var s0 = document.createElement("script");
               	s0.src = srcMatch[1];
               	hd.appendChild(s0);
            }else if(match[1] && (match[1].length != 0)){
               	eval(match[1]);
				//window.console.debug("false");
            }
        }
	}
};

xK.callback = {};
var msk = null;
function loadContent(href){        
        if (href == "" || href == undefined || href == "undefined")
            return;
        xK.destroy();                 
        
        xK.cM.add_history({
              href 	: href
        });
		msk = xK.maskLoading('Wait loading page..');
		msk.show();
        if(!xK.cM.check(href)){			
			var dynamicPanel = Ext.create('Ext.Component',{
					   loader: {
						  url: href,
						  renderer: 'html',
						  autoLoad: true,
						  scripts: true,
						  callback : function(el,s,x,op) {
								  xK.cM.add({
									href 	: href,
									text 	: x.responseText,
									scripts : op.scripts
								});
								msk.hide();
						  }
						  },					  
						renderTo: 'doc-body'
					   });		
        }
        
                
	
}


 // Add the additional 'advanced' VTypes
    Ext.apply(Ext.form.field.VTypes, {
        daterange: function(val, field) {
            var date = field.parseDate(val);

            if (!date) {
                return false;
            }
            if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                var start = field.up('form').down('#' + field.startDateField);
                start.setMaxValue(date);
                start.validate();
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = field.up('form').down('#' + field.endDateField);
                end.setMinValue(date);
                end.validate();
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        },

        daterangeText: 'Start date must be less than end date',

        password: function(val, field) {
            if (field.initialPassField) {
                var pwd = field.up('form').down('#' + field.initialPassField);
                return (val == pwd.getValue());
            }
            return true;
        },

        passwordText: 'Passwords do not match'
    });

function winPopPrint(url,titlex) {
var title = (titlex) ? titlex : "";

window.open(url,"","width=700,height=750,toolbar=no,scrollbars=yes,location=no,resizable=yes");
window.moveTo(0,0);
return;
window.resizeTo(screen.width,screen.height-100);
self.close();
}