<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Roleusermanagement extends Blackbox {
	function __construct()
        {
            parent::__construct();
            $this->check();
            $this->load->helper('url');
			$this->load->model('Mdl_menu', 'mtrx');
        }
	public function index()
	{	$data = "";		
		$this->load->view('roleusermanagement',$data);	
	}
	
	function get_allfitures() {
		$level = (isset($_REQUEST['level'])) ? $_REQUEST['level'] : "";
		$be_get = (isset($_REQUEST['be_get'])) ? $_REQUEST['be_get'] : "LEFT"; 
		$arr = $this->mtrx->get_menu_left($level,$be_get);
		echo json_encode(Array(
                "total"=>0,
                "data"=>$arr
            ));
	}
	
	function save() {
		$post_arr = $_POST;
		$this->mtrx->save_role($post_arr);
	}
	
	function get_menu_login() {
		$this->mtrx->get_menu($this->session->userdata('level'));
	}
	
	
	
	

	
}
