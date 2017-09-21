<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class M_machine_detail extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','md');
                $this->load->model('Mdl_master_machine','mm');
                
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("M_machine_detail");
		$this->load->view('m_machine_detail', $data);
	}
	function save() {            
		$array_post = array();
		$array_ID['idreport'] = $_POST['id'];
		$array_post['idmachine'] = $_POST['cmb_group_machine'];
        $array_post['name_report'] = $_POST['name_report'];
		$array_post['is_graph_report'] = "NO"; 
		$array_post['owner'] = $this->session->userdata('owner'); 
        if (isset($_POST['is_graph_report']))
			$array_post['is_graph_report'] = $_POST['is_graph_report']; 
		       
		$CI =& get_instance();
                
		if ($_POST['id'] == "") {
                      
                    $execute = $CI->db->insert("labor_master_report_test", $array_post);
                } else {
                    $execute = $CI->db->update("labor_master_report_test", $array_post, $array_ID);
                }
		if ($execute) {
                    $result_arr = array("success" => "true", "msg" => "Save Success!!");
                    echo json_encode($result_arr);
		} else {
                    $result_arr = array("failure" => "true", "msg" => "Save Not Success!!");
                    echo json_encode($result_arr);
		}

	}
	function get_data_machine() {
		$this->defaultSortProperty = 'name_report';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();  
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";			
		$fields[] = 'idreport';
                $fields[] = 'k1.name';
                $fields[] = 'k1.idmachine';
                $fields[] = 'name_report';
                $fields[] = 'orderindex';
                $fields[] = 'is_graph_report';
		$this->mm->get_data_machine($fields, "labor_master_report_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
    }

        function get_data_group_machine() {
            $this->defaultSortProperty = 'idmachine';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep(); 
		#$this->where .= " AND labor_master_machine_test.owner = '" . $this->session->userdata('owner'). "'";			
		$fields[] = 'idmachine';
		$fields[] = 'name';
		$this->md->get_common_data($fields, "labor_master_machine_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, 9999);
		
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
