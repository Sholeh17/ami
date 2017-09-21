<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class M_toleransi extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','md');
        $this->load->model('Mdl_master_machine','mmm');
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("m_toleransi");
		$this->load->view('m_toleransi', $data);
	}
        
        function get_list_machine_field_toleransi() {
                $this->defaultSortProperty = 'id';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep(); 
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";			
		$fields[] = 'id';
                $fields[] = 'compound';
                $fields[] = 'name_report';
                $fields[] = 'machine_test_report';
                $fields[] = 'field_params';
                $fields[] = 'start_toleransi';
                $fields[] = 'end_toleransi';
				$fields[] = 'remarks';
		$this->mmm->get_machine_fields_toleransi($fields, "labor_limit_toleransi_compound", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
        }
        
        function get_name_field_rpt() {
            $id_reprot = $_REQUEST['id_rpt'];
            $datax = $this->mmm->get_field_name_master_machine($id_reprot);
            echo json_encode(Array(
                "total"=>99999,
                "data"=>$datax
            ));
        }
        
	function save() {       
		if($_POST['remarks'] == ""){
			$remarks = "Minimum Values is " . $_POST['start_toleransi'] . " and Maximum Values is " . $_POST['end_toleransi'];
		} 
		else{
			$remarks = $_POST['remarks'];
		}
		$array_post = array();
		$array_ID['id'] = $_POST['id'];
		$array_post['machine_test_report'] = $_POST['cmb_machine_test_f'];		
		$array_post['compound'] = $_POST['compound'];
		$array_post['field_params'] = $_POST['field_params'];
		$array_post['start_toleransi'] = $_POST['start_toleransi'];
		$array_post['end_toleransi'] = $_POST['end_toleransi'];
		$array_post['remarks'] = $remarks;
                
               
		$CI = & get_instance();
        #print_r($array_post);        
		
		if ($this->checkDataExisting($_POST['cmb_machine_test_f'], $_POST['compound'], $_POST['field_params'])) {
			$execute = $CI->db->update("labor_limit_toleransi_compound", $array_post, 
								array(	"compound"=>$_POST['compound'], 
										"machine_test_report"=>$_POST['cmb_machine_test_f'], 
										"field_params"=>$_POST['field_params']));
		}else {
			$execute = $CI->db->insert("labor_limit_toleransi_compound", $array_post);
		}
		
		if ($execute) {
                    $result_arr = array("success" => "true", "msg" => "Save Success!!");
                    echo json_encode($result_arr);
		} else {
                    $result_arr = array("failure" => "true", "msg" => "Save Not Success!!");
                    echo json_encode($result_arr);
		}

	}
        
        function delete() {
            $CI = & get_instance();
            $array_ID['id'] = $_POST['id'];
            $execute = $CI->db->delete("labor_limit_toleransi_compound", $array_ID);
            $result_arr = array("success" => "true", "msg" => "Save Success!!");
            echo json_encode($result_arr);
        }
        
	function get_data_machine_test() {
		$this->defaultSortProperty = 'idreport';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();  
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";		
		$fields[] = 'idreport';
		$fields[] = 'name_report';
		$this->md->get_common_data($fields, "labor_master_report_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, 99999);
		
        }
        
        function checkDataExisting($id_report, $compound, $fields) {
            $CI = & get_instance();
            $q = "SELECT * FROM labor_limit_toleransi_compound "
                    . "WHERE compound = '".$compound."' "
                    . " AND machine_test_report = '".$id_report."' "
                    . " AND field_params = '".$fields."'";
					#print $q;
            $query = $CI->db->query($q);
            $row = $query->row();
            if ($row)
                return true;
            return false;
                
        }

            
}
