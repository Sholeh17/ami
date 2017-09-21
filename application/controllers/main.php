<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class main extends Blackbox {
	function __construct()
        {
            parent::__construct();
            $this->check();
            $this->load->helper('url');
			
        }
	public function index()
	{	
		$this->load->helper('url');
		$this->load->view('header');		
		$data = "";
		$this->load->view('backend',$data);
		$this->load->view('footer');
	}
	
	
	
	/*function get_menu() {
		$this->load->library("session");
		$GROUP = $this->session->userdata('group_user');
		$LEVEL = $this->session->userdata('level');		
		switch ($LEVEL) {
			case "USER":
				$this->get_menu_user();
			break;			
			case "ADMIN":
				$this->get_menu_admin();
			break;
			case "SALES MANAGER":
				$this->get_menu_sales_manager();
			break;			
			case "DIVISION HEAD":
				$this->get_menu_division_head();
			break;
			case "BOD":
				$this->get_menu_bod();
			break;
		}	
	}
		
	function get_menu_user() {
		$arr = array(
					array(
							"text" => "Proses Order",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Purchase Order",
													"id" => "new_po",
													"iconCls"=>"new_po",
													"leaf" => true
												),
												array(
													"text" => "Output Order",
													"id" => "output_order",
													"iconCls"=>"output_order",
													"leaf" => true
												),
												array(
													"text" => "Sales Order",
													"id" => "sales_order",
													"iconCls"=>"sales_order",
													"leaf" => true
												)
							)
						)
					,
					array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(												
												array(
													"text" => "Change Password",
													"id" => "sys_cp",
													"iconCls" => "btn-login",
													"leaf" => true
												),array(
													"text" => "Logout",
													"id" => "syslogout",
													"iconCls" => "btn-delete",
													"leaf" => true
												)
							)
						)				
				);
		echo json_encode($arr);
	
	}	

	
	function get_menu_admin() {
		$arr = array(
					array(
							"text" => "Proses Order",
							"cls" => "folder",							
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Purchase Order",
													"id" => "new_po",
													"hide" => "true",
													"iconCls"=>"new_po",
													"leaf" => true
												),
												array(
													"text" => "Output Order",
													"id" => "output_order",
													"iconCls"=>"output_order",
													"leaf" => true
												),
												array(
													"text" => "Sales Order",
													"id" => "sales_order",
													"iconCls"=>"sales_order",
													"leaf" => true
												)
							)
						)
					,
					array(
							"text" => "Report",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(								
									array(
										"text" => "Production Capacity Plan",
										"iconCls"=>"view_pcp",
										"id" => "view_pcp",
										"leaf" => true
									),array(
										"text" => "Customer Alocation Plan",
										"iconCls"=>"view_cap",
										"id" => "view_cap",
										"leaf" => true
									),
									array(
										"text" => "Profitability",
										"iconCls"=>"view_profitability",
										"id" => "view_profitability",
										"leaf" => true
									)							
							)
						)
					,
					array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Role Managament",
													"iconCls"=>"roleusermanagement",
													"id" => "roleusermanagement",
													"leaf" => true
												),array(
													"text" => "Employee",
													"iconCls"=>"karyawan",
													"id" => "karyawan",
													"leaf" => true
												),
												array(
													"text" => "Registrasi",
													"id" => "sys_reg",
													"iconCls" => "sys_reg",
													"leaf" => true
												),
												array(
													"text" => "Change Password",
													"id" => "sys_cp",
													"iconCls" => "btn-login",
													"leaf" => true
												),array(
													"text" => "Logout",
													"id" => "syslogout",
													"iconCls" => "btn-delete",
													"leaf" => true
												)
							)
						)				
				);
				
		echo json_encode($arr);
	}
	
	
	function get_menu_sales_manager() {
		$arr = array(
					array(
							"text" => "Proses Order",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Purchase Order",
													"id" => "new_po",
													"iconCls"=>"new_po",
													"leaf" => true
												),
												array(
													"text" => "Output Order",
													"id" => "output_order",
													"iconCls"=>"output_order",
													"leaf" => true
												),
												array(
													"text" => "Sales Order",
													"id" => "sales_order",
													"iconCls"=>"sales_order",
													"leaf" => true
												)
							)
						)
					,
					array(
							"text" => "Report",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(								
									array(
										"text" => "Production Capacity Plan",
										"iconCls"=>"report",
										"id" => "view_pcp",
										"leaf" => true
									),array(
										"text" => "Customer Alocation Plan",
										"iconCls"=>"report",
										"id" => "view_cap",
										"leaf" => true
									),
									array(
										"text" => "Profitability",
										"iconCls"=>"view_profitability",
										"id" => "view_profitability",
										"leaf" => true
									)							
							)
						)
					,
					array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(	
												array(
													"text" => "Change Password",
													"id" => "sys_cp",
													"iconCls" => "btn-login",
													"leaf" => true
												),array(
													"text" => "Logout",
													"id" => "syslogout",
													"iconCls" => "btn-delete",
													"leaf" => true
												)
							)
						)				
				);
		echo json_encode($arr);
	}
	
	
	function get_menu_division_head() {
		$arr = array(
					array(
							"text" => "Proses Order",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Purchase Order",
													"id" => "new_po",
													"iconCls"=>"new_po",
													"leaf" => true
												),
												array(
													"text" => "Output Order",
													"id" => "output_order",
													"iconCls"=>"output_order",
													"leaf" => true
												),
												array(
													"text" => "Sales Order",
													"id" => "sales_order",
													"iconCls"=>"sales_order",
													"leaf" => true
												)
							)
						)
					,
					array(
							"text" => "Report",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(								
									array(
										"text" => "Production Capacity Plan",
										"iconCls"=>"view_pcp",
										"id" => "view_pcp",
										"leaf" => true
									),array(
										"text" => "Customer Alocation Plan",
										"iconCls"=>"view_cap",
										"id" => "view_cap",
										"leaf" => true
									),
									array(
										"text" => "Profitability",
										"iconCls"=>"view_profitability",
										"id" => "view_profitability",
										"leaf" => true
									)							
							)
						)
					,
					array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(												
												array(
													"text" => "Change Password",
													"id" => "sys_cp",
													"leaf" => true
												),array(
													"text" => "Logout",
													"id" => "syslogout",
													"leaf" => true
												)
							)
						)				
				);
				
		echo json_encode($arr);
	}
	
	function get_menu_bod() {
		$arr = array(
					array(
							"text" => "Proses Order",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(
												array(
													"text" => "Purchase Order",
													"id" => "new_po",
													"iconCls"=>"new_po",
													"leaf" => true
												),
												array(
													"text" => "Output Order",
													"id" => "output_order",
													"iconCls"=>"output_order",
													"leaf" => true
												),
												array(
													"text" => "Sales Order",
													"id" => "sales_order",
													"iconCls"=>"sales_order",
													"leaf" => true
												)
							)
						)
					,
					array(
							"text" => "Report",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(								
									array(
										"text" => "Production Capacity Plan",
										"iconCls"=>"view_pcp",
										"id" => "view_pcp",
										"leaf" => true
									),
									array(
										"text" => "Customer Alocation Plan",
										"iconCls"=>"view_cap",
										"id" => "view_cap",
										"leaf" => true
									),
									array(
										"text" => "Profitability",
										"iconCls"=>"view_profitability",
										"id" => "view_profitability",
										"leaf" => true
									)							
							)
						)
					,
					array(
							"text" => "System",
							"cls" => "folder",
							"expanded" => false,
							"children" => array(												
												array(
													"text" => "Change Password",
													"id" => "sys_cp",
													"leaf" => true
												),array(
													"text" => "Logout",
													"id" => "syslogout",
													"leaf" => true
												)
							)
						)				
				);
		echo json_encode($arr);
	}
*/
	
}
