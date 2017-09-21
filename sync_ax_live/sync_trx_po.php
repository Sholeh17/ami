<?php

$link = mssql_connect('10.255.238.14', 'trecs', 'trecs');
if (!$link || !mssql_select_db('MASA_AX2009SP1_LIVEDB', $link)) {
    die('Unable to connect or select database!');
}

$mysql =mysql_connect('localhost', 'root', 'qwerty123');
if (!$mysql || !mysql_select_db('sms', $mysql)) {
    die('Unable to connect or select database!');
}

function getLastRecIDByNameTable($name_table) {
	$sql = "SELECT max(RECID) as RECID FROM ".$name_table;
	$queryDel = mysql_query($sql);
	$data_row = mysql_fetch_row($queryDel);
	$row = mysql_num_rows($queryDel);
	if ($row > 0)
		return intval($data_row[0]);
	return 0;
}

echo "Process..... Start : ".date("H:i:s");
// Select Data Price Global
$LastRecId = getLastRecIDByNameTable("sms_ax_item_price_global");
$sql = "SELECT * FROM sms_purchase_order_header a inner join
				sms_purchase_order_item b on a.no_po = b.no_po
		WHERE b.status_sync = 'sms'  AND status = 'Approve' AND status_sertification = 'OK'";
$queryPriceGlobal = mysql_query($sql) or die("error SELECT");
//Do sync sms_item_price_global
$j=1;
$nopo =array();
$POrecID =array();
$xtemp = 'XX';
//mssql_query("DELETE FROM ZTCUSTTMPSALESSMS");
//mssql_query("DELETE FROM ZTCUSTTMPSALESSMSCONTROL");
//die;
while ($row = mysql_fetch_object($queryPriceGlobal)) {	
	$q_sync = "INSERT INTO ZTCUSTTMPSALESSMS(DATAAREAID,RECID, ACCOUNTNUM,CURRENCY,TRANSDATE,PURCHORDERFORMNUM,CUSTOMERREF,ITEMID,ISWRAPPING,SALESPRICE,LINEPERCENT,LINEDISC,MULTILNPERCENT,SALESQTY,ZIPERCENT1,ZIPERCENT2,PORTDEST,PALLETTYPEID,QTYOUTPUT,QTYOUTPUT2_,QTYOUTPUT3_,QTYOUTPUT4_)
									VALUES('msa','".$row->idrec."','".$row->code_cust."','".$row->currency."','".$row->date_po."','".$row->no_po."','".$row->no_ref."','".$row->item_sku."','".(($row->is_wrapping == "true") ? 1:0)."','".$row->price_sales."','".$row->disc_1."','".$row->disc_2."','".$row->disc_3."','".$row->qty_order."','".$row->Percent1."','".$row->Percent2."','".$row->portdist."','".$row->pallettype."','".$row->m1."','".$row->m2."','".$row->m3."','".$row->mn."')";        		
	mssql_query($q_sync);	
	$q = "UPDATE sms_purchase_order_item SET status_sync = 'ax' WHERE idrec = '".$row->idrec."'";
	mysql_query($q);
	
	if ($xtemp != $row->no_po) {
		$nopo[] = $row->no_po;
		$POrecID[] = $row->porecid;
	}
	$xtemp =$row->no_po; 	
	$j++;
}

for ($i = 0; $i<count($nopo); $i++) {
	$q = "UPDATE sms_purchase_order_header SET status_ax = 'Synchronized' WHERE no_po = '".$nopo[$i]."'";
	mysql_query($q);
	
	$q2 = "INSERT INTO ZTCUSTTMPSALESSMSCONTROL(DATAAREAID,RECID,PURCHORDERFORMNUM,ISPROCESS,BLANKETID,SALESID) 
			VALUES('msa','".$POrecID[$i]."','".$nopo[$i]."','0','','')"; 
	mssql_query($q2);
}


// Select Data ZTCUSTTMPSALESSMSCONTROL
$LastRecId = getLastRecIDByNameTable("sms_ax_view_custtempsalescontroll", "RECID");
$sql = "SELECT * FROM ZTCUSTTMPSALESSMSCONTROL  WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_custtempsalescontroll
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_custtempsalescontroll VALUES('".$row->DATAAREAID."','".$row->RECVERSION."','".$row->RECID."','".$row->PURCHORDERFORMNUM."','".$row->ISPROCESS."','".$row->BLANKETID."','".$row->SALESID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}



echo "Finished End : ".date("H:i:s");


?>
