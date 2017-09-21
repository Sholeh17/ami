<?php
$link = mssql_connect('10.255.238.14', 'trecs', 'trecs');
if (!$link || !mssql_select_db('MASA_AX2009SP1_LIVEDB', $link)) {
    die('Unable to connect or select database!');
}

$mysql =mysql_connect('localhost', 'root', 'qwerty123');
if (!$mysql || !mysql_select_db('sms', $mysql)) {
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
$LastRecId = getLastRecIDByNameTable("sms_ax_view_custmastersertifikasi", "RecId");
$sql = "SELECT * FROM zvCustMasterSertifikasi  WHERE RecId >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_custmastersertifikasi
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_custmastersertifikasi VALUES('".$row->NoReg."','".$row->TglRegistrasi."','".$row->TipeSertifikasi."',DATE(STR_TO_DATE('".$row->ExpireDate."','%M %d %Y %H:%i')),'".$row->RecId."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}
die;


// Select Data zvCustMappingCountrySertifikasi 
$LastRecId = getLastRecIDByNameTable("sms_ax_view_custmappingcountrysertifikasi", "RECID");
$sql = "SELECT * FROM zvCustMappingCountrySertifikasi WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_custmappingcountrysertifikasi
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_custmappingcountrysertifikasi VALUES('".$row->IDCOUNTRY."','".$row->IDSERTIFIKASI."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data zvCustMappingItemSertifikasi 
$LastRecId = getLastRecIDByNameTable("sms_ax_view_custmappingitemsertifikasi", "RECID");
$sql = "SELECT * FROM zvCustMappingItemSertifikasi WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_custmappingitemsertifikasi
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_custmappingitemsertifikasi VALUES('".$row->ITEMID."','".$row->NOREG."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data zvCustJenisSertifikasi 
$LastRecId = getLastRecIDByNameTable("sms_ax_view_custjenissertifikasi", "RECID");
$sql = "SELECT * FROM zvCustJenisSertifikasi WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_custjenissertifikasi
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_custjenissertifikasi VALUES('".$row->IDSERTIFIKASI."','".$row->NAMASERTIFIKASI."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}

?>
