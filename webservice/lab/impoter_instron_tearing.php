<?php
	$workDir = "/home/upload/lab_source/instron_tearing";
	//$workDir = "E:/file_machine/test";
	$isPrint = false;
	$mode = 3; 
	$owner = "RD";

	$noformulir = "";
	$idcompound = "";
	$isGet = false;
	/*--------------- konek database ---------------*/
	//$conn = mysqli_connect("localhost","root","root","labor1",3306);
	$conn = mysqli_connect("127.0.0.1","upload","qwerty123","labor",3306);

	if (mysqli_connect_errno()) {
		die("Could not connect database");
	}
	
	$listOfFile = scandir($workDir);
	
	try {
		foreach($listOfFile as $filename){
			if ($filename == '.' || $filename == '..') {
				continue;
			}
			
			$path_info = pathinfo($workDir . DIRECTORY_SEPARATOR  . $filename);
			if($path_info['extension'] != "csv"){
				continue;
			}
			
			//check in database
			$sql = "SELECT COUNT(*) AS jml FROM labor_history_file_machine WHERE name_file = '$filename'";
			$result = mysqli_query($conn,$sql);
			$jml = 0;
			while($row = mysqli_fetch_array($result)) {
				$jml = $row['jml'];
			}
			
			if($jml == $jml){
				/*--------------- var value --------------------*/
				$thickness = array(); $mload = array(); $strength = array(); $idreport = array(); $N_mm = array();
			
				/*--------------- open file --------------------*/
				try {
					$filename1 = $workDir . DIRECTORY_SEPARATOR  . $filename;
					$file = fopen($filename1, "r");
					if(file_exists($filename1)){
						if($isPrint){
							print "Available" . "<br>";
						}
					}
					else{ 
						if($isPrint){
							print "Not Available" . "<br>";
						}
						die("File not Found");
						
					}
					
					while(!feof($file)){
						$line = str_replace("\"","", fgets($file));
						$v = explode(",", $line);
						switch($v[0]){
							case "1": 
								$isGet = true;
								break;
							case "Mean": 
								$isGet = false;
								break;
							default: break;
						}
						
						if(($v[1] != "") && $isGet){
							$noformulir[] = $v[1];
							$idreport[] = $v[4];
						}
						
						if($isGet){
							$thickness[] = $v[5];
							$mload[] = $v[6];
							$strength[] = $v[7];
							$N_mm = '';
						}
					}
					fclose($file);
				} 
				catch (Exception $e) {
					mysqli_close($conn);
					die($e->getMessage());
				}

				if($isPrint){
					print "<br>";
					var_dump($noformulir);
					print "<br>";
					var_dump($idreport);
					print "<br>";
					var_dump($thickness);
					print "<br>";
					var_dump($mload);
					print "<br>";
					var_dump($strength);
					print "<br>";
				}
				
				/*--------------- proses datanya cuy ----------*/
				$nbOfArr = sizeof($noformulir);
				for($i = 0; $i < $nbOfArr; $i++){
					$status = ""; $idformulir = "";
					$sql = "SELECT `status`, a.idformulir, b.`idmachinetest_detail`,a.`sample` FROM 			
																		labor_formulir_request_test a
															INNER JOIN 	labor_forumlir_item_test b 
															ON a.`idformulir` = b.`idformulir` WHERE no_req = '$noformulir[$i]'";
					print $sql;										
					$result = mysqli_query($conn,$sql);
					while($row = mysqli_fetch_array($result)) {
						$status = $row['status'];
						$idformulir = $row['idformulir'];
						$idcompound = $row['sample'];
					}
					switch($status){
						case "": 
							//no_req is not registered yet
							break;
						case "DRAFT": case "CHECKED": 
							//ignore 
							break;
						case "APPROVE": 
							//proses cuy
							break;
						default: break;
					}
					
					switch($idreport[$i]){
						case "Fresh": 
							$idmachinetest = 15;
							
							$field_Thickness = "N_mm";
							$field_MaximumLoad = "MaximumLoad";
							$field_TearingStrength = "Tear_Strength";
							
							$field_N_mm = "";
							break;
						case "Aging 24 jam": 
							$idmachinetest = 316;
							$field_Thickness = "Thickness";
							$field_MaximumLoad = "MaximumLoad";
							$field_TearingStrength = "Tear_Strength";
							
							$field_N_mm = "N_mm";
							break;
						case "Aging 48 jam": 
							$idmachinetest = 317;
							
							$field_Thickness = "Thickness";
							$field_MaximumLoad = "MaximumLoad";
							$field_TearingStrength = "Tear_Strength";
							
							$field_N_mm = "N_mm";
							break;
						default : break;
					}
					print "<br>".($status)."<br>"."<br>";
					#$sql_exe = array();
					if($status == "APPROVE"){
						$dt = date("Y-m-d H:i:s");
						$sql_del = "DELETE FROM labor_result_value_test WHERE idformulir = $idformulir  and idmachinereport = $idmachinetest";
						mysqli_query($conn, $sql_del);
						$i_temp = sizeof($thickness) / $nbOfArr; //nbOfArr = jumlah formulir,  
						for($itungan = 0; $itungan < $i_temp; $itungan++){
							$idx = ($i*$i_temp)+$itungan;
						#for($itungan = 0; $itungan < sizeof($thickness) / $nbOfArr; $itungan++){
						#	$idx = ($i*sizeof($thickness) / $nbOfArr)+$itungan;
							#print $idx ."__".$nbOfArr. "__".$itungan. "<br>" . "<br>";
							#if($itungan == 0){
								#$sql_exe .= "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
							#}
							
							if($field_Thickness != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, 'Thickness', 				'$idcompound', '$thickness[$idx]', 		'$dt', '$owner')" ;
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_MaximumLoad != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, 'MaximumLoad', 			'$idcompound', '$mload[$idx]', 			'$dt', '$owner')" ;
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_TearingStrength != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, 'Tear_Strength', 			'$idcompound', '$strength[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								echo $error;
							}
							
							if($field_N_mm != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, 'Tear_Strength', 			'$idcompound', '$N_mm[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
						}
						#$sql_exe = substr($sql_exe, 0, -1);
						
						/*for($j = 0; $j < 2 ; $j++){
							mysqli_query($conn, $sql_exe[$j]);
							$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe[$j]\n<br>"; 
							echo $error;
						}*/
					}
				}
				
				
				if($jml == 0){
					$sql_exe = array();
					
					$sql_exe[0] = "INSERT INTO labor_history_file_machine (name_file) VALUES ('$filename')";
					
					foreach($sql_exe as $s){
						#print $s . "<br>"."<br>";
						mysqli_query($conn, $s);
						$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$s\n<br>"; 
						echo $error;
					}
				}
			}
			else{
				print "File has been uploaded";
			}
		}
	} catch (Exception $e) {
		print $e->message;
	}
	
	mysqli_close($conn);
	
?>