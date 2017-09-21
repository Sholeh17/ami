<?php
	$workDir = "/home/upload/lab_source/density";
	$isPrint = false;
	$mode = 3; 
	$owner = "RD";

	$noformulir = "";
	$idcompound = "";
	$isGet = false;
	/*--------------- konek database ---------------*/
	$conn = mysqli_connect("127.0.0.1","upload","qwerty123","labor",3306);

	if (mysqli_connect_errno()) {
		die("Could not connect database");
	}
	
	$listOfFile = scandir($workDir);
	var_dump($listOfFile);
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
			#print $sql;
			$result = mysqli_query($conn,$sql);
			$jml = 0;
			while($row = mysqli_fetch_array($result)) {
				$jml = $row['jml'];
			}
			
			if($jml == 0){
				/*--------------- var value --------------------*/
				$density = array(); $weight = array(); $volume = array();
				
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
					$itungan = 0;
					while(!feof($file)){
						$line = str_replace("\"","", fgets($file));
						$v = explode("\t", $line);
						//var_dump( $v);// . "<br>"; 
						#print "<br><br>";
						switch($itungan){
							case 0: 
								//kolom name
								break;
							case 1: 
								//unity
								break;
							default : 
								//value
								if(sizeof($v) == 1){
									//go away..!!!
								}
								else{
									$noformulir = str_replace("-","/", $v[5]);
									$density[] = $v[6];
									$weight[] = $v[7];
									$volume[] = $v[8];
								}
								break;
						}
						$itungan++;
					}
					fclose($file);
					
				} 
				catch (Exception $e) {
					mysqli_close($conn);
					die($e->getMessage());
				}
				
				if($isPrint){
					print $noformulir;
					print "<br>";
					var_dump($density);
					print "<br>";
					var_dump($weight);
					print "<br>";
					var_dump($volume);
					print "<br>";
				}
				
				/*--------------- proses datanya cuy ----------*/
				$status = ""; $idformulir = "";$idmachinetest = 8;
				$sql = "SELECT `status`, a.idformulir, b.`idmachinetest_detail`,a.`sample` FROM 			
																	labor_formulir_request_test a
														INNER JOIN 	labor_forumlir_item_test b 
														ON a.`idformulir` = b.`idformulir` WHERE no_req = '$noformulir'";
				$result = mysqli_query($conn,$sql);
				while($row = mysqli_fetch_array($result)) {
					$status = $row['status'];
					$idformulir = $row['idformulir'];
					//$idmachinetest = $row['idmachinetest_detail'];
					$idcompound = $row['sample'];
				}
				switch($status){
					case "": 
						//no_req is not registered yet
						print "$filename : no_req is not registered yet";
						continue;
						break;
					case "DRAFT": case "CHECKED": 
						//ignore 
						break;
					case "APPROVE": 
						//proses cuy
						break;
					default: break;
				}
				
				$sql_exe = array();
				if($status == "APPROVE"){
					$dt = date("Y-m-d H:i:s");
					$sql_exe[0] = "DELETE FROM labor_result_value_test WHERE idformulir = $idformulir";
					$sql_exe[1] = "";
					for($itungan = 0; $itungan < sizeof($density); $itungan++){
						if($itungan == 0){
							$sql_exe[1] .= "INSERT INTO labor_result_value_test (idformulir, idmachinereport, namfields, idcompound, `value`, datetrx, `owner`) VALUES ";
						}
						$sql_exe[1] .= "($idformulir, $idmachinetest, 'DENSITY', '$idcompound', $density[$itungan], '$dt', '$owner'),";
					}
					$sql_exe[1] = substr($sql_exe[1], 0, -1);
					$sql_exe[2] = "INSERT INTO labor_history_file_machine (name_file) VALUES ('$filename')";

					foreach($sql_exe as $s){
						mysqli_query($conn, $s);
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