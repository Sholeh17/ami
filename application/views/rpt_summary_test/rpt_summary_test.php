<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
		font-weight:bold;
		border:solid #eceaea thin;
		border-bottom:none;
		background-color:#CFC;
		
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
<title>Report Summary Test Result</title>
<body>
	<div class="div_title"><u>Report Summary Test</u></div>
    <table width="50%">
    <?php
		//print_r($v_lst->result());
		foreach($v_lst->result() as $v_row){
		
	?>
    	<tr bgcolor="#CCCCCC">
    	  <td colspan="2"><b><?=$v_row->name?></b></td>
        	<td width="20%" align="center"><b><?=$v_row->no_req?></b></td>
        </tr>
        <?php
		$fields_detail = $this->cm->list_machine($v_row->no_req, $v_row->idmachinetest_detail, $v_row->idformulir);
		//print_r($fields_detail);
		foreach($fields_detail  as $v_lst_mch){
			if($v_row->idreport != '7' && $v_row->idreport != '33' && 
			$v_row->idreport != '32' && $v_row->idreport != '34' && 
			$v_row->idreport != '35' && $v_row->idreport != '25' && 
			$v_row->idreport != '38'  && $v_row->idreport != '29' 
			&& $v_row->idreport != '20' && $v_row->idreport != '21' 
			&& $v_row->idreport != '73' && $v_row->idreport != '319'){
		?>
        
				<tr>
				  <td width="5%" style="border-right:none;">&nbsp;</td>
				  <td width="75%" style="border-left:none;"><?=$v_lst_mch->textlabel;?> </td>
				  <td align="right"><?php if ($v_lst_mch->textlabel == "Tan_D") echo number_format($v_lst_mch->avg_val,5,'.',','); else echo number_format($v_lst_mch->avg_val,5,'.',',');?> <?//=round($v_lst_mch->avg_val,3)?></td>
				</tr> 
         <?php
			
			}
			
			if($v_row->idreport == '2'){ //Rheometer Test at 165°C 
					
					if($v_lst_mch->idfield == '33'){ $v_mh_val = $v_lst_mch->avg_val;}
					if($v_lst_mch->idfield == '34'){ $v_ml_val = $v_lst_mch->avg_val;}
					
					if($v_mh_val != '' && $v_ml_val != ''){
						
						$v_val = $v_mh_val - $v_ml_val;
						
						echo "<tr>";
						echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
						echo "<td width='75%' style='border-left:none;'>&Delta;Torque</td>";
						echo "<td align='right'>".number_format($v_val,5,'.',',')."</td>";
						echo "</tr>";
						
						$v_mh_val = '';
						$v_ml_val = '';
					}
			}
			
			if($v_row->idreport == '1'){ //Rheometer Test at 195°C
				
				if($v_lst_mch->idfield == '1'){ $v_mh_val = $v_lst_mch->avg_val;}
				if($v_lst_mch->idfield == '2'){ $v_ml_val = $v_lst_mch->avg_val;}
					
					if($v_mh_val != '' && $v_ml_val != ''){
						
						$v_val = $v_mh_val - $v_ml_val;
						
						echo "<tr>";
						echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
						echo "<td width='75%' style='border-left:none;'>&Delta;Torque </td>";
						echo "<td align='right'>".number_format($v_val,5,'.',',')."</td>";
						echo "</tr>";
						
						$v_mh_val = '';
						$v_ml_val = '';
					}
			}
			
			if($v_row->idreport == '11'){ //Physical Propertis Fresh
				
				if($v_lst_mch->idfield == '321'){ $v_mh_val = $v_lst_mch->avg_val;}
				if($v_lst_mch->idfield == '322'){ $v_ml_val = $v_lst_mch->avg_val;}
					
					if(!empty($v_mh_val) && $v_ml_val != ''){
						
						$v_val = $v_mh_val * $v_ml_val;
						
						echo "<tr>";
						echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
						echo "<td width='75%' style='border-left:none;'>TB x EB</td>";
						echo "<td align='right'>".number_format($v_val,5,'.',',')."</td>";
						echo "</tr>";
						
						
						$v_frs_ptb =  $v_mh_val;
						
						$v_mh_val = '';
						$v_ml_val = '';						
						
					}
			}	
			
			if($v_row->idreport == '51'){ //Physical Propertis Aging 24 Jam
				$v_mh_val = "";
				$v_ml_val = "";
				if($v_lst_mch->idfield == '482'){ $v_mh_val = $v_lst_mch->avg_val;}
				if($v_lst_mch->idfield == '481'){ $v_ml_val = $v_lst_mch->avg_val;}
					
					if($v_mh_val != '' && $v_ml_val != ''){
						
						$v_val = $v_mh_val * $v_ml_val;
						
						echo "<tr>";
						echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
						echo "<td width='75%' style='border-left:none;'>TB x EB</td>";
						echo "<td align='right'>".number_format($v_val,5,'.',',')."</td>";
						echo "</tr>";
						
						
						$v_age_ptb =  $v_ml_val;
						
						$v_mh_val = '';
						$v_ml_val = '';						
						
					}					
			}	
			
			if($v_row->idreport == '13'){ //Physical Propertis HT
				
				if($v_lst_mch->idfield == '131'){ $v_mh_val = $v_lst_mch->avg_val;}
				if($v_lst_mch->idfield == '132'){ $v_ml_val = $v_lst_mch->avg_val;}
					
					if($v_mh_val != '' && $v_ml_val != ''){
						
						$v_val = ($v_mh_val * $v_ml_val);
						
						echo "<tr>";
						echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
						echo "<td width='75%' style='border-left:none;'>TB x EB</td>";
						echo "<td align='right'>".number_format($v_val,0,'.',',')."</td>";
						echo "</tr>";
						
						$v_mh_val = '';
						$v_ml_val = '';
					}
			}		
			
			if($v_row->idreport == '12'){ //Physical Propertis Aging
				
				if(!empty($v_age_ptb) && !empty($v_frs_ptb)){
					
					$v_val = ($v_age_ptb/$v_frs_ptb) * 100;
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>TB, %</td>";
					echo "<td align='right'>".number_format($v_val,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_age_ptb = '';
					$v_frs_ptb = '';
				}
			}	
			
			if($v_row->idreport == '33'){ //WET 25
				
				$v_ave = $this->cm->machine_ave($v_row->no_req, $v_row->idmachinetest_detail, $v_row->idformulir);
				$v_arr_tmp = array();
				foreach($v_ave as $key=>$v){
					$v_arr_tmp[$v['namefield']][] = $v['avg_val'];
				}
				
				//μp Ave
				$v_jml_one = (!empty($v_arr_tmp['p_Ave_1_1']['14']) ? $v_arr_tmp['p_Ave_1_1']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_1']['14']) ? $v_arr_tmp['p_Ave_2_1']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				
				if($v_dry_thr){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μp Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}
				
				
				//μd Ave
				$v_jml_one = (!empty($v_arr_tmp['d_Ave_2_2']['14']) ? $v_arr_tmp['d_Ave_2_2']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_2']['14']) ? $v_arr_tmp['p_Ave_2_2']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				if($v_dry_thr){
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μd Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}	
				
				break;
			}
			
			if($v_row->idreport == '32'){ //dry 30C
				
				$v_ave = $this->cm->machine_ave($v_row->no_req, $v_row->idmachinetest_detail, $v_row->idformulir);
				$v_arr_tmp = array();
				foreach($v_ave as $key=>$v){
					$v_arr_tmp[$v['namefield']][] = $v['avg_val'];
				}
				
				//μp Ave
				$v_jml_one = (!empty($v_arr_tmp['p_Ave_1_1']['14']) ? $v_arr_tmp['p_Ave_1_1']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_1']['14']) ? $v_arr_tmp['p_Ave_2_1']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				
				if($v_dry_thr){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μp Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}
				
				
				//μd Ave
				$v_jml_one = (!empty($v_arr_tmp['d_Ave_1_2']['14']) ? $v_arr_tmp['d_Ave_1_2']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['d_Ave_2_2']['14']) ? $v_arr_tmp['d_Ave_2_2']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				if($v_dry_thr){
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μd Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}	
				
				break;
				
			}
			
			if($v_row->idreport == '34'){ //wet 40
				
				$v_ave = $this->cm->machine_ave($v_row->no_req, $v_row->idmachinetest_detail, $v_row->idformulir);
				$v_arr_tmp = array();
				foreach($v_ave as $key=>$v){
					$v_arr_tmp[$v['namefield']][] = $v['avg_val'];
				}
				
				//μp Ave
				$v_jml_one = (!empty($v_arr_tmp['p_Ave_1_1']['14']) ? $v_arr_tmp['p_Ave_1_1']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_1']['14']) ? $v_arr_tmp['p_Ave_2_1']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				
				if($v_dry_thr){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μp Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}
				
				
				//μd Ave
				$v_jml_one = (!empty($v_arr_tmp['d_Ave_1_2']['14']) ? $v_arr_tmp['d_Ave_1_2']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['d_Ave_2_2']['14']) ? $v_arr_tmp['d_Ave_2_2']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				if($v_dry_thr){
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μd Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}	
				
				break;
				
			}
			
			if($v_row->idreport == '35'){ //wet 50
				
				$v_ave = $this->cm->machine_ave($v_row->no_req, $v_row->idmachinetest_detail, $v_row->idformulir);
				$v_arr_tmp = array();
				foreach($v_ave as $key=>$v){
					$v_arr_tmp[$v['namefield']][] = $v['avg_val'];
				}
				
				//μp Ave
				$v_jml_one = (!empty($v_arr_tmp['p_Ave_1_1']['14']) ? $v_arr_tmp['p_Ave_1_1']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_1']['14']) ? $v_arr_tmp['p_Ave_2_1']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				
				if($v_dry_thr){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μp Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}
				
				
				//μd Ave
				$v_jml_one = (!empty($v_arr_tmp['p_Ave_1_2']['14']) ? $v_arr_tmp['p_Ave_1_2']['14'] : 0);	
				$v_jml_two = (!empty($v_arr_tmp['p_Ave_2_2']['14']) ? $v_arr_tmp['p_Ave_2_2']['14'] : 0);	
				
				$v_dry_thr = round((($v_jml_one + $v_jml_two)/2),4);	
				if($v_dry_thr){
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>μd Ave</td>";
					echo "<td align='right'>".number_format($v_dry_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_dry_thr = '';
				}	
				
				break;
				
			}
			
			if($v_row->idreport == '25'){ //Rheolograph Test
				
				$v_arr_one = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$v_arr_tmp = array();
				foreach($v_arr_one as $key=>$v){
					$v_arr_tmp[$v['namfields']][] = $v['value'];
				}				
				
				$i = 0;
				$v_idx_arr = '';
				$v_nol_lst = '';
				$v_tig_lst = '';
				$v_tig_idx = '';
				$v_six_lst = '';
				$v_six_idx = '';
				$v_max_pek = '';
				$v_max_idx = '';
				$v_com_one = '';
				$v_com_two = '';
				$v_max_tan = '';
				$v_tan_idx = '';
				
				if (!empty($v_arr_tmp)) {
					foreach($v_arr_tmp['TEMP'] as $v_num){
						
						if($v_num <= 0){						
							if($v_nol_lst == ''){
								$v_nol_lst = $v_num;
								$v_idx_arr = $i;
							}elseif($v_num > $v_nol_lst){
								$v_nol_lst = $v_num;
								$v_idx_arr = $i;
							}
							
						}elseif($v_num >= 29 && $v_num < 31){
							
							if($v_tig_lst == ''){
								$v_tig_lst = $v_num;
								$v_tig_idx = $i;
							}elseif($v_num > $v_tig_lst){
								$v_tig_lst = $v_num;
								$v_tig_idx = $i;
							}
						}elseif($v_num < 61 && $v_num >= 59){
							
							if($v_six_lst == ''){
								$v_six_lst = $v_num;
								$v_six_idx = $i;
							}elseif($v_num > $v_tig_lst){
								$v_six_lst = $v_num;
								$v_six_idx = $i;
							}
						}
						
						if($v_arr_tmp['Strain'][$i] == 0.1){
							
							if($v_max_pek == ''){
								$v_max_pek = $v_num;
								$v_max_idx = $i;
							}elseif($v_num > $v_max_pek){
								$v_max_pek = $v_num;
								$v_max_idx = $i;
							}
						}
						
						$i++;
						
					}
				}
				
				$j = 0;
				
				if (isset($v_arr_tmp['TAN'])) {
				
					foreach($v_arr_tmp['TAN'] as $v_tan){
						
						if($v_arr_tmp['Strain'][$j] == 0.1){						
							if($v_max_tan == ''){
								$v_max_tan = $v_tan;
								$v_tan_idx = $j;
							}elseif($v_tan > $v_max_tan){
								$v_max_tan = $v_tan;
								$v_tan_idx = $j;
							}
						}
						
						$j++;
					}
				}
				
				if(!isset($v_arr_tmp['TAN'][$v_idx_arr]) || !isset($v_arr_tmp['Ebs_'][$v_idx_arr])){
					$v_com_one = 0;
				}else{
					$v_com_one = @($v_arr_tmp['TAN'][$v_idx_arr]/(pow($v_arr_tmp['Ebs_'][$v_idx_arr],0.34)));
				}	
				
				if(!isset($v_arr_tmp['TAN'][$v_tig_idx]) || !isset($v_arr_tmp['Ebs_'][$v_tig_idx])){
					$v_com_two = 0;
				}else{
					$v_com_two = @($v_arr_tmp['TAN'][$v_tig_idx]/(pow($v_arr_tmp['Ebs_'][$v_tig_idx],0.34)));
				}
				
					
				if($v_max_pek != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>Tan Delta Peak (°C)</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['TEMP'][$v_max_idx])."</td>";
					echo "</tr>";
					
					$v_max_pek = '';
					if($v_max_tan == ''){ break; }						
				}
				
				if($v_max_tan != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>Tan Delta Peak</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['TAN'][$v_tan_idx])."</td>";
					echo "</tr>";
					
					$v_max_pek = '';
					if($v_com_one == ''){ break; }						
				}
				
				if($v_com_one){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>tand/ E'^(1/3)(0)</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_com_one)."</td>";
					echo "</tr>";
					
					$v_max_pek = '';
					//if($v_com_two == ''){ break; }
					
				}
				//echo 'test'.$v_com_two;
				if($v_com_two != '' || $v_com_two == 0){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>tand/ E'^(1/3)(30)</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_com_two)."</td>";
					echo "</tr>";
					
					
					$v_com_two = '';
					if($v_nol_lst == ''){ break; }
					
				}
				
				if($v_nol_lst!=''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>0 Tan &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E", $v_arr_tmp['TAN'][$v_idx_arr])."</td>";
					echo "</tr>";		
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>0 E' &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['Ebs_'][$v_idx_arr])."</td>";
					echo "</tr>";
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>0 E* &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['E_'][$v_idx_arr])."</td>";
					echo "</tr>";				
					
					$v_nol_lst = '';
					//if($v_tig_lst == ''){ break; }	
				}
				
				if($v_tig_lst!=''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>30 Tan &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['TAN'][$v_tig_idx])."</td>";
					echo "</tr>";	
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>30 E' &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['Ebs_'][$v_tig_idx])."</td>";
					echo "</tr>";	
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>30 E* &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['E_'][$v_tig_idx])."</td>";
					echo "</tr>";			
					
					$v_tig_lst = '';
					if($v_six_lst == '') {break; }
						
				}
				
				if($v_six_lst!=''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>60 Tan &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['TAN'][$v_six_idx],5)."</td>";
					echo "</tr>";	
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>60 E' &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E",$v_arr_tmp['Ebs_'][$v_six_idx])."</td>";
					echo "</tr>";	
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>60 E* &Delta;</td>";
					echo "<td align='right'>".sprintf("%.2E", $v_arr_tmp['E_'][$v_six_idx])."</td>";
					echo "</tr>";						
					
					$v_six_lst = '';
					break;
				}
				
				
			}
			
			
			if($v_row->idreport == '38') { //Lambourn Abrasion
			
				$v_arr_one = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$v_arr_tmp = array();
				foreach($v_arr_one as $key=>$v){
					$v_arr_tmp[$v['namfields']][] = $v['value'];
				}
				
				#print_r($v_arr_tmp);
				
				$i=0;
				$v_idx_sev = '';
				$v_idx_thr = '';
				$v_ave_val = '';
				
				$v_nil_sev = '';
				$v_nil_thr = '';
				if (!empty($v_arr_tmp)) {
					foreach($v_arr_tmp['LOAD_N'] as $v_hsl){
						
						if($v_hsl == 70){
							$v_idx_sev = $i;
						}elseif($v_hsl == 35){
							$v_idx_thr = $i;
						}
						
						$i++;
					}
					$v_nil_sev = $v_arr_tmp['ATG'][$v_idx_sev]; //AVE/TIME (gram/minute)
					$v_nil_thr = $v_arr_tmp['ATG'][$v_idx_thr]; //AVE  (ΔW/n)
					//echo $v_idx_thr;
				} else {
					$v_nil_sev = '';
					$v_nil_thr = '';
				}
				
				
				
				if($v_nil_sev != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>70N, 25%, 100</td>";
					echo "<td align='right'>".number_format($v_nil_sev,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_nil_sev = '';					
					if($v_nil_thr == ''){ break; }
					
				}
				
				if($v_nil_thr != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>35N, 50%, 45</td>";
					echo "<td align='right'>".number_format($v_nil_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_nil_sev = '';					
					break;
					
				}
			}
			
			if($v_row->idreport == '73') { //Lambourn Abrasion Extrim
			
				$v_arr_one = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$v_arr_tmp = array();
				foreach($v_arr_one as $key=>$v){
					$v_arr_tmp[$v['namfields']][] = $v['value'];
				}
				
				#print_r($v_arr_tmp);
				
				$i=0;
				$v_idx_sev = '';
				$v_idx_thr = '';
				$v_ave_val = '';
				
				$v_nil_sev = '';
				$v_nil_thr = '';
				if (!empty($v_arr_tmp)) {
					foreach($v_arr_tmp['LOAD_N'] as $v_hsl){
						
						if($v_hsl == 70){
							$v_idx_sev = $i;
						}elseif($v_hsl == 35){
							$v_idx_thr = $i;
						}
						
						$i++;
					}
					$v_nil_sev = $v_arr_tmp['ATG'][$v_idx_sev]; //AVE/TIME (gram/minute)
					$v_nil_thr = $v_arr_tmp['ATG'][$v_idx_thr]; //AVE  (ΔW/n)
					//echo $v_idx_thr;
				} else {
					$v_nil_sev = '';
					$v_nil_thr = '';
				}
				
				
				
				if($v_nil_sev != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>70N, 25%, 100</td>";
					echo "<td align='right'>".number_format($v_nil_sev,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_nil_sev = '';					
					if($v_nil_thr == ''){ break; }
					
				}
				
				if($v_nil_thr != ''){
					
					echo "<tr>";
					echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
					echo "<td width='75%' style='border-left:none;'>35N, 50%, 45</td>";
					echo "<td align='right'>".number_format($v_nil_thr,5,'.',',')."</td>";
					echo "</tr>";
					
					$v_nil_thr = '';					
					break;
					
				}
			}
			
			
			if($v_row->idreport == '7'){ //RPA (Payne Effect)			
				//Base Value machine fields
				$arr_7 = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$arr_temp_1 = array();
				foreach ($arr_7 as $key=>$v) {
					$arr_temp_1[$v['namfields']][] = $v['value'];
				}
				//print_r($arr_7);
				$j = 0;
				$arr_temp_2 = array();
				$v_res_one = 0;
				$v_res_two = 0;
				foreach($arr_temp_1['LABEL'] as $v) {
						//echo "<pre>";
						//print_r($v);
						//echo "</pre>";
						if ($v == "G'@1 %") {
							/*$arr_temp_2['g1'][0] = $arr_temp_1['B_1'][$j];
							$arr_temp_2['g1'][1] = $arr_temp_1['B_2'][$j];
							$arr_temp_2['g1'][2] = $arr_temp_1['B_3'][$j];
							$arr_temp_2['g1'][3] = $arr_temp_1['B_4'][$j];
							$arr_temp_2['g1'][4] = $arr_temp_1['B_5'][$j];
							$arr_temp_2['g1'][5] = $arr_temp_1['B_6'][$j];
							$arr_temp_2['g1'][6] = $arr_temp_1['B_7'][$j];*/
							$arr_temp_2['g1'][0] = $arr_temp_1['Result'][$j];
							//$v_res_one = ($arr_temp_1['B_1'][$j] + $arr_temp_1['B_2'][$j] + $arr_temp_1['B_3'][$j] + $arr_temp_1['B_4'][$j] + $arr_temp_1['B_5'][$j] + $arr_temp_1['B_6'][$j] + $arr_temp_1['B_7'][$j])/7;
							$v_res_one = $arr_temp_1['Result'][$j];
							//print_r(var_dump($arr_temp_2));
						}
						if ($v == "G'@1000 %") {
							/*$arr_temp_2['g100'][0] = $arr_temp_1['B_1'][$j];
							$arr_temp_2['g100'][1] = $arr_temp_1['B_2'][$j];
							$arr_temp_2['g100'][2] = $arr_temp_1['B_3'][$j];
							$arr_temp_2['g100'][3] = $arr_temp_1['B_4'][$j];
							$arr_temp_2['g100'][4] = $arr_temp_1['B_5'][$j];
							$arr_temp_2['g100'][5] = $arr_temp_1['B_6'][$j];
							$arr_temp_2['g100'][6] = $arr_temp_1['B_7'][$j];*/
							
							$arr_temp_2['g1'][0] = $arr_temp_1['Result'][$j];
							$v_res_two = $arr_temp_1['Result'][$j];
							//$v_res_two = ($arr_temp_1['B_1'][$j] + $arr_temp_1['B_2'][$j] + $arr_temp_1['B_3'][$j] + $arr_temp_1['B_4'][$j] + $arr_temp_1['B_5'][$j] + $arr_temp_1['B_6'][$j] + $arr_temp_1['B_7'][$j])/7;
						}
					$j++;
				}			
				
				$v_res_all = $v_res_one + $v_res_two;
				
				echo "<tr>";
				echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
				echo "<td width='75%' style='border-left:none;'>G' @1%, Kpa</td>";
				echo "<td align='right'>".number_format($v_res_one,5,'.',',')."</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
				echo "<td width='75%' style='border-left:none;'>G' @1000%, Kpa</td>";
				echo "<td align='right'>".number_format($v_res_two,5,'.',',')."</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td width='5%' style='border-right:none;'>&nbsp;</td>";
				echo "<td width='75%' style='border-left:none;'>ΔG', KPa</td>";
				echo "<td align='right'>".number_format($v_res_all,5,'.',',')."</td>";
				echo "</tr>";
					
				break;
			}
			
			//Rheolograph Strain sweep
			if ($v_row->idreport == "29") {
				$arr_29 = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$arr_temp_1 = array();
				foreach ($arr_29 as $key=>$v) {
					$arr_temp_1[$v['namfields']][] = $v['value'];
				}
				
				$min_ = min($arr_temp_1['E_']);
				$min__ = min($arr_temp_1['E__']);
				
				$max_ = max($arr_temp_1['E_']);
				$max__ = max($arr_temp_1['E__']);
				
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E' @ 0,1%, Pa</td>";
				echo "<td align='right'>".sprintf("%.2E",$min_)."</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E' @ 5%, Pa</td>";
				echo "<td align='right'>".sprintf("%.2E",($max_+$max__))."</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>Delta E', Pa</td>";
				echo "<td align='right'>".sprintf("%.2E",(($max_+$max__) - $min_))."</td>";
				echo "</tr>";
				
				/*
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>30 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				
				
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>60 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>TES AAA</td>";
				echo "</tr>";
				*/
				break;
			}
			
			//Gabo Honda Condition
			if ($v_row->idreport == "20") {
				$arr_29 = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$arr_temp_1 = array();
				foreach ($arr_29 as $key=>$v) {
					$arr_temp_1[$v['namfields']][] = $v['value'];
				}
				
				$index_get30 = getIndexResultForumlaGebo($arr_temp_1, 30);
								
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>30 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get30]) ? $arr_temp_1['tan'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get30]) ? $arr_temp_1['E___'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_bintang'][$index_get30]) ? $arr_temp_1['E_bintang'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				$index_get60 = getIndexResultForumlaGebo($arr_temp_1, 60);
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>60 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get60]) ? $arr_temp_1['tan'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get60]) ? $arr_temp_1['E___'][$index_get60] : 0),5,',','.')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_bintang'][$index_get60]) ? $arr_temp_1['E_bintang'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				break;
			}
			
			//Gabo Tension Condition
			if ($v_row->idreport == "319") {
				$arr_29 = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$arr_temp_1 = array();
				foreach ($arr_29 as $key=>$v) {
					$arr_temp_1[$v['namfields']][] = $v['value'];
				}
				
				$index_get30 = getIndexResultForumlaGebo($arr_temp_1, 30);
								
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>30 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get30]) ? $arr_temp_1['tan'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get30]) ? $arr_temp_1['E___'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_bintang'][$index_get30]) ? $arr_temp_1['E_bintang'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				$index_get60 = getIndexResultForumlaGebo($arr_temp_1, 60);
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>60 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get60]) ? $arr_temp_1['tan'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get60]) ? $arr_temp_1['E___'][$index_get60] : 0),5,',','.')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_bintang'][$index_get60]) ? $arr_temp_1['E_bintang'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				break;
			}
			
			//Gabo BDE
			if ($v_row->idreport == "21") {
				$arr_29 = $this->cm->list_value($v_row->idformulir,$v_row->idreport);
				$arr_temp_1 = array();
				foreach ($arr_29 as $key=>$v) {
					$arr_temp_1[$v['namfields']][] = $v['value'];
				}
				
				$index_get30 = getIndexResultForumlaGebo($arr_temp_1, 30);
								
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>30 &deg;C &nbsp;&nbsp; Tan d</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get30]) ? $arr_temp_1['tan'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_'][$index_get30]) ? $arr_temp_1['E_'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get30]) ? $arr_temp_1['E___'][$index_get30] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				
				$index_get60 = getIndexResultForumlaGebo($arr_temp_1, 60);
				
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>60 &deg;C &nbsp;&nbsp; Tan d X:".$index_get60."</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['tan'][$index_get60]) ? $arr_temp_1['tan'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E&quot;</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E_'][$index_get60]) ? $arr_temp_1['E_'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width='75%' style='border-left:none;' colspan='2'>E*</td>";
				echo "<td align='right'>".number_format((!empty($arr_temp_1['E___'][$index_get60]) ? $arr_temp_1['E___'][$index_get60] : 0),5,'.',',')."</td>";
				echo "</tr>";
				
				break;
			}
		
		?>
        
        	
              
    <?php } }  ?>
    </table>
    
		
        <?php
			function getIndexResultForumlaGebo($data, $c=30) {
					$index = 0;
					$arr_temp_1 = $data;
					switch($c) {
						case 30;
							$array_30 = array();
							$array_smaller30 = array();
							$index_get30 = "";
							$k30 =  0;
							$k30small = 0;
							foreach ($arr_temp_1['T'] as $k=>$v) {
								if ($v < 31 & $v >= 30) {
									$array_30[$k30]['v_T30'] = $v;
									$array_30[$k30]['i_T30'] = $k;
									$k30++;
								}
								
								if ($v < 30) {
									$array_smaller30[$k30small]['v_T30'] = $v;
									$array_smaller30[$k30small]['i_T30'] = $k;
									$k30small++;
								}
								
								if ($v == 30) {
									$index_get30 = $k;
								}
									
							}
							
							//get 30 > && 30.9 , Priority yang diambil jika tidak ditemukan nilai 30
							if ($index_get30 == "") {
								if(!empty($array_30)) {
									$smaller30X = 30.9;
									foreach($array_30  as $k1=>$v1) {
										if ($v1['v_T30'] < $smaller30X) {
											$smaller30X = $v1['v_T30'];
											$indexsmaller30X = $v1['i_T30'];
										}
									}
									$index_get30 = $indexsmaller30X;
								}
							}
							
							
							// Get Max < 30
							if ($index_get30 == "") {
								if(!empty($array_smaller30)) {
									$max30X = 0;
									//print_r($array_smaller30);
									foreach($array_smaller30  as $k2=>$v2) {
										
										if ($v2['v_T30'] > $max30X) {
											//$ = $v1['v_T30'];
											$indexdown30 = $v2['i_T30'];
										}
									}
									$index_get30 = $indexdown30;
								}
							}
							$index = $index_get30;
						break;
						case 60:
							
							$array_60 = array();
							$array_smaller60 = array();
							$index_get60 = "";
							$k60 =  0;
							$k60small = 0;
							
							foreach ($arr_temp_1['T'] as $k=>$v) {
								if ($v < 61 & $v >= 60) {
									$array_60[$k60]['v_T60'] = $v;
									$array_60[$k60]['i_T60'] = $k;
									$k60++;
								}
								
								if ($v < 60) {
									$array_smaller60[$k60small]['v_T60'] = $v;
									$array_smaller60[$k60small]['i_T60'] = $k;
									$k60small++;
								}
								
								if ($v == 60) {
									$index_get60 = $k;
								}
									
							}
							//print_r($array_60);
							//get 60 > && 60.9 , Priority yang diambil jika tidak ditemukan nilai 30
							if ($index_get60 == "") {
								if(!empty($array_60)) {
									$smaller60X = 60.9;
									foreach($array_60  as $k1=>$v1) {
										if ($v1['v_T60'] < $smaller60X) {
											$smaller60X = $v1['v_T60'];
											$indexsmaller60X = $v1['i_T60'];
										}
									}
									$index_get60 = $indexsmaller60X;
								}
							}
							
							
							// Get Max < 60
							if ($index_get60 == "") {
								if(!empty($array_smaller60)) {
									$max60X = 0;
									//print_r($array_smaller60);
									foreach($array_smaller60  as $k2=>$v2) {
										
										if ($v2['v_T60'] > $max60X) {
											//$ = $v1['v_T30'];
											$indexdown60 = $v2['i_T60'];
										}
									}
									$index_get60 = $indexdown60;
								}
							}
							$index = $index_get60;
							
						break;
					}
					return $index;
			}
		
		?>
        
</body>
</html>		