
<html>
<title></title>

<head>

<style type="text/css">
    @media print {
        thead {display: table-header-group;}
    }
.div, .div td{
	vertical-align	: top;
	padding-top		: 1px!important;
	padding-bottom	: 1px!important;
	font-family		: "Lucida Console"!important;
	font-size		: 10pt!important;
	text-transform	: uppercase!important;
}
.bt {}
.bt td{
	border-top:1px solid #000000;
}
</style>

<script>
	function doPrint() {
		document.getElementById("btnP").style.display="none";
		window.print();
		document.getElementById("btnP").style.display="inline";
		
	}
</script>
</head>



<body>

<div class="div">
<img id="btnP" src="<?php echo base_url(); ?>assets/images/btn_icon/print.gif" style="cursor: pointer; cursor: hand;" onClick="doPrint()">
<table border="0" cellpadding="0" cellspacing="0" width="1024">
<tbody>
	<tr>
		<td>
			<center><h3>ITEM TEST PROGRESS</h3>						
						
						</center>
		</td>
	</tr>
</tbody>
</table>

<br /><br />
<div style="margin-bottom:4px;">
<table border="0">
<tr><td>No Req</td><td>: <?=$rowformulir->no_req;?></td></tr>
<tr><td>Date Req</td><td>: <?=$rowformulir->date_request;?></td></tr>
<tr><td>Date Line</td><td>: <?=$rowformulir->date_line;?> </td></tr>
<tr><td>Sample</td><td>: <?=$rowformulir->sample;?></td></tr>
<tr><td>Tipe</td><td>: <?=$rowformulir->type_request;?></td></tr>
<tr><td>Criteria</td><td>: <?=$rowformulir->criteria;?></td></tr>
<tr><td>Scale</td><td>: <?=$rowformulir->scale;?></td></tr>
<tr><td>Purpose</td><td>: <?=$rowformulir->porpose;?></td></tr>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<thead>
<tr>
  	<td style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td style="width:420px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Item Raw Material Compound
  	</td>	
	<td style="width:100px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Qty
  	</td>	
</tr>
</thead>
<?php
$i = 1;
foreach ($itemsraw as $v) {
?>
	<tr>
	<td style="border-left: 1px solid black; text-align: left;"><?php echo $i; ?>&nbsp;</td>			
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo $v['idmaterial']; ?>&nbsp;</td>	
	<td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo number_format($v['qty'],2,'.',','); ?>&nbsp;</td>
	</tr>
<? $i++;}  ?>
<tr>
<td style="border-top: 1px solid black; text-align: left;">&nbsp;</td>
<td style="border-top: 1px solid black; text-align: left;">&nbsp;</td>
<td style="border-top: 1px solid black; text-align: left;">&nbsp;</td>
</tr>
<thead>
<tr>
  	<td style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td style="width:420px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Item Test
  	</td>	
	<td style="width:100px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Status
  	</td>	
</tr>

</thead>

<?php
$i = 1;
foreach ($items as $v) {
?>
	<tr>
	<td style="border-left: 1px solid black; text-align: left;"><?php echo $i; ?>&nbsp;</td>			
    <td style="border-left: 1px solid black; text-align: left;"><?php echo $v["name_report"]; ?>&nbsp;</td>
	<td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo ($v['progress']) ? "Close" : "Open"; ?>&nbsp;</td>	</tr>
	</tr>
<? $i++;}  ?>

<tr>
  <td colspan="3" style="border-top: 1px solid black; text-align:right">&nbsp;
  </td>
</tr>
</table>


</body>
</html>