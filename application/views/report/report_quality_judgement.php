<?php error_reporting( 0 ); ?>
<html>
<head>

<script>
	function doPrint() {
		document.getElementById("btnP").style.display="none";
		window.print();
		document.getElementById("btnP").style.display="inline";
		
	}
</script>
</head>
<?php

?>
<?php if ($is_excel == 0) { ?><img id="btnP" src="<?php echo base_url(); ?>assets/images/btn_icon/print.gif" style="cursor: pointer; cursor: hand;" onClick="doPrint()"> <?php } ?>
<title>REPORT HASIL PENGUJIAN BAHAN BAKU</title>
<body>
	<table border="0" colspan="2"  width="100%">
		<tr>
			<td width="120px" style="border-left: none;border-bottom: none;border-right: none;border-top: none;">
				<div style="margin: 0 auto; width: 130px">
					  <img src="<?php echo base_url(); ?>assets/images/multi_strada_logo.png">
				</div>
			</td>
			<td style="border-left: none;border-bottom: none;border-right: none;border-top: none;font-size:18;"><b>PT. Multistrada Arah Sarana, Tbk.</b>
			</td>
			
		</tr>
		<tr height="5px">
			</tr>
			
	</table>
	<?php
		
	#print_r($AX);
		$data04 = $this->mrj->get_no_reg_checksheet($rowformulir->sample);
		foreach ($data04->result() as $row){
			$title = $row->label_testing_report;
			$no_reg_checksheet = $row->no_reg_checksheet;
			$__idmaterial = $row->idmaterial;
			$__name = $row->name;
		}
		#print_r($AX);
		$data03 = $this->mrj->get_details_formulir($rowformulir->idformulir);
		foreach ($data03->result() as $row){
			$materialName = $row->name_report;
		}
		#print_r($AX);
		$data05 = $this->mrj->get_control_date($rowformulir->idformulir);
		#print_r($data05);
		foreach ($data05->result() as $row){
			$controldate = $row->dt;
		}
		
	?>
	<div class="div_title" ><u><?php echo $title;?></u></div>
    <table border="0" colspan="2" colspadding="2" width="70%" name="tablehead" style="border-left: none;border-bottom: none;border-right: none;border-top: none;">
    	<tr style="border-left: none;border-bottom: none;border-right: none;border-top: none;">
        	<td width="22%" style="border-left: none;border-bottom: none;border-right: none;border-top: none;">NO RIR</td>
            <td width="78%" style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$rowformulir->rir;?></td>
        </tr>
        <tr>
        	<td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">MATERIAL NAME</td>
            <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$__name;?></td>
        </tr>
		   <tr>
        	<td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">MATERIAL KODE</td>
            <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$__idmaterial;?></td>
        </tr>
		<tr>
        	<td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">SUPPLIER</td>
            <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$AX[0]['VendorName']; ?></td>
        </tr>
        <tr>
        	<td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">LOT NUMBER</td>
            <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$AX[0]['LotNumber']; ?></td>
        </tr>
        <tr>
          <td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">ARRIVAL DATE</td>
          <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: 
		  <?php 
			$s = $AX[0]['ArrivalDate'] ;
			$time = strtotime($s);
			$newformat = date('Y-m-d H:i',$time);
			echo $newformat;
		  ?></td>
        </tr>
        <tr>
          <td  style="border-left: none;border-bottom: none;border-right: none;border-top: none;">CONTROL DATE</td>
          <td style="border-left: none;border-bottom: none;border-right: none;border-top: none;">: <?=$controldate; ?></td>
        </tr>
    </table>
    <br>
	<style>
	@media print {
        thead {display: table-header-group;}
    }
	th{		
		
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		height:25px;
		font-size:11px;
		text-align:center;
		vertical-align:middle;
		color:#666;
		color:#FFF;
		font-weight:bold;
		border:solid #eceaea thin;
		border-bottom:none;
		background-color:#963;
		
	}
	
	td{
	
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:11px;
		height:20px;
		color: #666;
		border:solid #eceaea thin;
		border-top:none;

	}
	.div_title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:24px;
		text-align:center;
		color:#666;
		margin-bottom:10px;
	}
</style>
    <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border-collapse:collapse;border-spacing: 0;">
	    <tr>
			<th width="2%" rowspan="1"><div align="center">No</th>
			<th width="7%" rowspan="1" colspan="1"><div align="center">Item Test</div></th>
			<th width="3%" rowspan="1"><div align="center">Unit</div></th>
			<th width="7%" rowspan="1"><div align="center">Standard</div></th>
			<th width="5%" rowspan="1"><div align="center">Hasil</div></th>
		</tr>
		
		<?php
			$itungan = 0;
			$id_formulir = $rowformulir->idformulir;
			$data02 = $this->mrj->get_jml_data_report_quality($id_formulir);
			$arr_data = array();
			foreach ($data02->result() as $row){
				$arr_data[] = array($row->jml, $row->name_report, false); 
			}
			$hasil_akhir = "OK";
			$data01 = $this->mrj->get_Data_report_quality($id_formulir);
			foreach ($data01->result() as $row){
				$itungan++;
		?>
				<tr>
				<td>
				<?php 
					echo $itungan;

				?>
				</td>
				
				<td><?php echo $row->textlabel; ?></td>
				<td align="center"><?php echo $row->UOM; ?></td>
				<td align="center"><?php echo is_null($row->remarks) ? "Undefined" : $row->remarks; ?></td>
				<td align="center"><?php echo is_null($row->hasil) ? "NG" : $row->hasil; ?></td>
				</tr>
		<?php
			
				//$hasil_akhir = "";
				
				if((is_null($row->start_toleransi) && is_null($row->end_toleransi)) || ($hasil_akhir == "NG")){
					$hasil_akhir = "NG";
				}
				else{
					$hasil_akhir = $row->res;
				}
			
			}
		?>
		
		<tr>
			<td colspan="6" style="border-left: none;border-right: none;"></td>
		</tr>
		
	</table>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" style="border-collapse:collapse;border-spacing: 0;">
	    <tr><!-- 1 -->
			<td rowspan="4" colspan="4" valign="top">Keputusan - <font size="10"><b><?php echo $hasil_akhir; ?></b></font></td>
			<td width="150px" align="center" ><b>Inspector</b></td>
			<td width="150px" align="center" ><b>Diketahui</b></td>
		</tr>
		<tr height="60px"><!-- 2 -->
			<td rowspan="2"></td>
			<td rowspan="2"></td>
		</tr>
		<tr><!-- 3 -->
		</tr>
		<tr><!-- 4 -->
			<td></td>
			<td></td>
		</tr>
		<tr><!-- 5 -->
			<td colspan="4" style="border-left: none;border-bottom: none;border-right: none;"></td>
			<td colspan="2" style="border-left: none;border-bottom: none;border-right: none;" align="right"><?=$no_reg_checksheet;?></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	
    <p>&nbsp;</p>
    <p><br>
    </p>
</body>
</html>
<script>
	document.getElementById("satatus_<?=$x;?>").innerHTML =  "<?=$result_row;?>";
</script>		