<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Auth extends Blackbox {
        function __construct()
        {
            parent::__construct();            
            $this->load->helper('url');
        }
	public function index()
	{   
            $data['access'] = '';
            $this->load->view('auth', $data);
	}

	function submit() {
			$data_cek_login = $this->cek_user();
			$rest_array = array();
			if(!empty($data_cek_login)){
				$rest_array = array("success"=>true,"data"=>$data_cek_login);
				echo json_encode($rest_array);
			} else {
				$rest_array = array("success"=>false, "failure"=>true, "errors"=>array("reason"=>"Username and/or Password is wrong"));
				echo json_encode($rest_array);
			}
	}

		
	function changePassword() {
                $this->changePasswordx();
        }

        function regenSession() {
            $this->regenerationSession();
			$this->logout_sfa();
        }
		
        function logout() {
            $this->check_out();			
            redirect('./auth');
        }		
		
		
		
	   
	   
	   
        

}
