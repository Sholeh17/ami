<?php
date_default_timezone_set("Asia/Bangkok");
include "db.inc.php";
connectDB();
$id_machine = "6"; //RPA (MW 90)
$param_name = "G_1_min";

$dir = "/home/upload/lab_source/rpa/";//"file_MW90C";
$files2 = scandir($dir, 1);	
$arrXml = array();
$thelist = "";
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

# $arrXml[] = "TestResult20161130112330.xml";
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
	
	//echo "\n".$vfiles;
	//if ($vfiles != "TestResult20160802090727.xml")
		//continue;
		
	//echo "\n".$dir."".$vfiles;	
	
	if ($mesinH != "MW 90C")
		continue;
	
	$desc_formulir =  @split("\n", $xml["TEST"]["HEADER"]["SAMPLEIDENTIFICATION"]["value"]);
	$a = @split("	", $desc_formulir[2]);
	$batchX = split("Batch", $desc_formulir[3]);
	$batchXXX = (trim($batchX[1]));
	
	#$arr_no_reg[] = $a[1];
	$desc_value =  $xml["TEST"]['SUBTEST'][2]['SIMPLEDATA']['value'];

	$desc_value = @split("\n", $desc_value);
		#var_dump($desc_value);
	#die();G'@1 min	
	foreach($desc_value as $val){
		#print $val . "<br>";
		if(strpos($val, "'@1")){
			#print "jancuk";
			$param_val = explode("kPa",$val);
			$param_val = trim($param_val[1]);
		}
	}
	
	if(!isset($param_val)){
		continue;
	}
	#var_dump($param_val);
	#die();
	#print $desc_formulir[2];
	#$mesinH = (trim($desc_formulir[2]));
	#echo $mesinH;
	#print_r($desc_formulir);

	#die;
	
	#$i = 0;
	#print_r ($desc_mesin);
	#foreach ($xml as $v) {
		//foreach($v as $v2) {
			/*if ($v2) {
				foreach($v2 as $v3) {
					//print_r($v2); print "<br />"; print "__";
					//Get NO Registrasi Formulir
					$line_reg = @split("\n", $v2['value']);
					$arr_no_reg[] = $line_reg;
					//print_r ($line_reg);
					$line_par = @split("\n", $v['SIMPLEDATA']['value']);
					$arr_param_val[] = $line_par;
					print_r($line_par);
				}
			}*/
			
					#print $i;
					#$i++;
					
		//}
		
		//print_r($xml['TEST']);
	#}
	//print_r($arr_no_reg);
	#$get_arr_no_reg = $arr_no_reg[1];
	// No registrasi
	#$no_reg = explode("RDLAB",$get_arr_no_reg[2]);
	//print_r($no_reg);
	#if (!isset($no_reg[1])) { 
	#	echo $vfiles."Format No Reg Salah \n";
	#	continue;
	#}
		
	$no_reg = $a[1];//"RDLAB".trim($no_reg[1]);
	
	//Check di table formulir
	$row_formulir = get_row_data_formulir_test($no_reg);
	#var_dump($row_formulir);die();
	//print_r($row_formulir);
	if (!$row_formulir) 
		continue;
	
	//print_r($arr_param_val);
	//No Batch No urut di Trx
	#$batch = explode("Batch",$get_arr_no_reg[3]);
	#$batch = trim($batch[1]);
	//echo $batch;
	//print_r($arr_param_val);
	//Untuk Paramater G_02_min 
	//print_r($arr_param_val);
	#$param_val = explode("kPa",$arr_param_val[4][1]);
	#$param_val = trim($param_val[1]);
	//echo $param_val;
	update_data_test_by_id_formulir($row_formulir[0], $row_formulir[1], $row_formulir[2], $id_machine, $param_name, $param_val, $batchXXX);
	
	
	$arr_no_reg = array();
	$arr_param_val = array();
	
	//Log files
	setLogFileRead($vfiles);
	//break;
	//die;
	
}
echo "Success";


?>
