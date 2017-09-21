<?php
	error_reporting(0);
?>
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
		$title = "";
		$data04 = $this->mrj->get_no_reg_checksheet($rowformulir->sample);
		foreach ($data04->result() as $row){
			$title = $row->label_testing_report;
			$no_reg_checksheet = $row->no_reg_checksheet;
			$__idmaterial = $row->idmaterial;
			$__name = $row->name;
		}
		$data03 = $this->mrj->get_details_formulir($rowformulir->idformulir);
		foreach ($data03->result() as $row){
			$materialName = $row->name_report;
		}
		
		$data05 = $this->mrj->get_control_date($rowformulir->idformulir);
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
			<th rowspan="3" style="width:20px; text-align: left;">
				No
			</th>
			<?php 
			foreach($fields_machine["textlabel"] as $v) { 
				?>
				<th style="width:120px; text-align: center;" nowrap>
					<?=$v;?>
				</th>
				<?php 
			} 
			?>
		</tr>
		<tr>
			<?php 
			$jx = 0;
			foreach($fields_machine["textlabel"] as $v) { 
				$limitToleransi = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($fields_machine['fields'][$jx]));
				if ($limitToleransi) {
					$limitTolerens = $limitToleransi->remarks;
				} else {
					$limitTolerens = " - ";
				}
				?>
				<th style="width:120px; text-align: center;" nowrap>
					<?=$limitTolerens;?>
				</th>
				<?php 
					$jx++; 
			} 
			?>
		</tr>
		<tr>
			<?php 
			$jx = 0;
			#var_dump($fields_machine);
			foreach($fields_machine["uom"] as $v) { 
			?>
			<th style="width:120px; text-align: center;" nowrap>
				<?=$v;?>
			</th>
			<?php 
			}
			?> 
		</tr>
		
		<?php
			$rowAvarage = array();
			for ($i= 0; $i < count($result); $i++) {
			?>
				<tr>
				<td style="text-align: left;"><?php echo ($i+1); ?>&nbsp;</td>
				<?php foreach($fields_machine["fields"] as $v) { 
			?>		
				<td style="text-align: right;"><?php echo $result[$i][$v]; ?>
				<?php 
					$checkTolerans = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($v));
					$is_true =false;
					if (isset($checkTolerans->id)) {
						if ($checkTolerans->start_toleransi <= $result[$i][$v] && $result[$i][$v] <= $checkTolerans->end_toleransi) {
							$is_true = true && $is_true;
						} else {
							$is_true = false && $is_true;
						}
					}
					$rowAvarage[$v][$i] = $result[$i][$v]; 
				
				?></td>
				<?php
					} 
				?>
					
				</tr>
			<?php  
			} 
			
			if($i == 0){
				for($itung = 1; $itung<5; $itung++){ ?>
					<tr>
						<td style="text-align: left;"><?=$itung?></td>
						<?php foreach($fields_machine["fields"] as $v) { 
						?>		
							<td style="text-align: right;"><?php echo $result[$i][$v]; ?>
							<?php 
								$checkTolerans = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($v));
								$is_true =false;
								if (isset($checkTolerans->id)) {
									if ($checkTolerans->start_toleransi <= $result[$i][$v] && $result[$i][$v] <= $checkTolerans->end_toleransi) {
										$is_true = true && $is_true;
									} else {
										$is_true = false && $is_true;
									}
								}
								$rowAvarage[$v][$i] = $result[$i][$v]; 
							
							?></td>
							<?php
								} 
							?>
					</tr>
				<?php
				}
			}
			?>
		
		<tr>
		  <td colspan="1" style="text-align:right">Avrg
		  </td>
		  <?php 
		  foreach($fields_machine["fields"] as $v) { 
			//Counting to avarage
			$cnt = 0;
			$amount = 0;
			foreach ($rowAvarage[$v] as $v1) {
				if (is_numeric($v1)  && $v1 != 0) {
					$amount += $v1;
					$cnt += 1;
				}
			}
			$res_avg = "-";
			if ($cnt > 0) {
				$res_avg = number_format(($amount / $cnt), 2,".",",");
			}
		  ?>	
		  <td style="text-align: right;"><?php echo $res_avg;// array_sum($rowAvarage[$v]) /count($rowAvarage[$v]); ?></td>
		   
		   <?php } 
		   ?>
		</tr>

		<tr>
		  <td colspan="1" style="text-align:right">Min
		  </td>
		  <?php 
		  foreach($fields_machine["fields"] as $v) { 
			//Counting to avarage
			//$cnt = 0;
			$amount = 0xffffffff;
			foreach ($rowAvarage[$v] as $v1) {
				if (is_numeric($v1)  && $v1 != 0) {
					$amount = $amount > $v1 ? $v1 : $amount;
				}
			}
			$res_min = $amount == 0xffffffff ? "-" : $amount;
		  ?>	
		  <td style="text-align: right;"><?php echo $res_min;?></td>
		   
		   <?php } 
		   ?>
		</tr>
		<tr>
		  <td colspan="1" style="text-align:right">Max
		  </td>
		  <?php 
		  foreach($fields_machine["fields"] as $v) { 
			//Counting to avarage
			$amount = 0x0;
			foreach ($rowAvarage[$v] as $v1) {
				if (is_numeric($v1)  && $v1 != 0) {
					$amount = $amount < $v1 ? $v1 : $amount;
				}
			}
			$res_max = $amount == 0x0 ? "-" : $amount;
		  ?>	
		  <td style="text-align: right;"><?php echo $res_max;?></td>
		   
		   <?php } 
		   ?>
		</tr>

		<tr>
		  <td colspan="1" style="text-align:right">Range
		  </td>
		  <?php 
		  foreach($fields_machine["fields"] as $v) { 
			//Counting to avarage
			$amount = 0x0;
			foreach ($rowAvarage[$v] as $v1) {
				if (is_numeric($v1)  && $v1 != 0) {
					$amount = $amount < $v1 ? $v1 : $amount;
				}
			}
			$res_max = $amount == 0x0 ? 0 : $amount;
			
			$amount = 0xffffffff;
			foreach ($rowAvarage[$v] as $v1) {
				if (is_numeric($v1)  && $v1 != 0) {
					$amount = $amount > $v1 ? $v1 : $amount;
				}
			}
			$res_min = $amount == 0xffffffff ? 0 : $amount;
		  ?>	
		  <td style="text-align: right;"><?php echo $res_max - $res_min;?></td>
		   
		   <?php } 
		   ?>
		</tr>

		<tr>
		  <td colspan="1" style="text-align:right">Judgement
		  </td>
		  <?php 
			$jx = 0;
			
			$isOK = true;
			foreach($fields_machine["fields"] as $v) {
				$status = "OK";
				$start_toleran = 0;
				$end_toleran = 0;
				$limitToleransi = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($fields_machine['fields'][$jx]));
				if ($limitToleransi) {
					$start_toleran 	= $limitToleransi->start_toleransi;
					$end_toleran 	= $limitToleransi->end_toleransi;
				}
				
				foreach ($rowAvarage[$v] as $v1) {
					if (is_numeric($v1)) {
						if(is_numeric($start_toleran)){
							if($v1 < $start_toleran){
								$status = "NG";
								$isOK = false;
							}
						}
						
						if(is_numeric($end_toleran)){
							if($v1 > $end_toleran){
								$status = "NG";
								$isOK = false;
							}
						}
					}
					
					if(!is_numeric($end_toleran) && !is_numeric($start_toleran)){
						$status = "Value is not set";
						$isOK = false;
					}
				}		
				
				
		  ?>	
		  <td style="text-align: right;"><?php echo $status; ?></td>
		   
		   <?php $jx++;}  ?>
		</tr>
		<tr>
			<td colspan="6" style="border-left: none;border-right: none;"></td>
		</tr>
		
	</table>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" style="border-collapse:collapse;border-spacing: 0;">
	    <tr><!-- 1 -->
			<td rowspan="4" colspan="4" valign="top">Keputusan - <font size="10"><b><?php echo $isOK ? "OK" : "NG"; ?></b></font></td>
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
	//document.getElementById("satatus_<?=$x;?>").innerHTML =  "<?=$result_row;?>";
</script>		