<?php
    class Mdl_report_test extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
		$this->load->library("session");
    }


	function get_formulir_test_is_approved($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
		$i= 0;
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
				for ($i=0; $i<count($fields);$i++){
				
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
					$where = str_replace("name",$tableName.".name",$where);
                }
            } else {
			}			
			
			if ($i > 0) {
				$where .= " ) "; 
			}
			
			
			$where .= " AND status = 'APPROVE' ";
			 //All Level Administrator
			
			$fields = implode(",", $fields);
			$query = "SELECT ".$fields."
			FROM ".$tableName."			
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
			#die($query);
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." 			
			WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();

            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
	}

        
    function get_list_report_test_item_input($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
            $this->load->library("session");
            
            $level = $this->session->userdata('level');
            $user_id = $this->session->userdata('user_id');
            
            $i= 0;
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
				for ($i=0; $i<count($fields);$i++){
				
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
					$where = str_replace("name",$tableName.".name",$where);
                }
            } else {
			}			
			
			if ($i > 0) {
				$where .= " ) "; 
			}
		
            $where .= " AND labor_report_mesin_user.userid = '".$user_id."'";            
            $where .= " AND labor_forumlir_item_test.idformulir = '".$_REQUEST['id_formulir']."' ";  
			$where .= " AND labor_forumlir_item_test.status_item = 'Test' ";           
			 //All Level Administrator
                        
            $fields = implode(",", $fields);
            $query = "SELECT ".$fields."
			FROM ".$tableName."	
							inner join labor_forumlir_item_test on labor_forumlir_item_test.idmachinetest_detail = ".$tableName.".idreport
                            inner join labor_report_mesin_user on labor_report_mesin_user.idreport = ".$tableName.".idreport
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." 
						inner join labor_forumlir_item_test on labor_forumlir_item_test.idmachinetest_detail = ".$tableName.".idreport
                        inner join labor_report_mesin_user on labor_report_mesin_user.idreport = ".$tableName.".idreport
			WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();

            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
    }    
        
    function get_row_formulir_test($id_formulir) {
		$query = "SELECT a.* FROM labor_formulir_request_test a "
                . " WHERE a.idformulir = '".$id_formulir."'";
        $rs = $this->db->query($query);
		$datarow = $rs->row();
		return $datarow;
	}
	
    function get_formulir_item_test($id_formulir) {
        
        $query = "SELECT b.* FROM labor_forumlir_item_test a "
                . " inner join labor_master_machine_test b on a.idmachinetest = b.idmachine "
                . " WHERE a.idformulir = '".$id_formulir."'";
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
        echo json_encode(Array(
                "total"=>1000,
                "data"=>$rowsData
            ));
    }

    function get_formulir_item_test_result_arr($id_formulir) {
        
        $query = "SELECT b.* FROM labor_forumlir_item_test a "
                . " inner join labor_master_report_test b on a.idmachinetest_detail = b.idreport "
                . " WHERE a.idformulir = '".$id_formulir."'";
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
       	return $rowsData;
    }

    function get_formulir_item_raw_material_result_arr($id_formulir) {
		$user_section = $this->session->userdata('user_section');
       	$level = $this->session->userdata('level');
        $query = "SELECT a.*, b.* FROM labor_formulir_cp_raw_material a "
                . " inner join labor_master_material_compound b on a.id_raw_material = b.idmaterial "
				. " inner join labor_formulir_request_test c on a.idformulir = c.idformulir "
                . " WHERE a.idformulir = '".$id_formulir."'";
		
		if ($level == "USER" || $level == "ANALISA LAB" || $level == "SECTION HEAD" || $level == "GENERAL" )		
			$query .= " and c.request_by = '".$user_section."'";		
				
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
       	return $rowsData;
    }   	
	
	function get_formulir_item_test_file_attch($id_formulir, $idreport) {
        $query = "SELECT a.* FROM labor_forumlir_item_test a "
                . " inner join labor_master_report_test b on a.idmachinetest_detail = b.idreport "
                . " WHERE a.idformulir = '".$id_formulir."' AND a.idmachinetest_detail = '".$idreport."'";
        $rs = $this->db->query($query);
        $rowsData = $rs->row();
       	return $rowsData;
    }    
	
	function get_formulir_item_test_progress($id_formulir) {
        
       $query = "SELECT  b1.name_report, c.value as progress FROM labor_forumlir_item_test a "
				. " inner join labor_master_report_test b1 on b1.idreport = a.idmachinetest_detail "
				. " left join labor_result_value_test c on (c.idformulir = a.idformulir and c.idmachinereport= b1.idreport)"
                . " WHERE a.idformulir = '".$id_formulir."' group by  b1.idreport";
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
       	return $rowsData;
    }    
	
	function get_fields_machine($idreportmachine) {
		$query = "SELECT a.* FROM labor_master_machine_fields a"
                . " WHERE a.idmachinereport = '".$idreportmachine."' order by sortorder ASC";
		#die($query);
        $rs = $this->db->query($query);
		$fields = array();
		$textlabel = array();
		$fieldsstartvalue = array();
		$fieldsendvalue = array();
		$unitname = array();
		$xtype = array();
        $rowsData = $rs->result();
		foreach($rowsData as $v) {
			$fields[] = $v->namefield;
			$textlabel[] = $v->textlabel;
			$fieldsstartvalue[]= $v->value_start;
			$fieldsendvalue[] = $v->value_end;
			$unitname[] = $v->unit_name;
			$xtype[] = $v->xtype;
			$uom[] = $v->UOM;
		}
		return array("uom"=>$uom,"fields"=>$fields,"textlabel"=>$textlabel,"xtype"=>$xtype,"fieldsstartvalue"=>$fieldsstartvalue,"fieldsendvalue"=>$fieldsendvalue,"unitname"=>$unitname);
	}
	
	function get_limit_toleransi_compund($compound, $idmachinereports, $field_params) {
		$query = "SELECT a.* FROM labor_limit_toleransi_compound a"
                . " WHERE a.machine_test_report = '".$idmachinereports."' AND compound = '".$compound."'"
				." AND field_params = '".$field_params."'";
		
        $rs = $this->db->query($query);
		$datarow = $rs->row();
		
		#var_dump($datarow);
		return $datarow;
	}
	
	function get_result_machine_test($data_arr) {
		$query = " SELECT a.*  FROM labor_result_value_test a  "
                . " WHERE a.idformulir = '".$data_arr['idformulir']."'"
				. " AND a.idmachinereport = '".$data_arr['idreport']."' order by id_result ASC";
                $rs = $this->db->query($query);
		$rowsData = $rs->result();
		
		//Get fields Machihe
		$fields = $this->get_fields_machine($data_arr['idreport']);
		$countfields = count($fields['fields']);
		
		$newArr = array();
		$arr_temp =array();
		
		$j=0;
		$y=1;
		
		//Generate Data Pivot 
		foreach ($rowsData as $k=>$v) {
			if (!in_array($v->namfields,$arr_temp)) {
				//Populated Fields (Pivot)
				$newArr[$j][$v->namfields] = $v->value; 
				$newArr[$j]["lock_row"] = $v->lock_row; 
				$newArr[$j]["idformulir"] = $v->idformulir; 
				$newArr[$j]["idmachinereport"] = $v->idmachinereport; 
				$newArr[$j]["idcompound"] = $v->idcompound;
				$newArr[$j]["namecompound"] = $v->idcompound;
				$newArr[$j]["no_type"] = $v->mode;
				$newArr[$j]["pallet"] = $v->pallet;
				$newArr[$j]["no_lot"] = $v->no_lot;
				$newArr[$j]["time_sample"] = $v->time_sample; 
				$newArr[$j]["batch"] = $v->batch; 
				array_push($arr_temp,$v->namfields);
			}
			
			if ($countfields <= $y) {
				$y = 0;
				$j++;
				$arr_temp = array();		
			}
			$y++;
		}
		
		return $newArr;
	}
    
	
	function saveFormParamTestMachine($postData) {
		$arrDel = array("idformulir"=>$postData['list_formulir_test'],"idmachinereport"=>$postData['list_report_test']);
		$this->db->delete("labor_result_value_test", $arrDel);
		$compund = "";
		#print_r($postData);
		foreach($postData["items"] as $k=>$v) {
			foreach ($postData['fields'] as $f) {
				$getfields["namfield"][] = $f;
				$getValues["valuefield"][] = $v[$f]; 
			}
			#echo("<script>console.log('PHP: ".var_dump($getfields)."');</script>");
			#echo("<script>console.log('PHP: ".var_dump($getValues)."');</script>");
			#var_dump($getfields);
			#var_dump($getValues);
			for ($i=0; $i < count($getfields["namfield"]); $i++) {
				$arrIns = array("idformulir"=>$postData['list_formulir_test'],
								"idmachinereport"=>$postData['list_report_test'],
								"namfields"=>$getfields["namfield"][$i],
								"mode"=>$v['no_type'],
								"lock_row"=>$v['lock_row'],
								"idcompound"=>$v['idcompound'], 
								"value"=>$getValues["valuefield"][$i], 
								"pallet"=>$v["pallet"], 
								"batch"=>$v["batch"],
								"no_lot"=>$v["no_lot"],
								"time_sample"=>$v["time_sample"], 
								"datetrx"=>date("Y-m-d H:i:s"),
								"owner"=>$this->session->userdata('owner')
								);
				#echo("<script>console.log('PHP: ".var_dump($arrIns)."');</script>");
				$this->db->insert("labor_result_value_test",$arrIns);	
				$compund = $v['idcompound'];		
			}
			$getfields = "";
			$getValues = "";
			
		}
		
	}
	
        
        function get_is_item_graph() {
            $q = "SELECT b.* FROM labor_master_report_test b"
                    . " WHERE b.is_graph_report = 'YES' GROUP BY b.idreport";
            $rs = $this->db->query($q);
            $rowsData = $rs->result_array();
            echo json_encode(Array(
                
                "data"=>$rowsData
            ));
        }
        
        function save_config_graph($arr) {
            $this->db->delete("labor_config_report_test_graph", array("id_report_test"=>$arr['id_report_test']));
            foreach($arr["data"]["items"] as $k=>$v) { 
                $this->db->insert("labor_config_report_test_graph", 
                        array("id_report_test"=>$arr['id_report_test'],
                            "series"=>$v["series"],
                            "title_y"=>$v["title_y"],
                            "title_x"=>$v["title_x"],
                            "y_field_data"=>$v["y_field_data"],
                            "x_field_data"=>$v["x_field_data"],
                            "x_categorit_axis"=>$arr["cmb_x_axis"],
                            "type_graph"=>$arr['type_graph']
                    ));
            }
           
        }
        
        function get_items_config_graph($data_arr) {
            $query = " SELECT a.*, a.y_field_data as y_field_data_name, a.x_field_data as x_field_data_name,"
                    . " a.x_categorit_axis as x_categorit_axis_name FROM labor_config_report_test_graph a  "
                . " WHERE a.id_report_test = '".$data_arr['id_report_test']."'";
            $rs = $this->db->query($query);
            $rowsData = $rs->result();
            return $rowsData; 
        }
        
	function uploadFile($idformulir,$idmachinetest) {
		 $direktori = './uploads/testrusult/'; //Folder penyimpanan file 
		 $nama_file = $_FILES['file1']['name']; //Nama file yang akan di Upload
                 $ext = end((explode(".", $nama_file)));
                 
                 if (strtolower($ext) != "csv") {
                     echo json_encode(array("success"=>false, "msg"=>"File yang diupload harus CSV!!!"));
                     die;
                 }
                 
		 $file_size = $_FILES['file1']['size']; //Ukuran file yang akan di Upload
		 $nama_tmp  = $_FILES['file1']['tmp_name']; //Nama file sementara
		 $newfilename = trim($idformulir).trim($idmachinetest).".".$ext;
		 $upload = $direktori . $newfilename; //Memposisikan direktori penyimpanan dan file
		 $is_upload = move_uploaded_file($nama_tmp, $upload);	
		 if ($is_upload)
                    return $newfilename;	
	}
        
        function WriteTableFromCSV($id_formulir,$id_report_test, $name_file) {		
            $dirfile = './uploads/testrusult/'.$name_file;		
            $file = fopen($dirfile,"r");	
       
            $line_of_text = array();
            $x = 0;
            while(!feof($file))
             {
                   $line_of_text[] = fgetcsv($file);
                   $x++;
             }
            $j = 0;
            
            
            //Check Colum 
            $columnCheck = array();
            foreach ($line_of_text as $k=>$v) {
                if ($j== 0) {
                    $columnCheck = $v;
                }
                $j++;
            }
            
            $orginal_column = $this->get_field_name_master_machine($id_report_test);
            $columnorginal = array(); // Culomn Orginal
            foreach ($orginal_column as $v) {
                $columnorginal[] = $v['namefield'];
            }
           

            if (count($columnorginal) != count($columnCheck)) {
                echo json_encode(array("success"=>false, "msg"=>"Column not same with orginal Column. Check Format CSV Data!!!"));
                die;
            }
            
            //Delete Data Before
            $arrDel = array("idformulir"=>$id_formulir,"idmachinereport"=>$id_report_test);
            $this->db->delete("labor_result_value_test", $arrDel);
            
            $formulir = $this->get_row_formulir_test($id_formulir);
            $j=0;
            foreach ($line_of_text as $k=>$v) {
                    //print_r($v);
                    if ($j== 0) {
                        $column = $v;             
                    } else {
                        if ($v) {
                            $xj = 0;
                            foreach ($v as $vx) {
                                //if ($vx != ""){
                                     $qins = "INSERT INTO labor_result_value_test (idformulir,idmachinereport,namfields,mode,idcompound,value,lock_row,datetrx) "
                                        . " VALUES ('".$formulir->idformulir."', '".addslashes($id_report_test)."', 'FIELDS', '".addslashes($formulir->type_request)."','".addslashes($formulir->sample)."', '".addslashes($vx)."', '0' , '".date("Y-m-d")."');";
                                    $qins_new = str_replace("FIELDS", $column[$xj], $qins);
                                    $this->db->query($qins_new);
                                //}
                                $xj++;
                            }
                        }
                    }
                    
                    $j++;
            }
            
            if ($j == 1) {
                echo json_encode(array("success"=>false, "msg"=>"Not Found Row Data. Check Format CSV Data!!!"));
                die;
            }
            
        }
        
        function get_field_name_master_machine($id_report) {
            $q = "SELECT * FROM labor_master_machine_fields WHERE idmachinereport = '".$id_report."'";
            $query = $this->db->query($q);
            return $query->result_array();
        }
        
        
		
		function update_other_file_labor_forumlir_item_test($arr_data, $key) {
			$this->db->update("labor_forumlir_item_test", $arr_data, $key);
		}
		
        function delete_file() {
			if ($_REQUEST['fl'] == "1") 
				$this->db->update("labor_forumlir_item_test",array("file_others_1"=>""),array("idformulir"=>$_REQUEST['idformulir'],"idmachinetest_detail"=>$_REQUEST['idreport']));
			else 
				$this->db->update("labor_forumlir_item_test",array("file_others_2"=>""),array("idformulir"=>$_REQUEST['idformulir'],"idmachinetest_detail"=>$_REQUEST['idreport']));			
		}
		
		
		function uploadOtherFile($name_file_x, $key) {
			 $direktori = './uploads/other_file/'; //Folder penyimpanan file 
			 $nama_file = $_FILES[$name_file_x]['name']; //Nama file yang akan di Upload
			 $ext = end((explode(".", $nama_file)));
			 
			 /*if (strtolower($ext) != "csv") {
				 echo json_encode(array("success"=>false, "msg"=>"File yang diupload harus CSV!!!"));
				 die;
			 }*/
					 
			 $file_size = $_FILES[$name_file_x]['size']; //Ukuran file yang akan di Upload
			 $nama_tmp  = $_FILES[$name_file_x]['tmp_name']; //Nama file sementara
			 $newfilename = $name_file_x.$key.date('YmdHis').".".$ext;
			 $upload = $direktori . $newfilename; //Memposisikan direktori penyimpanan dan file
			 $is_upload = move_uploaded_file($nama_tmp, $upload);	
			 if ($is_upload)
						return $newfilename;	
	
			
		}
		
	function checkValue($idformulir){
		$sql = "SELECT 
				  a.`idformulir`,
				  a.`no_req`,
				  d.`value` 
				FROM
				  labor_formulir_request_test a 
				  LEFT JOIN labor_forumlir_item_test b 
					ON a.`idformulir` = b.`idformulir` 
				  INNER JOIN labor_master_report_test c 
					ON b.`idmachinetest_detail` = c.`idreport` 
				  LEFT JOIN labor_result_value_test d 
					ON (
					  a.`idformulir` = d.`idformulir` 
					  AND c.`idreport` = d.`idmachinereport`
					) 
				WHERE a.`idformulir` = '$idformulir' 
				  AND c.`idreport` IN (2, 3) 
				  AND d.`value` IS NOT NULL";
				  
		 $query = $this->db->query($sql);
            return $query->num_rows();
	}
	
}

    

