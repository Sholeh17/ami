<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Rpt_summary_test extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('mdl_rpt_summary_test','cm');
	}
	public function index()
	{           
        
		$data['role'] = $this->get_role_leve_user("rpt_summary_test");
		$data['v_list'] = $this->cm->list_formulir();
		
		$this->load->view('rpt_summary_test/list', $data);
	}
	
	public function print_report_formulir(){
		
		$v_pno_req = $_GET['pno_req'];
		
		$data['role'] = $this->get_role_leve_user("rpt_summary_test");	
		
		//variable
		$v_hed = array();
		$v_det = array();	
		
		$data['v_lst'] = $this->cm->list_formulir($v_pno_req);
				
		
		$data['frm_test'] = false;// $arr_ft; 
		$this->load->view('rpt_summary_test/rpt_summary_test', $data);
	}
	
	function get_formulir_test_is_approved() {
		$this->defaultSortProperty = 'date_request';
                $this->defaultSortDirection = 'DESC';
                $this->where = '';
                $this->prep();                
                $fields[] = 'idformulir';
                $fields[] = 'no_req';
                $fields[] = 'date_request';
                $fields[] = 'date_line';
                $fields[] = 'sample';
                $fields[] = 'type_request';
                $fields[] = 'request_by';
                $fields[] = 'porpose';
                $fields[] = 'sample_spec';
                $fields[] = 'notes';
                $fields[] = 'scale';
                $fields[] = 'status';
                $fields[] = 'user_create';
                $this->cm->get_formulir_test_is_approved($fields, "labor_formulir_request_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
	}
	
	/*function get_formulir_test_is_approved_by_user() {
		$this->defaultSortProperty = 'date_request';
		$this->defaultSortDirection = 'DESC';
		$this->where = '';
		$this->prep(); 
		$this->cm->get_formulir_test_by_user($this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
	}*/
}
