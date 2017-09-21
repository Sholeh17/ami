<?php
class Mdl_menu extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
		$this->load->library("session");
    }	
	
	function get_menu($level) {		
		$arr_res = array();
		$formulir_test = array();
		$check_formulir = $this->check_access_menu("ADMINISTRATION", $level);
		if ($check_formulir) {
				$formulir_test =  array(
					"text" => "Administration",
					"cls" => "folder",							
					"expanded" => false
				);
				
				$menu_formulir = $this->getMenuUser($this->session->userdata('level'), "ADMINISTRATION");
				$menu_child = array();
				#print_r($menu_child);
				foreach ($menu_formulir as $v) {
					$menu_child[] = array(
						"text" => $v['menu_name'],
						"id" => $v['menu_controller'],						
						"iconCls"=> $v['menu_controller'],
						"leaf" => true
					);
				}
				$formulir_test['children'] = $menu_child;
				$arr_res[]=	$formulir_test;
		}
		/*
		$reports = array();
		$check_group_Report = $this->check_access_menu("Report Test", $level);
		if ($check_group_Report) {
				$reports =  array(
					"text" => "Report",
					"cls" => "folder",							
					"expanded" => false
				);
				
				$menu_reports = $this->getMenuUser($level, "Report Test");
				$menu_child = array();
				foreach ($menu_reports as $v) {
					$menu_child[] = array(
						"text" => $v['fitur_code'],
						"id" => $v['name_menu'],						
						"iconCls"=> $v['name_menu'],
						"leaf" => true
					);
				}
				$reports['children'] = $menu_child;
				$arr_res[]=	$reports;
		}
		
		$masters = array();
		$check_group_Master = $this->check_access_menu("Master", $level);
		if ($check_group_Master) {
				$masters =  array(
					"text" => "Master",
					"cls" => "folder",							
					"expanded" => false
				);
				
				$menu_masters = $this->getMenuUser($level, "Master");
				$menu_child = array();
				foreach ($menu_masters as $v) {
					$menu_child[] = array(
						"text" => $v['fitur_code'],
						"id" => $v['name_menu'],						
						"iconCls"=> $v['name_menu'],
						"leaf" => true
					);
				}
				$masters['children'] = $menu_child;
				$arr_res[]=	$masters;
		}
		*/
				
		$menusystem = array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false
						);
		
		
		/*if ($level == "ADMIN") {
			$arradd = array(
													"text" => "Role Managament",
													"iconCls"=>"roleusermanagement",
													"id" => "roleusermanagement",
													"leaf" => true
						);					
			$menusystem["children"][] = $arradd;					
			$arradd = array(
													"text" => "Registrasi",
													"id" => "sys_reg",
													"iconCls" => "sys_reg",
													"leaf" => true
												);
			$menusystem["children"][] = $arradd;										
		}*/
		
		$menusystem["children"][]= array(
										"text" => "Change Password",
										"id" => "sys_cp",
										"iconCls" => "btn-login",
										"leaf" => true
									);
		/*$menusystem["children"][]= array(
													"text" => "Logout",
													"id" => "syslogout",
													"iconCls" => "btn-delete",
													"leaf" => true
									);*/
							
		//$arr_res[]=	$menusystem;		
		echo json_encode($arr_res);
	}
	
	
	
	function get_menu_left($level, $be_get) {
		$resArr = array();
		
		//Formulir Test
		$array_menu[]="Formulir Test Request"; $array_group[]="Data"; $array_name[]="form_request_test"; $orderby[]="0";
		$array_menu[]="Input Report Test"; $array_group[]="Data"; $array_name[]="input_report_test"; $orderby[]="1";
		$array_menu[]="Setting Graph"; $array_group[]="Data"; $array_name[]="report_graph"; $orderby[]="2";
		
		
		
		//Report
		//$array_menu[]="Graph Report"; $array_group[]="Graph Report"; $array_name[]="rpt_generate"; $orderby[]="3";		
		$array_menu[]="Report Control Machine"; $array_group[]="Report Test"; $array_name[]="rpt_control_machine"; $orderby[]="0";
		$array_menu[]="Report Summary Test"; $array_group[]="Report Test"; $array_name[]="rpt_summary_test"; $orderby[]="1";
		$array_menu[]="Report Test Periode"; $array_group[]="Report Test"; $array_name[]="rpt_summary_test_periode"; $orderby[]="2";
		$array_menu[]="Report Acheivement"; $array_group[]="Report Test"; $array_name[]="rpt_acheivement"; $orderby[]="3";
		
		$array_menu[]="Report Test by User"; $array_group[]="Report Test"; $array_name[]="rpt_period_by_user"; $orderby[]="4";
		
		//Master
		$array_menu[]="Employee"; $array_group[]="Master"; $array_name[]="karyawan"; $orderby[]="1";
		$array_menu[]="Sample"; $array_group[]="Master"; $array_name[]="m_sample"; $orderby[]="2";
		$array_menu[]="Machine Detail"; $array_group[]="Master"; $array_name[]="m_machine_detail"; $orderby[]="3";
		$array_menu[]="Machine Field"; $array_group[]="Master"; $array_name[]="m_machine_field"; $orderby[]="4";
		$array_menu[]="Toleransi"; $array_group[]="Master"; $array_name[]="m_toleransi"; $orderby[]="5";
		
		
		if ($be_get == "left") 
			$resArr = $this->getFituresWhereNotIN($array_menu,$array_group,$array_name,$level,$orderby);
		else 
			$resArr = $this->getFituresWhereIN($array_menu,$array_group,$array_name,$level,$orderby);
		return $resArr;
	}
	
	function getMenuUser($username, $group_menu) {
		$this->db->from('sys_user_role_access');		
		$this->db->where("username",$username);
		//print 'username cuy ::::'.$username;
		$this->db->where("group",$group_menu);
		$this->db->order_by("menu_name","ASC");
		$query = $this->db->get();			
		if($query->num_rows() > 0)
			return $query->result_array();
		return array();
	}
	
	function getFituresWhereIN($fitur_code,$array_group,$array_name,$level,$orderby) {
		$this->db->from('user_role_access');
		$this->db->where_in('fitur_code', $fitur_code);
		$this->db->where("level",$level);
		$query = $this->db->get();			
		if($query->num_rows() > 0)
			return $query->result_array();
		return array();
	}
	
	function getFituresWhereNotIN($fitur_code,$array_group,$array_name, $level,$orderby) {				
		$res = array();	
		$j = 0;
		for($i=0; $i<count($fitur_code); $i++) {
			$this->db->from('user_role_access');
			$this->db->where('fitur_code', $fitur_code[$i]);
			$this->db->where("level",$level);
			$query = $this->db->get();			
			if($query->num_rows() <= 0) {
				$res[$j]['orderId'] = $orderby[$j];
				$res[$j]['name_menu'] = $array_name[$i];
				$res[$j]['level'] = $level;
				$res[$j]['fitur_code'] = $fitur_code[$i];
				$res[$j]['group'] = $array_group[$i];
				$j++;
			}
		}		
		return $res;
	}
	
	
	function save_role($data) {
		$item_x = $data['data'];
		$level = $data['level'];
		$this->db->query("delete from user_role_access WHERE level = '".$level."'");
		foreach ($item_x as $v) {	
			$this->db->insert("user_role_access",$v);
		}
		
		echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success save data'
			));
	}
	
    function check_access_menu($access_menu,$level) {
		$this->db->from('sys_user_access');
		$this->db->where('access_menu', $access_menu);
		$this->db->where("level",$level);
		$query = $this->db->get();			
		if($query->num_rows() > 0)
			return true;
		return false;
	}
	
	function get_role_fiture($fiture,$level) {
		$this->db->from('user_role_access');
		$this->db->where_in('fitur_code', $fiture);
		$this->db->where("level",$level);
		$query = $this->db->get();			
		if($query->num_rows() > 0)
			return $query->result_array();
		return array();
	}

}



