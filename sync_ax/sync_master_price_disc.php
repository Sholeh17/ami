<?php
$link = mssql_connect('10.255.238.20', 'trecs', 'trecs');
if (!$link || !mssql_select_db('MASA_DEV', $link)) {
    die('Unable to connect or select database!');
}

$mysql =mysql_connect('172.31.30.15', 'parul', 'qwerty123');
if (!$mysql || !mysql_select_db('sales_management', $mysql)) {
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


// Select Data Price Global
$LastRecId = getLastRecIDByNameTable("sms_ax_item_price_global");
$sql = "SELECT * FROM zvCustSalesPriceGlobal WHERE RECID >= '".$LastRecId."'";
$queryPriceGlobal = mssql_query($sql) or die("error SELECT");
//Do sync sms_item_price_global
$j=1;
while ($row = mssql_fetch_object($queryPriceGlobal)) {	
	$q_sync = "REPLACE INTO sms_ax_item_price_global VALUES('".$row->ItemId."','".$row->FromDate."','".$row->ToDate."','".$row->Currency."','".$row->Amount."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}



// Select Data Price List
$LastRecId = getLastRecIDByNameTable("sms_ax_item_price_list_customer");
$sql = "SELECT * FROM zvCustSalesPrice WHERE Recid >= '".$LastRecId."'";
$queryPriceGlobal = mssql_query($sql) or die("error SELECT");
//Do sync sms Price List
$j=1;
while ($row = mssql_fetch_object($queryPriceGlobal)) {	
	$q_sync = "REPLACE INTO sms_ax_item_price_list_customer VALUES('".$row->ItemID."','".$row->CustCode."','".$row->CURRENCY."','".$row->UNITID."','".$row->FROMDATE."','".$row->TODATE."','".$row->AMOUNT."','".$row->Recid."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data Line Disc
$LastRecId = getLastRecIDByNameTable("sms_ax_item_line_disc_customer");
$sql = "SELECT * FROM zvCustSalesLineDisc WHERE RECID >= '".$LastRecId."'";
$queryPriceGlobal = mssql_query($sql) or die("error SELECT");
//Do sync sms_item_price_global
$j=1;
while ($row = mssql_fetch_object($queryPriceGlobal)) {	
	$q_sync = "REPLACE INTO sms_ax_item_line_disc_customer VALUES('".$row->ItemID."','".$row->CustCode."','".$row->CURRENCY."','".$row->UNITID."','".$row->FROMDATE."','".$row->TODATE."','".$row->AMOUNT."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}

// Select Data MultiLine Disc
$LastRecId = getLastRecIDByNameTable("sms_ax_item_multiline_disc_customer");
$sql = "SELECT * FROM zvCustSalesMultiLineDisc WHERE RECID >= '".$LastRecId."'";
$queryPriceGlobal = mssql_query($sql) or die("error SELECT");
//Do sync Line Multidisc
$j=1;
while ($row = mssql_fetch_object($queryPriceGlobal)) {	
	$q_sync = "REPLACE INTO sms_ax_item_multiline_disc_customer VALUES('".$row->ItemID."','".$row->CustCode."','".$row->CURRENCY."','".$row->UNITID."','".$row->FROMDATE."','".$row->TODATE."','".$row->AMOUNT."','".$row->Percent1."','".$row->Percent2."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


?>
