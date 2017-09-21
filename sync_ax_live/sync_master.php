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

// Select Data sms_ax_master_kubikasi
$LastRecId = getLastRecIDByNameTable("sms_ax_master_kubikasi");
$sql = "SELECT * FROM zvInventKubikasi  WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_master_kubikasi
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_master_kubikasi VALUES('".$row->ITEMID."','".$row->PALLETTYPEID."','".$row->ISWRAPPING."','".$row->TOTALQTY."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data sms_ax_master_pallettype
$LastRecId = getLastRecIDByNameTable("sms_ax_master_pallettype");
$sql = "SELECT * FROM zvInventPalletType   WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_master_pallettype
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_master_pallettype VALUES('".$row->PALLETTYPEID."','".addslashes($row->NAME)."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data sms_ax_master_port_destination
$LastRecId = getLastRecIDByNameTable("sms_ax_master_port_destination");
$sql = "SELECT * FROM zvInventPortDestination  WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_master_port_destination
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_master_port_destination VALUES('".$row->PORTID."','".addslashes($row->DESCRIPTION)."','".$row->PortCountry."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}




// Select Data sms_ax_master_pallettype
$LastRecId = getLastRecIDByNameTable("sms_ax_master_distributor");
$sql = "SELECT a.*, a.[CREDITLIMIT(IDR)] as crdIDR FROM xvts_msa_customer a WHERE a.recid >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_master_distributor
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_master_distributor (DIST_CODE,DIST_NAME,DISTGROUP,CODESALES,SALESPERSON,ADDRESS,REGION,CITY,PHONE,FAX,PAYMTERMID,PAYMENTTERM,CREDITLIMIT,PAYMETHOD,CUSTSTATUS,CURRENCY,	RECID,MODIFIEDDATETIME) 
			VALUES('".$row->CUSTCODE."','".addslashes($row->CUSTNAME)."','".$row->CUSTGROUP."','".$row->EMPLID."','".$row->SALESPERSON."','".addslashes($row->Address)."','".$row->Region."','".$row->CITY."','".$row->PHONE."','".$row->FAX."','".$row->PAYMTERMID."','".$row->PAYMENTTERM."','".$row->crdIDR."','".$row->PAYMETHOD."','".$row->CUSTSTATUS."','".$row->CURRENCY."','".$row->recid."','".date('Y-m-d H:i:s')."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}

$LastRecId = getLastRecIDByNameTable("sms_ax_master_item_catalog");
$sql = "SELECT a.* FROM xvts_msa_fgmaster a WHERE a.RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_master_item_catalog
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_master_item_catalog VALUES('".$row->ItemCode."','".addslashes($row->ITEMNAME)."','".$row->ItemSpecCode."','".$row->Weight."','".$row->MarkingLine."','".$row->ItemGroup."','".$row->BrandName."','".$row->PatternName."','".$row->CategoryName."','".$row->Width."','".$row->Height."','".$row->RimSize."','".$row->LoadIndex."','".$row->SpeedIndex."','".$row->NETWEIGHT."','".$row->CREATEDBY."','".$row->CREATEDDATETIME."','".$row->ExtItemName."','".$row->Brandtipe."','".$row->RECID."','".$row->MODIFIEDBY."','".$row->MODIFIEDDATETIME."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}

/*
$sql = "SELECT a.* FROM xvts_msa_employee a ";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_karyawan
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_karyawan (nik_karyawan,name_karyawan,id_departemen) VALUES('".$row->EMPLID."','".addslashes($row->NAME)."','".$row->DEPTCODE."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}
*/


?>
