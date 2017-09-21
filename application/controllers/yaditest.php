<?php

class yaditest extends CI_Controller {
	
	function construct(){
		
		parent::__construct();
	}
	
	function index(){
		
		
		$v_data['v_test'] = 'Yadi odonks';
		
		$this->load->view('yaditest/list',$v_data);
	}
}