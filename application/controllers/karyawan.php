<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Karyawan extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_karyawan','ma');
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("karyawan");
		$this->load->view('karyawan', $data);
	}
	function save_karyawan() {            
		$array_post = array();
		$array_post['nik_karyawan'] = $_POST['nik_karyawan'];
		$array_post['name_karyawan'] = $_POST['name_karyawan'];		
		$array_post['id_atasan_1'] = $_POST['id_atasan_1_h'];
		$array_post['id_atasan_2'] = $_POST['id_atasan_2_h'];
		$array_post['id_atasan_3'] = $_POST['id_atasan_3_h'];
		
		
		if ($_POST['id_seq_karyawan'] == "")
			$execute = $this->ma->save_karyawan($array_post);
		else
			$execute = $this->ma->update_karyawan($array_post, $_POST['id_seq_karyawan']);
		if ($execute) {
		$result_arr = array("success" => "true", "msg" => "Save Success!!");
		echo json_encode($result_arr);
		} else {
				$result_arr = array("failure" => "true", "msg" => "Save Not Success!!");
				echo json_encode($result_arr);
		}

	}
	function get_data_karyawan() {
		$this->defaultSortProperty = 'name_karyawan';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();                
		$fields[] = 'id_seq_karyawan';
		$fields[] = 'nik_karyawan';
		$fields[] = 'name_karyawan';		
		$fields[] = 'id_atasan_1';
		$fields[] = 'id_atasan_2';
		$fields[] = 'id_atasan_3';
		$fields[] = 'date_insert_karyawan';
		$this->ma->get_data_karyawan($fields, "karyawan", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
    }

	function delete_produk() {
		$execute = $this->ma->update_del_active(array("del_karyawan" => 1),array("id_seq_karyawan" => $_POST['id_seq_karyawan']));
		if ($execute) {
		$result_arr = array("success" => "true", "msg" => "Delete Success!!");
		echo json_encode($result_arr);
		} else {
				$result_arr = array("failure" => "true", "msg" => "Delete Not Success!!");
				echo json_encode($result_arr);
		}
	}
	
	function get_user_by_login(){
		$this->defaultSortProperty = 'id_seq';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();  
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";			
		$fields[] = 'user_id';
		$fields[] = 'id_seq';
		$fields[] = 'level';
		#$fields[] = 'user_section';
		
		
		$level = $this->session->userdata['level'];
		$user_section = $this->session->userdata['user_section'];
		switch($level){
			case "USER": 
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$this->where .= " AND `user_section` IN ('CD 1','CD 2') ";
				}
				else{
					$this->where .= " AND `user_section` = '$user_section' ";
				}
				break;
			case "GENERAL": case "ANALISA LAB": 
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$this->where .= " AND `user_section` IN ('CD 1','CD 2') ";
				}
				else{
					$this->where .= " AND `user_section` = '$user_section' ";
				}
				break;
			case "SECTION HEAD":
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$this->where .= " AND `user_section` IN ('CD 1','CD 2') ";
				}
				else{
					$this->where .= " AND `user_section` = '$user_section' ";
				}
				break;
			case "DEPARTMENT HEAD": 
			case "DIVISION HEAD": 
			case "ADMIN": 
				$this->where .= " ";
				break;
			default: break;
		}
					
		$this->ma->get_data_karyawan_by_user($fields, "user", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
	}
}
