<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class M_machine_field extends Blackbox {
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
		$data['role'] = $this->get_role_leve_user("m_machine_field");
		$this->load->view('m_machine_field', $data);
	}
        
    function get_list_machine_field() {
                $this->defaultSortProperty = 'idmachinereport,sortorder';
		$this->defaultSortDirection = 'asc';
		
		$this->prep();  
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";		
		$fields[] = 'idfield';
        $fields[] = 'k1.name_report';
        $fields[] = 'namefield';
        $fields[] = 'textlabel';
        $fields[] = 'xtype';
        $fields[] = 'idmachinereport';
        $fields[] = 'sortorder';
		$fields[] = 'UOM';
		$this->mmm->get_machine_fields($fields, "labor_master_machine_fields", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
    }
        
	function save() {            
		$array_post = array();
		$array_ID['idfield'] = $_POST['id'];
		$array_post['idmachinereport'] = $_POST['cmb_machine_test'];		
		$array_post['namefield'] = $_POST['name_field'];
                $array_post['textlabel'] = $_POST['label_field'];
                $array_post['xtype'] = $_POST['type_field'];
                $array_post['sortorder'] = $_POST['sort_order'];
				$array_post['UOM'] = $_POST['UOM'];
                
                
		$CI =& get_instance();
                
		if ($_POST['id'] == "") {
                    $q = "SELECT * FROM labor_master_machine_fields where "
                            . "idmachinereport = '".addslashes($_POST['cmb_machine_test'])."' "
                            . "and namefield = '".addslashes($_POST['name_field'])."'";
                    $query_x = $CI->db->query($q);
                    if($query_x->num_rows() > 0){
                        $result_arr = array("failure" => "true", "msg" => "Save Not Success. Name field sudah!!");
                        echo json_encode($result_arr);
                        die;
                    }
                    $execute = $CI->db->insert("labor_master_machine_fields", $array_post);
                } else {
                    $execute = $CI->db->update("labor_master_machine_fields", $array_post, $array_ID);
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
            $array_ID['idfield'] = $_POST['id'];
            $execute = $CI->db->delete("labor_master_machine_fields", $array_ID);
            $result_arr = array("success" => "true", "msg" => "Save Success!!");
            echo json_encode($result_arr);
        }
        
	function get_data_machine_test() {
		$this->defaultSortProperty = 'idreport';
		$this->defaultSortDirection = 'asc';
		$this->where = "";
		$this->prep();
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";			
		$fields[] = 'idreport';
		$fields[] = 'name_report';
		$this->md->get_common_data($fields, "labor_master_report_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, 99999);
		
        }
        
        function download_csv() {
            // output headers so that the file is downloaded rather than displayed
            $id_report = $_REQUEST['id_report'];
            $name_report = str_replace(" ","_",$_REQUEST['name_report']);
            header("Content-Type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=$name_report.csv");

            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // output the column headings
            $get_field_name = $this->mmm->get_field_name_master_machine($id_report);
            $column = array();
            foreach ($get_field_name as $v) {
                $column[] = $v['namefield']; 
            }
            fputcsv($output, $column);
        }

            
}
