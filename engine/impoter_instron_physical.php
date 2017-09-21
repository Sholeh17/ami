<?php
	$workDir = "/home/upload/lab_source/instron_physical";
	#$workDir = "E:/file_machine/test";
	$isPrint = false;
	$mode = 3; 
	$owner = "RD";

	$noformulir = "";
	$idcompound = "";
	$isGet = false;
	/*--------------- konek database ---------------*/
	#$conn = mysqli_connect("localhost","root","root","labor1",3306);
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
			
			//check in database
			$sql = "SELECT COUNT(*) AS jml FROM labor_history_file_machine WHERE name_file = '$filename'";
			$result = mysqli_query($conn,$sql);
			$jml = 0;
			while($row = mysqli_fetch_array($result)) {
				$jml = $row['jml'];
			}
			
			if($jml == 0){
				/*--------------- var value --------------------*/
				$mod25 = array(); $mod50 = array(); $mod100 = array(); $mod150 = array(); $idreport = array();
				$mod300 = array(); $tensile = array(); $elongation = array(); $hardness = array();
			
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
								$noformulir[] = $v[1];
								#$hardness[] = $v[5];
								$idreport[] = $v[4];
								$isGet = true;
								break;
							case "Mean": 
								$isGet = false;
								break;
							default: break;
						}
						
						if($isGet){
							$hardness[] = is_numeric($v[5]) ? $v[5] : "";
							$mod25[] = $v[6];
							$mod50[] = $v[7];
							$mod100[] = $v[8];
							$mod150[] = $v[9];
							$mod300[] = $v[10];
							$tensile[] = $v[11];
							$elongation[] = $v[12];
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
					var_dump($hardness);
					print "<br>";
					var_dump($idreport);
					print "<br>";
					var_dump($mod25);
					print "<br>";
					var_dump($mod50);
					print "<br>";
					var_dump($mod100);
					print "<br>";
					var_dump($mod150);
					print "<br>";
					var_dump($mod300);
					print "<br>";
					var_dump($tensile);
					print "<br>";
					var_dump($elongation);
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
					#print $sql;										
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
							$idmachinetest = 11;
							$field_Hardenss = "Hardenss";
							$field_M_100 = "M_100mpa";
							$field_M_300 = "M_300mpa";
							$field_Tensile = "Tensile";
							$field_M_150 = "";
							$field_Elongation = "Elongation";
							$field_M_25 = "";
							$field_M_50 = "";
							break;
						case "Aging 24 jam": 
							$idmachinetest = 51;
							
							$field_Hardenss = "Hardnes";
							$field_M_100 = "M_100mpa";
							$field_M_300 = "M_300mpa";
							$field_Tensile = "Tensile";
							$field_M_150 = "";
							$field_Elongation = "Elongation";
							$field_M_25 = "";
							$field_M_50 = "";
							break;
						case "Aging 48 jam": 
							$idmachinetest = 53;
							
							$field_Hardenss = "Hardnes";
							$field_M_100 = "M_100mpa";
							$field_M_300 = "M_300mpa";
							$field_Tensile = "Tensile";
							$field_M_150 = "";
							$field_Elongation = "Elongation";
							$field_M_25 = "";
							$field_M_50 = "";
							break;
						case "HT": 
							$idmachinetest = 13;
							$field_Hardenss = "Hardnes";
							$field_M_100 = "M_100mpa";
							$field_M_300 = "M_300mpa";
							$field_Tensile = "Tensile";
							$field_M_150 = "";
							$field_Elongation = "Elongation";
							$field_M_25 = "";
							$field_M_50 = "";
							break;
						default : break;
					}
					
					if($status == "APPROVE"){
						$dt = date("Y-m-d H:i:s");
						$sql_del = "DELETE FROM labor_result_value_test WHERE idformulir = $idformulir  and idmachinereport = $idmachinetest";
						mysqli_query($conn, $sql_del);

						for($itungan = 0; $itungan < sizeof($mod25) / $nbOfArr; $itungan++){
							$idx = ($i*sizeof($mod25) / $nbOfArr)+$itungan;

							if($field_Hardenss != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_Hardenss', '$idcompound', '$hardness[$idx]', '$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_M_25 != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_M_25', 		'$idcompound', '$mod25[$idx]', 		'$dt', '$owner')" ;
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_M_50 != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_M_50', 		'$idcompound', '$mod50[$idx]', 		'$dt', '$owner')" ;
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_M_100 != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_M_100', 		'$idcompound', '$mod100[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_M_150 != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_M_150', 		'$idcompound', '$mod150[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_M_300 != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_M_300', 		'$idcompound', '$mod300[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_Tensile != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_Tensile', '$idcompound', '$tensile[$idx]', 		'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
							
							if($field_Elongation != ""){
								$sql_exe = "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
								$sql_exe .= "($idformulir, $idmachinetest, '$field_Elongation', 	'$idcompound', '$elongation[$idx]', 	'$dt', '$owner')";
								mysqli_query($conn, $sql_exe);
								$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$sql_exe\n<br>"; 
								#echo $error;
							}
						}
					}
				}
				
				$sql_exe = array();
				$sql_exe[0] = "INSERT INTO labor_history_file_machine (name_file) VALUES ('$filename')";
				
				foreach($sql_exe as $s){
					print $s . "<br>"."<br>";
					mysqli_query($conn, $s);
					$error = "MySQL error ".mysqli_errno($conn).": ".mysqli_error($conn)."\n<br>When executing:<br>\n$s\n<br>"; 
					echo $error;
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