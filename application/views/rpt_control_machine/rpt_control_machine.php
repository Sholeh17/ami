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
<title>Report Control Machine Test</title>
<body>
	<div class="div_title"><u>Report Control Machine Test</u></div>
    <table border="0" colspan="0" colspadding="0" width="45%">
    	<tr>
        	<td bgcolor="#FFFF99" width="5%"></td>
            <td width="40%">Tipe mesin tidak ada dalam forumlir test.</td>
        </tr>
        <tr>
        	<td bgcolor="#99FF66"></td>
            <td>Tipe Mesin ada dalam formulir dan sudah diinputkan nilainya.</td>
        </tr>
        <tr>
        	<td bgcolor="#FF0000"></td>
            <td>Tipe Mesin ada dalam formulir tapi belum diinputkan nilainya.</td>
        </tr>
    </table>
    <br>
	<table border="1" width="100%" colspan="0" colspadding="0">
		<tr>
			<th>NO.</th>
			<th>NO. REQUEST</th>
			<th>REQUEST DATE</th>
            <th>DATE LINE</th>
            <th>TGL TERIMA SAMPLE</th>
			<th>SAMPLE NAME</th>
			<th>SAMPLE TYPE</th>
			<th>REQUESTER</th>
			<th>REQUESTER NAME</th>
			<th>PURPOSE PROJECT</th>
			<th>SIMPLE SPECIFICATION</th>
            <th>Criteria</th>
            <th>Scale</th>
			<th>REQUEST STATUS</th>
			<th>STATUS</th>		
			<?php
				//List Machine
				foreach($v_lst_mch->result() as $v_row_mch){
			?>
					<th><?=$v_row_mch->name?></th>
			<?php
			}
			?>
		</tr>
		<?php
			$v_no = 1;
			$x = 0;
			foreach($frm_test as $v_row){ //content header
			
			if($v_row->status == 'APPROVE'){
				$v_bgc_req = '#99FFFF';
			}else{
				$v_bgc_req = '';
			}
		?>
		<tr bgcolor="<?php echo ($v_no%2!=0)?'#f3f3f3"':'#FFFFFF'; ?>">
			<td align="center"><?=$v_no?>.</td>
			<td><?=$v_row->no_req?></td>
			<td align="center"><?=date("d-M-Y",strtotime($v_row->date_request))?></td>
            <td align="center"><?=date("d-M-Y",strtotime($v_row->date_line))?></td>
            <td align="center"><?=date("d-M-Y",strtotime($v_row->date_reciept_sample))?></td>
			<td><?=$v_row->sample?></td>
			<td><?=$v_row->sample_category?></td>
			<td><?=$v_row->type_request?></td>
			<td><?=$v_row->request_by?></td>
			<td><?=$v_row->porpose?></td>
			<td><?=$v_row->sample_spec?></td>
            <td><?php
				switch ($v_row->criteria) {
					case "Reguler":
						echo "Low";
					break;
					
					case "Urgent":
						echo "Medium";
					break;
					
					case "Top Urgent" :
						echo "High";
					break;
				}
				
				?></td>
            <td><?=$v_row->scale?></td>
			<td bgcolor="<?=$v_bgc_req?>"><?=$v_row->status?></td>			           		
			<?php
			//content status	
			$v_sts = "Open";		
			$jml_cover = count($v_row->cover_machine);
			$x_init = 0;
			
			foreach($v_row->cover_machine as $v_sts_val){	// All Maschine Formulir Test			
				foreach ($v_row->value_input_machine as $v_vim) { // All Inputan				
					// Check Cover machin di valua input					
					if($v_sts_val->idmachinetest == $v_vim->idmachinereport){
						$x_init++;
						break;
					}
					
				}				
				//echo $x_init.'<br>';				
			}
			
			//echo $jml_cover.':'.$x_init;
			if ($jml_cover == $x_init){
				$v_sts = "Close";
				$v_bgc_sts = '#99FF66';
			}else{
				$v_sts = "Open";
				$v_bgc_sts = '#FF0000';
			}
			echo "<td align='center' bgcolor='".$v_bgc_sts."'>".$v_sts."</td>";
			
			//content machine
			foreach($v_lst_mch->result() as $v_row_mch)
			{
				$init_check = 2;
				$date_test = "X";				
				foreach($v_row->cover_machine as $v_frm_mch){						
					if(($v_frm_mch->idformulir == $v_row->idformulir) && ($v_frm_mch->idmachinetest_detail == $v_row_mch->idmachine)){					
						$init_check = 0;
						$date_test = "X";	
						foreach($v_row->value_input_machine as $v_machine) {
							if(($v_frm_mch->idmachinetest_detail == $v_machine->idmachinereport) && $v_machine->value!=''){
								$init_check = 1;
								$date_test = $v_machine->update_date;	
							}
						}
					}
				}
				
				if($init_check == 1){ //hijau 
					$v_lbl = $date_test;//'X';
					$v_bgc = '#99FF66';
				}elseif($init_check == 0){ //merah
					$v_lbl = '-';
					$v_bgc = '#FF0000';
				}elseif($init_check == 2){ //kuning
					$v_lbl = '';
					$v_bgc = '#FFFF99'; 
				}				
			?>
                    
                    <td align="center" bgcolor="<?=$v_bgc?>"><?=$v_lbl?></td>
                    <?php
			}
				$result_row = "";
			?>						
		</tr>		
		<?php 
			$x++;
			$v_no++; 
			} 
		?>
	</table>
		
</body>
</html>
<script>
	document.getElementById("satatus_<?=$x;?>").innerHTML =  "<?=$result_row;?>";
</script>		