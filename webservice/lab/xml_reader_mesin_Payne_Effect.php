<?php
date_default_timezone_set("Asia/Bangkok");
include "db.inc.php";
//connectDB();
$id_machine = "7"; //Payne Effect
$param_name = "Result";

//$dir = "/home/upload/lab_source/rpa/";//"file_MW90C";
//$files2 = scandir($dir, 1);	
//$arrXml = array();
/*$thelist = "";
foreach ($files2 as $file) {
   if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml')
        {
			if (checkLogFile($file))
				continue;
            $arrXml[] = $file;
			//setLogFileRead($file);
			//break;
        }
}
*/

$arrXml[] = "TestResult20161123104955.xml";

//Read XML
$name_tag = array();
$arr_no_reg = array();
$arr_param_val = array();
foreach ($arrXml as $vfiles) {
	$source = file_get_contents($dir."".$vfiles);
	//echo "\n".$dir."".$vfiles;
	$xml = xml2array($source, 1, 'attribute');
	$desc_mesin =  @split("\n", $xml["TEST"]["HEADER"]["value"]);
	$mesin_x = split("Identifier", $desc_mesin[3]);
	$mesinH = (trim($mesin_x[1]));
	print 'Mesin yang digunakan: '.$mesinH ;
	if ($mesinH != "Payne Effect")
		continue;
		
	foreach ($xml as $v) {
		foreach($v as $v2) {
			if ($v2) {
				foreach($v2 as $v3) {
					//Get NO Registrasi Formulir
					$line_reg = @split("\n", $v3['value']);
					$arr_no_reg[] = $line_reg;
					
					$line_par = @split("\n", $v3['SIMPLEDATA']['value']);
					$arr_param_val[] = $line_par;
					//print_r($v3['SIMPLEDATA']);
				}
			}
		}
	}
	
	$get_arr_no_reg = $arr_no_reg[1];
	// No registrasi
	$no_reg = explode("RDLAB",$get_arr_no_reg[2]);
	$no_reg = "RDLAB".trim($no_reg[1]);
	
	//Check di table formulir
	$row_formulir = get_row_data_formulir_test($no_reg);
	if (!$row_formulir) 
		continue;
	
	//print_r($arr_param_val);
	//No Batch No urut di Trx
	$batch = explode("Batch",$get_arr_no_reg[3]);
	$batch = trim($batch[1]);
	//echo $batch;
	//print_r($arr_param_val);
	//Untuk Paramater G_02_min 
	//print_r($arr_param_val);
	delet_payneffect($row_formulir[0],$id_machine);
	$params_val_x = array();
	foreach ($arr_param_val[3] as $dd=>$vv) {
		if($vv != "") {
			$param_val = explode("kPa", $vv);
			if (substr(trim($param_val[0]),0,2) == "G'") {
				$par_value = trim($param_val[1]);
				$label_val = trim(str_replace("'",'`',$param_val[0]));
				
				//Value
				InsertPayneEffect_data_test_by_id_formulir($row_formulir[0], $row_formulir[1], $row_formulir[2], $id_machine, $param_name, $par_value, $label_val,  $batch);
				
			}
		}
	}
	
	
	
	//$param_val = explode("kPa",$arr_param_val[4][1]);
	//$param_val = trim($param_val[1]);
	//echo $param_val;
	//update_data_test_by_id_formulir($row_formulir[0], $row_formulir[1], $row_formulir[2], $id_machine, $param_name, $param_val,  $batch);
	
	
	$arr_no_reg = array();
	$arr_param_val = array();
	
	//Log files
	setLogFileRead($vfiles);
	//break;
	
}
echo "Success";


?>
