
<html>
<title></title>

<head>
<meta charset="UTF-8">
<style type="text/css">
    @media print {
        thead {display: table-header-group;}
    }
.div, .div td{
	vertical-align	: top;
	padding-top		: 1px!important;
	padding-bottom	: 1px!important;
	font-family		: "Lucida Console"!important;
	font-size		: 12pt!important;
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
			<center><h3>Formulir Test</h3>						
						
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
<tr><td>Sample Specification</td><td>: <?=$rowformulir->sample_spec;?></td></tr>
<tr><td>Notes</td><td>: <?=$rowformulir->notes;?></td></tr>
</table>
</div>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
<tbody>


<thead>
<tr>
  	<td style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Item Raw Material Compound
  	</td>	
    <td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;">
  		Qty (PHR)
  	</td>	
    
    <?php
    //(($this->session->userdata('level') == "SECTION HEAD" || $this->session->userdata('level') == "DEPARTMENT HEAD" || $this->session->userdata('level') == "DIVISION HEAD" || $this->session->userdata('level') == "ADMIN")) ? "":"display:none;";?>
    
    
    <td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;<?=($this->session->userdata('level') == "ADMIN") ? "":"display:none;";?>">
  		Price
  	</td>	
    <td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;<?=($this->session->userdata('level') == "ADMIN") ? "":"display:none;";?>">
  		MATERIAL/KG COMPD
  	</td>	
    <td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;<?=(($this->session->userdata('level') == "SECTION HEAD" || $this->session->userdata('level') == "DEPARTMENT HEAD" || $this->session->userdata('level') == "DIVISION HEAD" || $this->session->userdata('level') == "ADMIN")) ? "":"display:none;";?>">
  		COST
  	</td>	
</thead>
<?php
$i = 1;
$cons_total_phr = 0;
$wKG = 1;
foreach ($itemsraw as $params) {
	$cons_total_phr += $params['qty'];
}

//print_r($itemsraw);
foreach ($itemsraw as $v) {
?>
	<tr>
	<td style="border-left: 1px solid black; text-align: left;"><?php echo $i; ?>&nbsp;</td>			
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo $v['idmaterial']; ?>&nbsp;</td>	
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo number_format($v['qty'],2,'.',','); ?>&nbsp;</td>	
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;<?=($this->session->userdata('level') == "ADMIN") ? "":"display:none;";?>"><?php echo $v['price']; ?>&nbsp;</td>	
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;<?=($this->session->userdata('level') == "ADMIN") ? "":"display:none;";?>">
		<?php 
			$mkc = @($v['qty'] / ($cons_total_phr * $wKG));
			echo number_format($mkc,4,'.',','); 
		
		
		?>&nbsp;</td>	
    <td style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;<?=(($this->session->userdata('level') == "SECTION HEAD" || $this->session->userdata('level') == "DEPARTMENT HEAD" || $this->session->userdata('level') == "DIVISION HEAD" || $this->session->userdata('level') == "ADMIN")) ? "":"display:none;";?>"><?php echo number_format(($v['price'] * $mkc),4,'.',','); ?>&nbsp;</td>	
    </tr>
<? $i++;}  ?>
<tr>
  <td colspan="<?=($this->session->userdata('level') == "ADMIN") ? "6":"4";?>" style="border-top: 1px solid black; text-align:right">&nbsp;
  </td>
</tr>


<thead>
<tr>
  	<td style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;border-right: 1px solid black;" colspan="5">
  		Item Test Machine
  	</td>	
	


</thead>

<?php
$i = 1;
foreach ($items as $v) {
?>
	<tr>
	<td style="border-left: 1px solid black; text-align: left;"><?php echo $i; ?>&nbsp;</td>			
    <td  colspan="5" style="border-left: 1px solid black;border-right: 1px solid black; text-align: left;"><?php echo $v['name_report']; ?>&nbsp;</td>	</tr>
<? $i++;}  ?>
<tr>
<td colspan="5" style="border-top: 1px solid black; text-align: left;">&nbsp;</td>
<td colspan="5" style="border-top: 1px solid black; text-align: left;">&nbsp;</td>
</tr>

</table>
<br><br>
<br>

<table border="0" cellpadding="10" cellspacing="20" width="30%">
<tr><td>Create,</td><td>Check,</td><td>Approve,</td></tr>
<tr><td><br><br>_______________</td><td><br><br>_______________</td><td><br><br>_______________</td></tr>
</table>


</body>
</html>