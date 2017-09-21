<html>
<head>
<style>

	table{
		border-collapse:collapse;
		border-spacing: 0;	
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
		font-size:14px;
		text-align:left;
		color:#666;
		margin-bottom:10px;
	}
</style>
</head>
<title>REPORT HASIL PENGUJIAN COMPOUND - CHECK</title>
<body>
	<div class="div_title"><u>HASIL PENGUJIAN COMPOUND - CHECK</u></div>
    <table border="0" colspan="2" colspadding="2" width="70%">
    	<tr>
        	<td width="22%">NO REGISTRASI</td>
            <td width="78%">: <?php echo $rowformulir->no_req;?></td>
        </tr>
        <tr>
        	<td >DATA REQUEST</td>
            <td>: <?php echo date("d M Y", strtotime($rowformulir->date_request));?></td>
        </tr>
        <tr>
        	<td >SAMPLE</td>
            <td>: <?php echo $rowformulir->sample;?></td>
        </tr>
        <tr>
          <td >INSPECTOR</td>
          <td>: </td>
        </tr>
        <tr>
          <td >SHIFT</td>
          <td>: <?php echo $rowformulir->shift_formulir;?></td>
        </tr>
        <tr>
          <td >TIPE</td>
          <td>: <?php echo $rowformulir->type_request;?></td>
        </tr>
        <tr>
          <td >CRITERIA</td>
          <td>: <?php echo $rowformulir->criteria;?></td>
        </tr>
        <tr>
          <td >SCALE</td>
          <td>: <?php echo $rowformulir->scale;?></td>
        </tr>
        <tr>
          <td >PURPOSE</td>
          <td>: <?php echo $rowformulir->porpose;?></td>
        </tr>
    </table>
    <br>
    <table width="108%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <th width="6%" rowspan="2">Pallet</th>
        <th width="7%" rowspan="2">NO LOT</th>
        <th width="7%" rowspan="2">Time Sample</th>
        <th width="7%" rowspan="2">Batch</th>
        <th colspan="5"><div align="center">RHEOMETER 195</div></th>
        <th width="4%"><div align="center">DENSTIY</div></th>
        <th width="5%"><div align="center">MOONEY VISCOSTY</div></th>
        <th colspan="2"><div align="center">MOONEY SCORCHTIME</div></th>
        <th width="5%" rowspan="2">Status  </th>
      </tr>
      <tr>
        
        <th width="5%">MH<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 1, "MH"); ?>)</td>
        
        <th width="5%">ML<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 1, "ML"); ?>)</td>
        
        <th width="5%">TC10<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 1, "tc10"); ?>)</td>
        
        <th width="5%">TC50<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 1, "tc50"); ?>)</td>
        
        <th width="5%">TC90<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 1, "tc90"); ?>)</td>
        
        <th>DENSTIY<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 8, "DENSITY"); ?>)</td>
        <th>ML+4<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 10, "ML_4"); ?>)</td>
        <th width="5%">TS5<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 9, "TS_5"); ?>)</td>
        <th width="5%">TS53<br>(<?php echo $this->mrj->set_set_text_toleransi_param($rowformulir->sample, 9, "TS_35"); ?>)</td>
             </tr>
      <?php
	  		//print_r($denstie);
			$j = 0; 
			$arr_mh = array(); $arr_ml = array(); $arr_tc10 = array(); $arr_tc50 = array(); $arr_tc90 = array();
			
			$arr_dens = array(); $arr_ml_4 = array(); $arr_ts_5 = array(); $arr_ts_35 = array(); $arr_hardenss = array();
			$arr_M_100mpa = array(); $arr_M_300mpa = array(); $arr_Tensile = array(); $arr_Elongation = array();
				
			
	  		foreach ($rheometer195 as $r) {
	  			if ($r['MH'] != "") {
					$arr_mh[] = $r['MH']; 
				}
				if ($r['ML'] != "") {
					$arr_ml[] = $r['ML']; 
				}
				if ($r['tc10'] != "") {
					$arr_tc10[] = $r['tc10']; 
				}
				if ($r['tc50'] != "") {
					$arr_tc50[] = $r['tc50']; 
				}
				if ($r['tc90'] != "") {
					$arr_tc90[] = $r['tc90']; 
				}
				
				// Rheometer 195
				$toleMH = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 1, "MH");
				$statusMH = false;
				$MH = (float) $r['MH'];
				if ($toleMH->start_toleransi <= $MH && $MH <= $toleMH->end_toleransi)
					$statusMH = true;
				
					
				$toleML = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 1, "ML");
				$statusML = false;
				$ML = (float) $r['ML'];
				if ($toleML->start_toleransi <= $ML && $rML <= $toleML->end_toleransi)
					$statusML = true;
				
				$toletc10 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 1, "tc10");
				$statustc10 = false;
				$tc10 = (float) $r['tc10'];
				if ($toletc10->start_toleransi <= $tc10 && $tc10 <= $toletc10->end_toleransi)
					$statustc10 = true;
				
					
				$toletc50 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 1, "tc50");
				$statustc50 = false;
				$tc50 = (float) $r['tc50'];
				if ($toletc50->start_toleransi <= $tc50 && $tc50 <= $toletc50->end_toleransi)
					$statustc50 = true;	
				
					
				$toletc90 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 1, "tc90");
				$statustc90 = false;
				$tc90 = (float) $r['tc90'];
				if ($toletc90->start_toleransi <= $tc90 && $tc90 <= $toletc90->end_toleransi)
					$statustc90 = true;				
				
				if ($statusMH && $statusML && $toletc10 && $toletc50 && $statustc90)
					$status_rheo = true;
				else
					$status_rheo = false;
				
				// DENSITY
				$toleDENSITY = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 8, "DENSITY");
				$statusDENSITY = false;
				if ($toleDENSITY->start_toleransi <= $denstie[$j]['DENSITY'] && $denstie[$j]['DENSITY'] <= $toleDENSITY->end_toleransi)
					$statusDENSITY = true;
					
				
				if (!isset($denstie[$j]['DENSITY'])) {
					$statusDENSITY = true;
				}
							
				// MV
				$toleML_4 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 10, "ML_4");
				$statusML_4 = false;
				if ($toleML_4->start_toleransi <= $moone_v[$j]['ML_4'] &&  $moone_v[$j]['ML_4'] <= $toleML_4->end_toleransi)
					$statusML_4 = true;
					
				if (!isset($moone_v[$j]['ML_4']))
					$statusML_4 = true;		
					
				// MS
				$toleTS_5 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 9, "TS_5");
				$statusTS_5 = false;
				if ($toleTS_5->start_toleransi <= $moone_s[$j]['TS_5'] &&  $moone_s[$j]['TS_5'] <= $toleTS_5->end_toleransi)
					$statusTS_5 = true;	
					
				
				if (!isset($moone_s[$j]['TS_5']))
					$statusTS_5 = true;		
					
				
				$toleTS_35 = $this->mrj->get_toleransi_param_test_report($rowformulir->sample, 9, "TS_35");
				$statusTS_35 = false;
				if ($toleTS_35->start_toleransi <= $moone_s[$j]['TS_35'] &&  $moone_s[$j]['TS_35'] <= $toleTS_35->end_toleransi)
					$statusTS_35 = true;
					
				if (!isset($moone_s[$j]['TS_35']))
					$statusTS_35 = true;	
					
						
					
				$statusMS = false;	
				if ($statusTS_5 && $statusTS_35)
					$statusMS = true;	
				
				if ($status_rheo  && $statusDENSITY && $statusML_4 && $statusMS) 
					$set_status = "OK";
				else
					$set_status = "NG";	
	  ?>
      
      <tr>
        <td><?=$r['pallet'];?></td>
        <td><?=$r['no_lot'];?></td>
        <td><?=$r['time_sample'];?></td>
        <td><?=$r['batch'];?></td>
        <td><?=$r['MH'];?></td>
        <td><?=$r['ML'];?></td>
        <td><?=$r['tc10'];?></td>
        <td><?=$r['tc50'];?></td>
        <td><?=$r['tc90'];?></td>
        <td><?=$denstie[$j]['DENSITY'];?></td>
        <td><?=$moone_v[$j]['ML_4'];?></td>
        <td><?=$moone_s[$j]['TS_5'];?></td>
        <td><?=$moone_s[$j]['TS_35'];?></td>
        
        <td align="center"><?=$set_status;?></td>
      </tr>
      	
        	<?php
				if ($denstie[$j]['DENSITY'] != "") {
					$arr_dens[] = $denstie[$j]['DENSITY']; 
				}
				
				if ($moone_v[$j]['ML_4'] != "") {
					$arr_ml_4[] = $moone_v[$j]['ML_4']; 
				}
				
				if ($moone_s[$j]['TS_5'] != "") {
					$arr_ts_5[] = $moone_v[$j]['TS_5']; 
				}
				
				if ($moone_s[$j]['TS_35'] != "") {
					$arr_ts_35[] = $moone_s[$j]['TS_35']; 
				}
				
				if ($physical[$j]['Hardenss'] != "") {
					$arr_hardenss[] = $physical[$j]['Hardenss']; 
				}
				
				if ($physical[$j]['M_100mpa'] != "") {
					$arr_M_100mpa[] = $physical[$j]['M_100mpa']; 
				}
				
				if ($physical[$j]['M_300mpa'] != "") {
					$arr_M_300mpa[] = $physical[$j]['M_300mpa']; 
				}
				
				if ($physical[$j]['Tensile'] != "") {
					$arr_Tensile[] = $physical[$j]['Tensile']; 
				}
				
				if ($physical[$j]['Elongation'] != "") {
					$arr_Elongation[] = $physical[$j]['Elongation']; 
				}
				
			?>
            
            
      
      <?php
	  			$j++;
			}
	  
	  ?>
      
      
      <tr>
        <td colspan="14">&nbsp;</td>
      </tr>
      <tr align="right"	>
        <td><b>AVERAGE</b></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php
			echo number_format((array_sum($arr_mh) / count($arr_mh)),2);
		?></td>
        <td>
			<?php
			echo number_format((array_sum($arr_ml) / count($arr_ml)),2);
			?>
        </td>
        <td>
			<?php
			echo number_format((array_sum($arr_tc10) / count($arr_tc10)),2);
			?>
        </td>
        <td><?php
			echo number_format((array_sum($arr_tc50) / count($arr_tc50)),2);
			?>
        </td>
        <td>
			<?php
			echo number_format((array_sum($arr_tc90) / count($arr_tc90)),2);
			?>
        </td>
        
        <td><?php
			echo number_format((array_sum($arr_dens) / count($arr_dens)),2);
			?></td>
        <td><?php
			echo number_format((array_sum($arr_ml_4) / count($arr_ml_4)),2);
			?></td>
        <td><?php
			echo number_format((array_sum($arr_ts_5) / count($arr_ts_5)),2);
			?></td>
        <td><?php
			echo number_format((array_sum($arr_ts_35) / count($arr_ts_35)),2);
			?></td>
        <td>&nbsp;</td>
      </tr>
      <tr align="right">
        <td><b>MAX</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php
			echo number_format(max($arr_mh),2);
		?></td>
        <td>
			<?php
			echo number_format(max($arr_ml),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(max($arr_tc10),2);
			?>
        </td>
        <td><?php
			echo number_format(max($arr_tc50),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(max($arr_tc90),2);
			?>
        </td>
        
        <td><?php
			echo number_format(max($arr_dens),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ml_4),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ts_5),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ts_35),2);
			?></td>
        <td>&nbsp;</td>
      </tr>
      <tr align="right">
        <td><b>MIN</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php
			echo number_format(min($arr_mh),2);
		?></td>
        <td>
			<?php
			echo number_format(min($arr_ml),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(min($arr_tc10),2);
			?>
        </td>
        <td><?php
			echo number_format(min($arr_tc50),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(min($arr_tc90),2);
			?>
        </td>
        
        <td><?php
			echo number_format(min($arr_dens),2);
			?></td>
        <td><?php
			echo number_format(min($arr_ml_4),2);
			?></td>
        <td><?php
			echo number_format(min($arr_ts_5),2);
			?></td>
        <td><?php
			echo number_format(min($arr_ts_35),2);
			?></td>
        <td>&nbsp;</td>
      </tr>
      <tr align="right">
        <td><b>R</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php
			echo number_format(max($arr_mh)-min($arr_mh),2);
		?></td>
        <td>
			<?php
			echo number_format(max($arr_ml) - min($arr_ml),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(max($arr_tc10) - min($arr_tc10),2);
			?>
        </td>
        <td><?php
			echo number_format(max($arr_tc50) - min($arr_tc50),2);
			?>
        </td>
        <td>
			<?php
			echo number_format(max($arr_tc90) - min($arr_tc90),2);
			?>
        </td>
        
        <td><?php
			echo number_format(max($arr_dens) - min($arr_dens),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ml_4) - min($arr_ml_4),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ts_5) - min($arr_ts_5),2);
			?></td>
        <td><?php
			echo number_format(max($arr_ts_35) - min($arr_ts_35),2);
			?></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p><br>
    </p>
</body>
</html>
<script>
	document.getElementById("satatus_<?=$x;?>").innerHTML =  "<?=$result_row;?>";
</script>		