<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class M_sample extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','md');
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("m_sample");
		$this->load->view('m_sample', $data);
	}
	function save() {            
		$array_post = array();
		$array_ID['idmaterial'] = $_POST['id'];
		$array_post['idmaterial'] = $_POST['idmaterial'];		
		$array_post['name'] = $_POST['name'];
		$array_post['category'] = $_POST['category'];
		$array_post['label_testing_report'] = $_POST['label_testing_report'];
		$array_post['no_reg_checksheet'] = $_POST['no_reg_checksheet'];
                
		$CI =& get_instance();
                
		if ($_POST['id'] == "") {
                      
                    $execute = $CI->db->insert("labor_master_material_compound", $array_post);
                } else {
                    $execute = $CI->db->update("labor_master_material_compound", $array_post, $array_ID);
                }
		if ($execute) {
                    $result_arr = array("success" => "true", "msg" => "Save Success!!");
                    echo json_encode($result_arr);
		} else {
                    $result_arr = array("failure" => "true", "msg" => "Save Not Success!!");
                    echo json_encode($result_arr);
		}

	}
	function get_data_sample() {
		$this->defaultSortProperty = 'idmaterial';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();                
		$fields[] = 'idmaterial';
		$fields[] = 'name';
		$fields[] = 'category';
		$fields[] = 'label_testing_report';
		$fields[] = 'no_reg_checksheet';
		$this->md->get_common_data($fields, "labor_master_material_compound", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
        }

	function delete() {
		$execute = $this->ma->update_del_active(array("del_karyawan" => 1),array("id_seq_karyawan" => $_POST['id_seq_karyawan']));
		if ($execute) {
		$result_arr = array("success" => "true", "msg" => "Delete Success!!");
		echo json_encode($result_arr);
		} else {
				$result_arr = array("failure" => "true", "msg" => "Delete Not Success!!");
				echo json_encode($result_arr);
		}
	}
}


/*
	SELECT `ItemId` as 	idmaterial, `ItemName` as name, 'Raw Material' as category FROM labor_master_itembanbury
*/
