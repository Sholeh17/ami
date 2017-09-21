<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Sys_reg extends Blackbox {
	function __construct()
        {
            parent::__construct();
            $this->check();
            $this->load->helper('url');
            $this->load->model('Mdl_user', 'mtrx');
             $this->load->model('Mdl_master_machine', 'mmm');
        }
	public function index()
	{	$data = "";		
		$this->load->view('sys_reg',$data);	
	}
	
	function get_data() {
		$this->defaultSortProperty = 'user_id';
		$this->defaultSortDirection = 'desc';
		$this->where = '';
		$this->prep();      		
		$fields[] = 'id_seq';
		$fields[] = 'user_id';		
		$fields[] = 'level';	
		$fields[] = 'user_section';		
		$fields[] = 'group_user';		
		$fields[] = 'amount_approve';		
		$fields[] = 'nama_user';	
		$fields[] = 'hp';	
		$fields[] = 'email';	
		$fields[] = 'active';
		$fields[] = 'owner';
		$this->mtrx->get_data_users($fields, "user", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);		
	}
	
	function save() {		
		
		if (!$_POST['id_seq'])  {
			
			#echo "<script>console.log( 'Debug Objects: test123' );</script>";
			$_POST['password'] = md5($_POST['password']);
			$this->mtrx->save_user($_POST);
            $this->mmm->save_report_user($_POST['user_id'], $_POST);
			echo json_encode(array("success"=>true,"msg"=>"Registration Success"));	
		} else  {
			var_dump($_POST);
			#echo "<script>console.log( 'Debug Objects: test321' );</script>";
			if ($_POST['password'] == "")
				unset($_POST['password']);
			else
				$_POST['password'] = md5($_POST['password']);
			
                        
			$this->mtrx->update_user($_POST, $_POST['id_seq']);
			$_POST['user_id'] = $this->mtrx->get_user_id($_POST['id_seq']);
			
			$this->mmm->save_report_user($_POST['user_id'], $_POST);
			echo json_encode(array("success"=>true,"msg"=>"Update Success"));
		}
		
	}
        
        function get_report_machine_test() {
            $userid = (isset($_REQUEST['userid']) ? $_REQUEST['userid'] : false);
			$owner = (isset($_REQUEST['owner']) ? $_REQUEST['owner'] : '');
            $this->mmm->get_mechine_user_test($userid, $owner);
        }
	
	
	

	
}
