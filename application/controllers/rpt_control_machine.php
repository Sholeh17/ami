<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class rpt_control_machine extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('mdl_rpt_control_machine','cm');        
				
	}
	public function index()
	{           
        
		$data['role'] = $this->get_role_leve_user("rpt_control_machine");
		$data['v_list'] = $this->cm->list_formulir();
		
		$this->load->view('rpt_control_machine/list', $data);
	}
	
	public function print_report_formulir(){
		
		$v_dte_frm = $_GET['dte_frm'];
		$v_dte_pto = $_GET['dte_pto'];
		$v_pno_req = $_GET['pno_req'];
		
		$data['role'] = $this->get_role_leve_user("rpt_control_machine");		
		$data['v_lst_mch'] = $this->cm->list_machine();
		
		//menampung data formulir test ke dalam array
		$v_frm_arr = array();
		$v_frm_lst = $this->cm->list_formulir($v_dte_frm,$v_dte_pto,$v_pno_req);
		
		$j = 0;
		$arr_ft = $v_frm_lst->result();
		foreach($arr_ft as $v_frm_row){
			
			$arr_ft[$j]->cover_machine = $this->cm->list_machine_formulir($v_frm_row->idformulir);
			$arr_ft[$j]->value_input_machine = $this->cm->list_value($v_frm_row->idformulir);
			
			//print_r($arr_ft);
			$j++;		
		}
		$data['frm_test'] = $arr_ft; 
		$this->load->view('rpt_control_machine/rpt_control_machine', $data);
	}
	
	function get_formulir_test_is_approved() {
		$this->defaultSortProperty = 'date_request';
                $this->defaultSortDirection = 'DESC';
                $this->where = '';
                $this->prep();  
				$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";
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
	
}
