function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}

function newWindow(a_str_windowURL, a_str_windowName, a_int_windowWidth, a_int_windowHeight, a_bool_scrollbars, a_bool_resizable, a_bool_menubar, a_bool_toolbar, a_bool_addressbar, a_bool_statusbar, a_bool_fullscreen) {
  //alert('dddd');return;
  var int_windowLeft = (screen.width - a_int_windowWidth) / 2;
  var int_windowTop = (screen.height - a_int_windowHeight) / 2;
  var str_windowProperties = 'height=' + a_int_windowHeight + ',width=' + a_int_windowWidth + ',top=' + int_windowTop + ',left=' + int_windowLeft + ',scrollbars=' + a_bool_scrollbars + ',resizable=' + a_bool_resizable + ',menubar=' + a_bool_menubar + ',toolbar=' + a_bool_toolbar + ',location=' + a_bool_addressbar + ',statusbar=' + a_bool_statusbar + ',fullscreen=' + a_bool_fullscreen + '';
  var obj_window = window.open(a_str_windowURL, a_str_windowName, str_windowProperties)
    //if (parseInt(navigator.appVersion) >= 4) {
      //obj_window.window.focus();
      obj_window.focus();
    //}
}

function home(url) {
  window.location=url;  
}

function showPopupAlert() {
  var flashPanel = new YAHOO.widget.Panel("popup", 
  	{ 
  	    close:true,  
  	    visible:false,  
  	    draggable:true,
  	    fixedcenter:true,
  	    constraintoviewport:true,
        effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:2}
  	} 
  );
  flashPanel.render();
  flashPanel.show();
}
var flashPanel2;
function showPopupAlert2() {
  flashPanel2 = new YAHOO.widget.Panel("popup", 
  	{ 
  	    close:true,  
  	    visible:false,  
  	    draggable:true,
  	    fixedcenter:true,
  	    constraintoviewport:true,
        effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:2}
  	} 
  );
  flashPanel2.render();
  flashPanel2.show();
  setTimeout("flashPanel2.hide();",3000);
}

function updateTotal(totalDiv, discount, price, quantityDiv) {
  var quantity = document.getElementById(quantityDiv).value;
  var total = ((100 - discount) / 100) * quantity * price;
  var div = document.getElementById(totalDiv);
  if(div != null) {
    document.getElementById(totalDiv).innerHTML = addCommas(total.toFixed(2));
  }
}

function updateTotal2(totalDiv, element1, element2) {
  var number1 = document.getElementById(element1).value;
  var number2 = document.getElementById(element2).value;
  var total = number1 * number2;
  var div = document.getElementById(totalDiv);
  if(div != null) {
    document.getElementById(totalDiv).value = addCommas(total.toFixed(2));
  }
}

var total_array_rp = new Array();

function updateTotal3(totalDiv, element1, rate, number2) {
  var number1 = document.getElementById(element1).value;
  number1 = number1 * 1;
  number2 = number2 * 1;
  var total = 0;
  if(number2 != null) {
	total = (number1 + number2) * rate;
  }else {
  	total = number1 * rate;
  }	  
  var div = document.getElementById(totalDiv);
  if(div != null) {
    document.getElementById(totalDiv).innerHTML = addCommas(total.toFixed(2));
  }
  total_array_rp[totalDiv] = number1 * rate;
  updateTotalReceived();
}

function updateTotalReceived() {
  var temp_rp = 0;
  for(key in total_array_rp) {
    if(!isNaN(total_array_rp[key])) {
      temp_rp = temp_rp + total_array_rp[key];
    }
  }
  document.getElementById('total_received_rp').innerHTML = addCommas(temp_rp.toFixed(2));
}

function disableButton(btn)  {
	var obj = document.getElementById(btn);
	if(obj != null) {
		obj.disabled='disabled';
	}
}

function enableButton(btn)  {
	var obj = document.getElementById(btn);
	if(obj != null) {
		obj.disabled='';
	}
}

function printPage(a,b,c, base_url) { 
   document.getElementById('printPanel').style.display = "none";
   var url= base_url+'preview/config_prints/set_count_print/'+a+'/'+b+'/'+c;
   
   Ext.Ajax.request({
                                         url: url,
                                         method:'POST',
                                         success: function(response){
                                            window.print();
                                         }
                                });

}
