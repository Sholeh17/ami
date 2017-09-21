<?php
date_default_timezone_set("Asia/Bangkok");
include "db.inc.php";
connectDB();

$dir = "/home/upload/lab_source/moony_rheo/"; //"rheo";
$files2 = scandir($dir, 1);	
$arrXml = array();

foreach ($files2 as $file) {
   if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'txt')
        {
			//if (checkLogFile($file))  Datanya di server slalu ditampah
				//continue;
			
			if (strtoupper(substr($file,0,2)) == "MD") {	
				$arrXml[] = $file;
			}
        }
}
//print_r($arrXml);

foreach ($arrXml as $vfiles) {
	$fileTxt = $dir."/".$vfiles;
	$textLine = readLineText($fileTxt);	
	$iend = count($textLine);
	$istart = 0;//($iend-2);
	$new_textLine = array();
	$xLoop = 0;
	//print_r($textLine);
	//Looping hasil dari text file
	for($ix = $istart; $ix < $iend; $ix++) {
		$new_textLine[$xLoop] =  $textLine[$ix];
		$xLoop++;
	}
	
	foreach ($new_textLine as $vTL) {
		$arr_data = array();
		if ($vTL) {
			$splitTextLine = explode("," ,$vTL);
			//print_r($splitTextLine);
			$mJenis = $splitTextLine[0];
			$no_formulir = "RDLAB/".trim($splitTextLine[1])."".(date("Y"));
			//echo $no_formulir."\n";
			//Check No Formulir Exist
			$row_formulir = get_row_data_formulir_test($no_formulir);
			if (!$row_formulir) 
				continue;
			
			$arr_field_val = array();
			$id_machine = 0;
			//echo trim(strtoupper($splitTextLine[3]))."\n";
			switch (trim(strtoupper($splitTextLine[3]))) {
				case "RHEO165":
						$arr_field_val = array(
									"TEST_TIME"=>str_replace('"',"",$splitTextLine[4]), 
									"TEST_TEMP"=>str_replace('"',"",$splitTextLine[5]), 
									"MH"=>str_replace('"',"",$splitTextLine[6]),
									"ML"=>str_replace('"',"",$splitTextLine[7]),
									"TC10"=>str_replace('"',"",$splitTextLine[8]),
									"TC50"=>str_replace('"',"",$splitTextLine[9]),
									"TC90"=>str_replace('"',"",$splitTextLine[10]),
									"TC100"=>str_replace('"',"",$splitTextLine[11]),
									"TS1"=>str_replace('"',"",$splitTextLine[12]),
									"TS2"=>str_replace('"',"",$splitTextLine[13]));
						$id_machine = 2;	
						//print_r($arr_field_val);		
				break;
				case "RHEO160":
					$arr_field_val = array(
									"TEST_TIME"=>str_replace('"',"",$splitTextLine[4]), 
									"TEST_TEMP"=>str_replace('"',"",$splitTextLine[5]), 
									"MH"=>str_replace('"',"",$splitTextLine[6]),
									"ML"=>str_replace('"',"",$splitTextLine[7]),
									"TC10"=>str_replace('"',"",$splitTextLine[8]),
									"TC50"=>str_replace('"',"",$splitTextLine[9]),
									"TC90"=>str_replace('"',"",$splitTextLine[10]),
									"TC100"=>str_replace('"',"",$splitTextLine[11]),
									"TS1"=>str_replace('"',"",$splitTextLine[12]),
									"TS2"=>str_replace('"',"",$splitTextLine[13]));
						$id_machine = 4;		
				break;
				case "RHEO145":
					$arr_field_val  = array(
									"TEST_TIME"=>str_replace('"',"",$splitTextLine[4]), 
									"TEST_TEMP"=>str_replace('"',"",$splitTextLine[5]), 
									"MH"=>str_replace('"',"",$splitTextLine[6]),
									"ML"=>str_replace('"',"",$splitTextLine[7]),
									"TC10"=>str_replace('"',"",$splitTextLine[8]),
									"TC50"=>str_replace('"',"",$splitTextLine[9]),
									"TC90"=>str_replace('"',"",$splitTextLine[10]),
									"TC100"=>str_replace('"',"",$splitTextLine[11]),
									"TS1"=>str_replace('"',"",$splitTextLine[12]),
									"TS2"=>str_replace('"',"",$splitTextLine[13]));
						$id_machine = 3;			
				break;
				case "RHEO195":
					$arr_field_val = array(
									"MH"=>str_replace('"',"",$splitTextLine[6]),
									"ML"=>str_replace('"',"",$splitTextLine[7]),
									"tc10"=>str_replace('"',"",$splitTextLine[8]),
									"tc50"=>str_replace('"',"",$splitTextLine[9]),
									"tc90"=>str_replace('"',"",$splitTextLine[10]));
					//print_r($row_formulir);				
					$id_machine = 1;				
				break;
			}
			
			//Insert Data
			//Delete Init
			//delet_payneffect($row_formulir[0], $id_machine);
			foreach ($arr_field_val as $k=>$v) {
				$param_name = $k;
				$param_val = $v;
				$batch = str_replace('"',"",$splitTextLine[2]);
				//echo $param_val."\n";
				update_data_test_by_id_formulir($row_formulir[0], 
									$row_formulir[1], $row_formulir[2], 
									$id_machine, 
									$param_name, 
									$param_val, 
									$batch);
			}
			
			//if (trim(strtoupper($splitTextLine[3])) == "RHEO165")
			//	break;
				
			setLogFileRead($vfiles);
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