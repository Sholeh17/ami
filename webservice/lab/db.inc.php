<?php
global $link;
function connectDB() {
	$link = mysql_connect("192.168.2.9","sholeh","sholeh") or die("Not Connected");
			mysql_select_db("labor",$link) or die(mysql_error());
}

function setLogFileRead($namefile) {
	$q = "REPLACE INTO labor_history_file_machine (name_file) VALUES('".addslashes($namefile)."')";
	mysql_query($q);
}

function checkLogFile($name_file) {
	$q = "SELECT * FROM labor_history_file_machine where name_file = '".$name_file."'";
	$query = mysql_query($q);
	$num_rows = mysql_num_rows($query);
	if ($num_rows > 0)
		return true;
	return false;
}

function get_row_data_formulir_test($no_formlir) {
	$q = "SELECT idformulir, sample as idcompound, type_request, no_req 
					FROM labor_formulir_request_test 
					WHERE no_req = '".$no_formlir."' #AND status = 'APPROVE'";
	$query = mysql_query($q);
	$num_rows = mysql_num_rows($query);
	$row = mysql_fetch_row($query);
	if ($num_rows > 0)
		return $row;
	return false;
}

function update_data_test_by_id_formulir($idformulir, $sample, $mode, $id_machine, $param_name, $param_val, $batch ="") {
	$qCheck = "SELECT * FROM labor_result_value_test 
					WHERE idformulir = '".$idformulir."' AND idmachinereport = '".$id_machine."' 
						AND namfields = '".addslashes($param_name)."' AND idcompound = '".$sample."' AND batch = '".$batch."'
				";
	$query = mysql_query($qCheck);		
	$num_rows = mysql_num_rows($query);
	//echo $num_rows."\n";
	if ($num_rows > 0) {
		if ($param_val == "")
			return;
			$qupdate = "UPDATE labor_result_value_test 
							SET value = '".addslashes($param_val)."', lock_row = '0' 
									WHERE idformulir = '".$idformulir."' AND idmachinereport = '".$id_machine."' 
												AND namfields = '".addslashes($param_name)."' AND idcompound = '".$sample."' AND batch = '".$batch."'
							";
			mysql_query($qupdate);				
	}else {
		if ($param_val == "")
			return;
			$qinsert = "INSERT INTO labor_result_value_test(idformulir,	idmachinereport, namfields, idcompound, mode, value, datetrx,lock_row,batch) 
							VALUES('".$idformulir."', '".$id_machine."', '".addslashes($param_name)."', '".$sample."',
									'".$mode."', '".addslashes($param_val)."', '".date("Y-m-d H:i:s")."','0','".$batch."')";
			mysql_query($qinsert);					
	}
		
}

function delet_payneffect($idformulir, $id_machine) {
	$qDel = "DELETE FROM labor_result_value_test 
					WHERE idformulir = '".$idformulir."' AND idmachinereport = '".$id_machine."'
				";
	mysql_query($qDel);		
}

function InsertPayneEffect_data_test_by_id_formulir($idformulir, $sample, $mode, $id_machine, $param_name, $param_val, $label_val, $batch ="") {
	
	if ($label_val == "")
		return;
	
	$qinsertLabel = "INSERT INTO labor_result_value_test(idformulir,	idmachinereport, namfields, idcompound, mode, value, datetrx,lock_row,batch) 
	VALUES('".$idformulir."', '".$id_machine."', 'LABEL', '".$sample."',
				'".$mode."', '".$label_val."', '".date("Y-m-d H:i:s")."','0','".$batch."');";
			mysql_query($qinsertLabel) or die(mysql_error());			
		
		
	$qinsert = "INSERT INTO labor_result_value_test(idformulir,	idmachinereport, namfields, idcompound, mode, value, datetrx,lock_row,batch) 
	VALUES('".$idformulir."', '".$id_machine."', '".addslashes($param_name)."', '".$sample."',
				'".$mode."', '".addslashes($param_val)."', '".date("Y-m-d H:i:s")."','0','".$batch."');";
			mysql_query($qinsert) or die(mysql_error());					
	
		
}




function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}  


?>