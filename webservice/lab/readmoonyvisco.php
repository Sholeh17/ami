<?php
date_default_timezone_set("Asia/Bangkok");
include "db.inc.php";
connectDB();

$id_machine_mV = "10"; //MOONEY VISCOSITY
$param_name_mV = "ML_4";

$id_machine_mS = "9"; //Mooney  Scorchtime
$param_name_mS_0 = "ML_4";
$param_name_mS_1 = "TS_5";
$param_name_mS_2 = "TS_35";

$dir = "/home/upload/lab_source/moony_rheo"; //"file_moony_visco";
$files2 = scandir($dir, 1);	
$arrXml = array();

foreach ($files2 as $file) {
   if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'txt')
        {
			//if (checkLogFile($file))  // Datanya di server slalu ditampah
				//continue;
			
			if (strtoupper(substr($file,0,2)) == "MV" || strtoupper(substr($file,0,2)) == "SC") { 	
				$arrXml[] = $file;
				//setLogFileRead($file);
			}
        }
}

//print_r($arrXml);


foreach ($arrXml as $vfiles) {
	$fileTxt = $dir."/".$vfiles;
	$textLine = readLineText($fileTxt);	
	
	foreach ($textLine as $vTL) {
		$arr_data = array();
		if ($vTL) {
			$splitTextLine = explode(",",$vTL);
			//print_r($splitTextLine);
			$mJenis = $splitTextLine[0];
			$no_formulir = "RDLAB/".$splitTextLine[1]."".(date("Y"));
			
			if ($mJenis == "MV") {
				$arr_data['no_formulir'] = $no_formulir;
				$arr_data["ML_4"] = str_replace('"','',$splitTextLine[12]);
				//print_r($arr_data);
				//Check No Formulir Exist
				$row_formulir = get_row_data_formulir_test($arr_data['no_formulir']);
				
				if (!$row_formulir) 
					continue;
				$param_val = $arr_data["ML_4"];	
				$batch = $splitTextLine[2];
				//print_r($row_formulir);
				
				//Insert Data
				update_data_test_by_id_formulir($row_formulir[0], 
									$row_formulir[1], $row_formulir[2], 
									$id_machine_mV, 
									$param_name_mV, 
									$param_val, 
									$batch);
				setLogFileRead($vfiles);
			} elseif ($mJenis == "SC") {
				$arr_data['no_formulir'] = $no_formulir;	
				$arr_data["ML_4"] = str_replace('"',"",$splitTextLine[11]);	
				$arr_data["TS_5"] = str_replace('"',"",$splitTextLine[12]);
				$arr_data["TS_35"]= str_replace('"',"",$splitTextLine[13]);
				
				//Check No Formulir Exist
				$row_formulir = get_row_data_formulir_test($arr_data['no_formulir']);
				//print_r($row_formulir);
				if (!$row_formulir) 
					continue;
				
				$param_val = $arr_data["ML_4"];	
				$batch = $splitTextLine[2];
				
				//Insert Data TS_5
				update_data_test_by_id_formulir($row_formulir[0], 
									$row_formulir[1], $row_formulir[2], 
									$id_machine_mS, 
									$param_name_mS_0, 
									$param_val, 
									$batch);
				
					
				$param_val = $arr_data["TS_5"];	
				$batch = 0;//$splitTextLine[2];
				
				//Insert Data TS_5
				update_data_test_by_id_formulir($row_formulir[0], 
									$row_formulir[1], $row_formulir[2], 
									$id_machine_mS, 
									$param_name_mS_1, 
									$param_val, 
									$batch);
				
				$param_val = $arr_data["TS_35"];	
				$batch = 0;					
				update_data_test_by_id_formulir($row_formulir[0], 
									$row_formulir[1], $row_formulir[2], 
									$id_machine_mS, 
									$param_name_mS_2, 
									$param_val, 
									$batch);	
				setLogFileRead($vfiles);									
				
			}	
			
		}
	}
	
}


function readLineText($fl) {
	$arrLine = array();
	$file = fopen($fl,"r");
	$j = 0;
	while(!feof($file)) {
	  $txLine = fgets($file);
	  if ($txLine == null or strlen($txLine) <= 5)
	  	continue;
	  $arrLine[$j] = $txLine;
	  $j++;
	}
	//print_r($arrLine);
	fclose($file);
	return $arrLine;
}







?>