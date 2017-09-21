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
		font-weight:bold;
		border:solid #eceaea thin;
		border-bottom:none;
		
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
<title>Report Kontrol Machine Test</title>
<body>
	<div class="div_title"><u>Report Kontrol Machine Test</u></div>
	<table border="1" width="100%" colspan="0" colspadding="0">
		<tr>
			<th>NO.</th>
			<th>NO. REQUEST</th>
			<th>REQUEST DATE</th>
			<th>SAMPLE NAME</th>
			<th>SAMPLE TYPE</th>
			<th>REQUESTER</th>
			<th>REQUESTER NAME</th>
			<th>PURPOSE PROJECT</th>
			<th>SIMPLE SPECIFICATION</th>
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
		?>
		<tr bgcolor="<?php echo ($v_no%2!=0)?'#f3f3f3"':'#FFFFFF'; ?>">
			<td align="center"><?=$v_no?>.</td>
			<td><?=$v_row->no_req?></td>
			<td align="center"><?=date("d-M-Y",strtotime($v_row->date_request))?></td>
			<td><?=$v_row->sample?></td>
			<td><?=$v_row->sample_category?></td>
			<td><?=$v_row->type_request?></td>
			<td><?=$v_row->request_by?></td>
			<td><?=$v_row->porpose?></td>
			<td><?=$v_row->sample_spec?></td>			           		
			<?php
			//content status	
			$v_sts = "Open";		
			$x_init = 0;
			$jml_cover = count($v_row->cover_machine);
			foreach($v_row->cover_machine as $v_sts_val){	// All Maschine Formulir Test			
				foreach ($v_row->value_input_machine as $v_vim) { // All Inputan				
					// Check Cover machin di valua input					
					if($v_sts_val->idmachinetest == $v_vim->idmachinereport){
						$x_init++;
						break;
					}
					
				}				
				
			}
			if ($jml_cover == $x_init)
				$v_sts = "Close";
			echo "<td align='center'>".$v_sts."</td>";
			
			//content machine
			foreach($v_lst_mch->result() as $v_row_mch)
			{
				$init_check = 0;
				foreach($v_row->value_input_machine as $v_machine) {
									
					if(($v_machine->idformulir == $v_row->idformulir) && ($v_machine->idmachinereport == $v_row_mch->idmachine))
						$init_check++;			
					}
					
					if($init_check > 0){
						$v_lbl = 'X';
						$v_bgc = '#99FF66';
					}else{
						$v_lbl = '-';
						$v_bgc = '#FF0000';
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