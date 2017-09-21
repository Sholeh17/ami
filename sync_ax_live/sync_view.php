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
	$sql = "SELECT $field FROM ".$name_table." ORDER BY $field DESC";
	$queryDel = mysql_query($sql);
	$data_row = mysql_fetch_row($queryDel);
	$row = mysql_num_rows($queryDel);	
	if ($row > 0)
		return $data_row[0];
	return date("Y-m-d H:i:s");
}


// Select Data xvts_msa_Sales_SOALL
$LastTimeRecId = getLastTimeDateByNameTable("sms_ax_view_sales_order", "modifiedDateTime");

$sql = "SELECT a.*, a.[Customer Name] as CustomerName, 
		a.[Qty Order] as QtyOrder, a.[Qty Pack Slip] as QtyPackSlip,
		a.[Qty Invoiced] as QtyInvoiced, a.[Qty Order Balance] as QtyOrderBalance,
		a.[Qty Closed] as QtyClosed, a.[Blanket Order] as BlanketOrder,
		a.[Customer Requisition] as CustomerRequisition
		FROM xvts_msa_Sales_SOALL a WHERE a.modifiedDateTime >= '".$LastTimeRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_sales_order
$j=1;
while ($row = mssql_fetch_object($querymaster)) {		
	$q_sync = "REPLACE INTO sms_ax_view_sales_order VALUES('".$row->SALESID."','".$row->CUSTACCOUNT."',
				'".addslashes($row->CustomerName)."','".$row->ITEMID."',
				'".$row->ITEMNAME."','".$row->QtyOrder."','".$row->QtyPackSlip."',
				'".$row->QtyInvoiced."','".$row->QtyOrderBalance."',
				DATE(STR_TO_DATE('".$row->DATECONFIRM."','%M %d %Y %H:%i')),'".$row->QtyClosed."',
				'".addslashes($row->BlanketOrder)."','".addslashes($row->CustomerRequisition)."',
				'".$row->PORT."','".addslashes($row->PORTNAME)."',
				'".$row->Type."','".$row->Status."',
				'".$row->CURRENCYCODE."','".$row->SALESPRICE."',
				'".$row->LINEPERCENT."','".$row->MULTILNPERCENT."',
				'".$row->netprice."','".$row->LINEAMOUNT."',
				'".$row->SOAMOUNT."','".$row->PSAMOUNT."',
				'".$row->CLOSEDAMOUNT."','".$row->OUTSTANDINGAMOUNT."',
				'".$row->PhysInvQty."','".$row->paymmode."',
				'".$row->PAYMENT."','".$row->PaymentTerm."',
				'".addslashes($row->PaymentMode)."','".addslashes($row->salesperson)."',
				'".$row->OUTSTANDING."','".$row->NETWEIGHT."',
				'".$row->OUTSTANDINGWEIGHT."','".$row->priceperkg."',
				'".$row->DOQTYNONE."','".$row->DOQTYRAR."',
				'".$row->DOQTYREG."','".$row->DOQTYCOMP."',
				'".$row->INVENTTRANSID."','".$row->tgl_payment."',
				'".$row->CONTAINER."','".$row->ISWRAPPING."',
				'".$row->CategoryName."',DATE(STR_TO_DATE('".$row->modifiedDateTime."','%M %d %Y %H:%i')),
				DATE(STR_TO_DATE('".$row->createddatetime_header."','%M %d %Y %H:%i')),DATE(STR_TO_DATE('".$row->modifiedDateTime_Header."','%M %d %Y %H:%i')),
				'".$row->DeliveryTerms."',DATE(STR_TO_DATE('".$row->LastModified."','%M %d %Y %H:%i')),
				'".$row->qtyNoShipment."','".addslashes($row->CustomerRef)."',
				'".$row->taxValue."','".$row->DiscAmount."');";        	
				
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
	
} 


// Select Data zvInventWMSOrder
$LastRecId = getLastRecIDByNameTable("sms_ax_view_output_order", "Recid");
$sql = "SELECT * FROM zvInventWMSOrder  WHERE Recid >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_output_order
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_output_order VALUES('".$row->orderId."','".$row->inventTransRefId."','".$row->customer."','".$row->itemId."','".$row->PortDest."','".(($row->IsWrapping == 1) ? 'true' : 'false') ."',DATE(STR_TO_DATE('".$row->dlvDate."','%M %d %Y %H:%i')),MONTH(DATE(STR_TO_DATE('".$row->dlvDate."','%M %d %Y %H:%i'))),YEAR(DATE(STR_TO_DATE('".$row->dlvDate."','%M %d %Y %H:%i'))),'".$row->qty."','".$row->qtyNoShipment."','".$row->Recid."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


// Select Data zvCustProdCapacityPlan 
$LastRecId = getLastRecIDByNameTable("sms_ax_view_prodcapacity_plan", "recid");
$sql = "SELECT * FROM zvCustProdCapacityPlan WHERE recid >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync sms_ax_view_prodcapacity_plan
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO sms_ax_view_prodcapacity_plan VALUES(DATE(STR_TO_DATE('".$row->Period."','%M %d %Y %H:%i')),'".$row->Remarks."','".$row->ProdCapacityStatus."','".$row->ItemId."','".$row->Capqty."','".$row->recid."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}



// Select Data zvCustAllocationPlan 
$LastRecId = getLastRecIDByNameTable("sms_ax_cust_alocation_plan", "RECID");
$sql = "SELECT * FROM zvCustAllocationPlan WHERE RECID >= '".$LastRecId."'";
$querymaster = mssql_query($sql) or die("error SELECT");

//Do sync  sms_ax_cust_alocation_plan
$j=1;
while ($row = mssql_fetch_object($querymaster)) {	
	$q_sync = "REPLACE INTO  sms_ax_cust_alocation_plan VALUES('".$row->ALLOCATIONPLANID."','".$row->REMARKS."','".$row->ALLOCATIONSTATUS."',DATE(STR_TO_DATE('".$row->PERIOD."','%M %d %Y %H:%i')),'".$row->CUSTACCOUNT."','".$row->SALESID."','".$row->ORDERID."','".$row->ITEMID."','".$row->ALLOCPLANQTY."','".$row->RECID."');";        	
	mysql_query($q_sync) or die(mysql_error());	
	$j++;
}


?>
