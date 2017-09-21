<?php
$link = mssql_connect('10.255.238.20', 'trecs', 'trecs');
if (!$link || !mssql_select_db('MASA_DEV', $link)) {
    die('Unable to connect or select database!');
}

$mysql =mysql_connect('172.31.30.15', 'parul', 'qwerty123');
if (!$mysql || !mysql_select_db('sales_management', $mysql)) {
    die('Unable to connect or select database!');
}

function getLastRecIDByNameTable($name_table, $field) {
	$sql = "SELECT max($field) as RECID FROM ".$name_table;
	$queryDel = mysql_query($sql);
	$data_row = mysql_fetch_row($queryDel);
	$row = mysql_num_rows($queryDel);
	if ($row > 0)
		return intval($data_row[0]);
	return 0;
}

function getLastTimeDateByNameTable($name_table, $field) {
	$sql = "SELECT max($field) as RECID FROM ".$name_table." ORDER BY $field DESC";
	$queryDel = mysql_query($sql);
	$data_row = mysql_fetch_row($queryDel);
	$row = mysql_num_rows($queryDel);
	if ($row > 0)
		return $data_row[0];
	return date("Y-m-d H:i:s");
}



// Select Data zvCustMasterSertifikasi
//$LastRecId = getLastRecIDByNameTable("sms_ax_view_last_costprice", "RecId");
$sql = "SELECT * FROM zvInventLastCostPrice  ";//WHERE RecId >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_last_costprice
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_last_costprice VALUES('".$row->ITEMID."',DATE(STR_TO_DATE('".$row->DATEPHYSICAL."','%M %d %Y %H:%i')),'".$row->CostPrice."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}



?>
