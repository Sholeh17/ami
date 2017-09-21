<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Rpt_acheivement extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_rpt_acheivement','cm');        
				
	}
	public function index()
	{           
        
		$data['role'] = $this->get_role_leve_user("rpt_acheivement");
		$this->load->view('rpt_acheivement/list', $data);
	}
	
	public function print_acheivement(){
		
		$data['role'] = $this->get_role_leve_user("rpt_acheivement");	
		
		$data['userList'] = $this->cm->get_user_analisa();
		
		$data['count_plan'] = $this->cm->get_count_plan($_REQUEST['dte_frm'],$_REQUEST['dte_pto']);	
		
		$data['count_plan_by_user'] = $this->cm->get_count_plan_by_user($_REQUEST['dte_frm'],$_REQUEST['dte_pto']);
		
		$data['count_acheivement'] = $this->cm->get_count_acheivement($_REQUEST['dte_frm'],$_REQUEST['dte_pto']);
		
		$data['count_acheivement_by_user'] = $this->cm->get_count_acheivement_by_user($_REQUEST['dte_frm'],$_REQUEST['dte_pto']);
		
		$this->load->view('rpt_acheivement/rpt_acheivement', $data);
	}
	
	public function print_acheivement_by_user(){
		
		$data['role'] = $this->get_role_leve_user("rpt_acheivement");	
		
		$data['all_data_by_user'] = $this->cm->get_all_data_by_user($_REQUEST['dte_frm'],$_REQUEST['dte_pto']);
		#print_r($data);
		$this->load->view('rpt_acheivement/rpt_achievement_by_user', $data);
	}
	
	
}
