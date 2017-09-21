<?php
    class Mdl_formulir_request extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function get_formulir_item_test($id_formulir) {
        
        $query = "SELECT a.idformuliritem, a.idmachinetest_detail as idmachine, b.name_report as name, a.status_item, a.idformulir
					 FROM labor_forumlir_item_test a "
                . " inner join labor_master_report_test b on a.idmachinetest_detail = b.idreport "
                . " WHERE a.idformulir = '".$id_formulir."'";
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
        echo json_encode(Array(
                "total"=>1000,
                "data"=>$rowsData
            ));
    }
	
    function get_formulir_item_compound_raw_material($id_formulir) {
        $user_section = $this->session->userdata('user_section');
		$level = $this->session->userdata('level');
        $query = "SELECT  a.idformulir, a.id_raw_material, c.name, a.qty, a.tipe_raw_material 
					 FROM labor_formulir_cp_raw_material a "
                . " inner join labor_formulir_request_test b on a.idformulir = b.idformulir "
				. " inner join labor_master_material_compound c on c.idmaterial =  a.id_raw_material "
                . " WHERE a.idformulir = '".$id_formulir."'";
				
			
		if ($level == "USER" || $level == "ANALISA LAB" || $level == "SECTION HEAD" || $level == "GENERAL" )		
			$query .= " and b.request_by = '".$user_section."'";
		//else
			
			
        $rs = $this->db->query($query);
        $rowsData = $rs->result_array();
        echo json_encode(Array(
                "total"=>1000,
                "data"=>$rowsData
            ));
    }
    
	
    function save_draft($data) {		
		$CI =& get_instance();	
		$state = true;
		
		if (!isset($data['data'])) {
			echo json_encode(Array("success"=>false,"msg"=>'Not Success, Insert items transaction. Transaction is empty!!!'));
			die;
		}
		
		$checkRowMaterial = $this->checkRowSample($data['data']['sample']);
		if (!$checkRowMaterial) {
			echo json_encode(Array("success"=>false,"msg"=>'Error, Pilih sample dengan benar!!!'));
			die;
		}
		
		
		$data_now = date("Y-m-d");
		$date_req = $data['data']['date_request'];
		if(strtotime($date_req) < strtotime($data_now)) {
			echo json_encode(Array("success"=>false,"msg"=>'Error, Tanggal Request tidak dapat lebih kecil dari tanggal hari ini!!!'));
			die;
		} 
				
		$this->db->trans_begin();
		if($this->session->userdata('owner') == 'RD'){
			$data['data']['no_req'] = $CI->generateNumberPO("RDLAB", 0);
		}
		else{
			$data['data']['no_req'] = $CI->generateNumberPO("QLLAB", 0);
		}
				
		$this->db->insert("labor_formulir_request_test",
                                    array("no_req"=>$data['data']['no_req'],
											"shift_formulir"=>$data['data']['shift_formulir'],
											"queue_number"=>$data['data']['queue_number'],
                                              "date_request"=>$data['data']['date_request'],
                                              "date_line"=>$data['data']['date_line'], 
											  "date_reciept_sample"=>$data['data']['date_reciept_sample'],
                                              "sample_category" => $data['data']['sample_category'],
                                              "sample"=>$data['data']['sample'], 
                                              "type_request"=>$data['data']['type_request'],
                                              "request_by"=>$data['data']['request_by'],  
											  "request_by_people"=>$data['data']['request_by_people'],
                                              "criteria"=>$data['data']['criteria'], 
                                              "scale"=>$data['data']['scale'],  
                                              "porpose"=>$data['data']['porpose'],
                                              "sample_spec"=>$data['data']['sample_spec'],
                                              "notes"=>$data['data']['notes'],
                                              "date_create"=>date("Y-m-d H:i:s"),
                                              "status"=>"Draft",
                                              "user_create"=>$this->session->userdata('user_id'),
											  "owner"=>$this->session->userdata('owner'),
											  "rir"=>$data['data']['rir']
                                              ));
                $idformulir = $this->db->insert_id();
		$item_machine = $data['data']['items'];
		foreach ($item_machine as $v) {			
			if ($v['idmachine'] != "") {
					$isExist = 0;
					$sql_select = "SELECT 1 as exist FROM labor_forumlir_item_test WHERE idmachinetest_detail = '".$v['idmachine']."'AND idformulir = $idformulir";
					$isExist = $this->db->query($sql_select)->num_rows();
					
					if($isExist == 0)
						$this->db->insert("labor_forumlir_item_test", array("idmachinetest"=>$v['idmachine'],"idmachinetest_detail"=>$v['idmachine'],"idformulir"=>$idformulir));
			}
		}
		
		
		if(isset($data['data']['itemsraw'])){
			$item_comp_raw = $data['data']['itemsraw'];
			foreach ($item_comp_raw as $v) {			
				if ($v['id_raw_material'] != "") {	
					try{
						$isExist = 0;
						$sql_select = "SELECT 1 as exist from labor_formulir_cp_raw_material WHERE id_raw_material = '" .$v['id_raw_material']. "' AND idformulir = $idformulir AND tipe_raw_material='".$v['tipe_raw_material']. "'";
						print $sql_select;
						$isExist = $this->db->query($sql_select)->num_rows();
						
						if($isExist == 0)
							$this->db->insert("labor_formulir_cp_raw_material", array("idformulir"=>$idformulir,"id_raw_material"=>$v['id_raw_material'],"qty"=>$v['qty'], "tipe_raw_material"=>$v['tipe_raw_material']));
					}catch (Exception $e) {
						
					}
				}
			}
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not success save data. Try again.!!'
			));
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success save data'
			));
		}
		
		
	}
	
        
    function update_draft($data, $idFormulir) {		
		$this->load->library("session");
		$CI =& get_instance();	
		$state = true;
		
		if (!isset($data['data'])) {
			echo json_encode(Array("success"=>false,"msg"=>'Not Success, Insert items transaction. Transaction is empty!!!'));
			die;
		}
		
		$checkRowMaterial = $this->checkRowSample($data['data']['sample']);
		if (!$checkRowMaterial) {
			echo json_encode(Array("success"=>false,"msg"=>'Error, Pilih sample dengan benar!!!'));
			die;
		}
		
		
		$status_data = $this->get_formulir_test_by_id($idFormulir);
		
		if ($status_data) {
				if ($status_data->status != "DRAFT") {
					echo json_encode(Array("success"=>false,"msg"=>'Not Success, Status data: '.$status_data->status.'!!!'));
					die;
				}
		}
		
		$this->db->trans_begin();
                
		
		//"date_request"=>$data['data']['date_request'],
		
		$this->db->update("labor_formulir_request_test",
                                    array("no_req"=>$data['data']['no_req'],
									"queue_number"=>$data['data']['queue_number'],
											  "shift_formulir"=>$data['data']['shift_formulir'],	
                                              "date_line"=>$data['data']['date_line'],
											  "date_reciept_sample"=>$data['data']['date_reciept_sample'],
                                              "sample_category" => $data['data']['sample_category'],
                                              "sample"=>$data['data']['sample'], 
                                              "type_request"=>$data['data']['type_request'],
                                              //"request_by"=>$data['data']['request_by'], 
											  //"request_by_people"=>$data['data']['request_by_people'],	
                                              "criteria"=>$data['data']['criteria'], 
                                              "scale"=>$data['data']['scale'],  
                                              "porpose"=>$data['data']['porpose'],
                                              "sample_spec"=>$data['data']['sample_spec'],
                                              "notes"=>$data['data']['notes'],
											  "rir"=>$data['data']['rir'],
                                              "update_date"=>date("Y-m-d H:i:s"),
                                              "user_update"=> $this->session->userdata('user_id')
                                              ), array("idformulir"=>$idFormulir));
        $idformulir = $idFormulir;//$this->db->insert_id();
		$item_machine = $data['data']['items'];
		$this->db->delete("labor_forumlir_item_test",array("idformulir"=>$idFormulir));
		foreach ($item_machine as $v) {			
			if ($v['idmachine'] != "") {
				$isExist = 0;
				$sql_select = "SELECT 1 as exist FROM labor_forumlir_item_test WHERE idmachinetest_detail = '".$v['idmachine']."'AND idformulir = $idformulir";
				$isExist = $this->db->query($sql_select)->num_rows();
				
				if($isExist == 0)
					$this->db->insert("labor_forumlir_item_test", array("idmachinetest"=>$v['idmachine'],"idmachinetest_detail"=>$v['idmachine'],"idformulir"=>$idformulir));
			}
		}
		
		if(isset($data['data']['itemsraw']) != "") {
			$itemsraw = $data['data']['itemsraw'];
			$this->db->delete("labor_formulir_cp_raw_material",array("idformulir"=>$idFormulir));
			foreach ($itemsraw as $v) {			
				if ($v['id_raw_material'] != "") {	
					$isExist = 0;
					$sql_select = "SELECT 1 as exist from labor_formulir_cp_raw_material WHERE id_raw_material = '" .$v['id_raw_material']. "' AND idformulir = $idformulir AND tipe_raw_material='".$v['tipe_raw_material']. "'";
					$isExist = $this->db->query($sql_select)->num_rows();
					
					if($isExist == 0)
						$this->db->insert("labor_formulir_cp_raw_material", array("idformulir"=>$idFormulir,"id_raw_material"=>$v['id_raw_material'],"qty"=>$v['qty'], "tipe_raw_material"=>$v['tipe_raw_material']));
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not success save data. Try again.!!'
			));
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success save data'
			));
		}
		
		
	}
        
	function apdate_status_approve($id, $status_value) {
            $this->db->update("labor_formulir_request_test",array("status"=>$status_value, "date_approved"=>date("Y-m-d")),array("idformulir"=>$id));
            echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success...'
			));
        }
		
	function apdate_status($id, $status_value) {
            $this->db->update("labor_formulir_request_test",array("status"=>$status_value),array("idformulir"=>$id));
            echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success...'
			));
        }
                
    function delete_formulir($id, $status_value) {
		$this->db->delete("labor_formulir_request_test", array("idformulir"=>$id));
		$this->db->delete("labor_forumlir_item_test", array("idformulir"=>$id));
		echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Delete Success...'
			));
	}
	
	function update_status_item_test($idformuliritem) {
		$this->db->update("labor_forumlir_item_test", array("status_item"=>"Cencel"), array("idformuliritem"=>$idformuliritem));
	}
	
	function get_item_test_formulir_test($idformulir) {
		$query = "SELECT a.*, b.name_report FROM labor_forumlir_item_test a 
				inner join labor_master_report_test b on a.idmachinetest_detail = b.idreport 
				where  a.idformulir = '".$idformulir."'";
		$rs = $this->db->query($query);
        $rowsData = $rs->result_array();		
		echo json_encode(Array(
                "total"=>99999,
                "data"=>$rowsData
            ));		
	}
			 
	function get_formulir_test_by_id($idformulir) {
		$query = "SELECT * FROM labor_formulir_request_test a 
				where  a.idformulir = '".$idformulir."'";
		$rs = $this->db->query($query);
        $rowsData = $rs->row();
		return $rowsData;
	}
	
	function chekc_item_test_intest($idformulir, $idmachine) {
		$this->db->select('idformulir');
		$this->db->where("idformulir", $idformulir);
		$this->db->where("idmachinereport", $idmachine);
		$this->db->where("owner", $this->session->userdata('owner'));
		$query = $this->db->get('labor_result_value_test');
		if($query->num_rows() > 0)
			return true;
		return false;
	}
	
	function checkRowSample($sample) {
		$this->db->select('idmaterial');
		$this->db->where("idmaterial", $sample);
		$query = $this->db->get('labor_master_material_compound');
		if($query->num_rows() > 0)
			return true;
		return false;
	}
	
}

    

