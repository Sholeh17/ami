<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Sys_cp extends Blackbox {
	function __construct()
        {
            parent::__construct();
            $this->check();
            $this->load->helper('url');			
        }
	public function index()
	{	$data = "";		
		$this->load->view('sys_cp',$data);	
	}	
	
	function save() {		
		$this->changePasswordx();		
	}
	
}
