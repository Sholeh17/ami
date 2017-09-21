<?php
class Mdl_rpt_control_machine extends CI_Model{
	
	//variable table
	private $v_tformulir_test = "labor_formulir_request_test";	
	private $v_tmachine_field = "labor_master_machine_test";
	private $v_tvalue_test = "labor_result_value_test";
	
	function construct(){
		parent::__construct();
		
		set_time_limit(0);
		ini_set('mysql.connect_timeout','0');
		ini_set('max_execution_time','0');
		
		$this->load->database();
		
		$this->db->query("SET GLOBAL connect_timeout=99999;");
		
	}
	
	function list_formulir($v_dte_frm='',$v_dte_pto='',$v_pno_req=''){
		
		$v_sql = "select idformulir, no_req, date_request, date_line, sample_category, date_reciept_sample,";
		$v_sql .= "sample,type_request,request_by,request_by_people,porpose,sample_spec,criteria,scale,status ";		
		$v_sql .= "from labor_formulir_request_test ";
		
		$v_sql .= "WHERE `owner`= '".$this->session->userdata('owner')."' ";
		
		if($v_dte_frm && $v_dte_pto && $v_pno_req){
			$v_sql .= "and date_request between '".$v_dte_frm."' and '".$v_dte_pto."' and (no_req = '".$v_pno_req."') ";
		}elseif($v_dte_frm && $v_dte_pto){
			$v_sql .= "and date_request between '".$v_dte_frm."' and '".$v_dte_pto."' ";
		}elseif($v_dte_frm && $v_pno_req){
			$v_sql .= "and date_request = '".$v_dte_frm."' and (no_req = '".$v_pno_req."') ";
		}elseif($v_dte_pto && $v_pno_req){
			$v_sql .= "and date_request = '".$v_dte_pto."' and (no_req = '".$v_pno_req."') ";
		}elseif($v_dte_frm){
			$v_sql .= "and date_request >= '".$v_dte_frm."' ";
		}elseif($v_dte_pto){
			$v_sql .= "and date_request <= '".$v_dte_pto."' ";
		}elseif($v_pno_req){
			$v_sql .= "and no_req = '".$v_pno_req."' ";
		}
		$v_sql .= "order by date_request desc ";
		#print $v_sql;
		return $this->db->query($v_sql);
	}
	
	function list_machine(){
	
		$v_sql = "select idreport as idmachine, name_report as name ";
		$v_sql .= "from labor_master_report_test inner join labor_master_machine_test on labor_master_machine_test.idmachine =  labor_master_report_test.idmachine ";
		$v_sql .= "order by labor_master_machine_test.idmachine ASC ";
		
		return $this->db->query($v_sql);
	}
	
	function list_value($v_frm_pid){
	
		$v_sql = "select id_result,idformulir,idmachinereport,value,lock_row,update_date ";
		$v_sql .= "from labor_result_value_test ";
		$v_sql .= "where idformulir = ".$v_frm_pid." ";
		$v_sql .= "order by idmachinereport asc ";
		
		return $this->db->query($v_sql)->result();
	}
	
	function list_machine_formulir($id_formulir) {		
		
		$v_sql = "select distinct a.* from labor_forumlir_item_test a ";
		$v_sql .= "inner JOIN labor_master_machine_fields b ";
		$v_sql .= "on a.idmachinetest = b.idmachinereport ";
		$v_sql .= "where a.idformulir = ".$id_formulir." ";
		$v_sql .= "and a.idmachinetest > 0  AND status_item <> 'Cencel' ";
		$v_sql .= "and a.idmachinetest_detail > 0  ";
		$v_sql .= "order by a.idmachinetest_detail asc ";

		return $this->db->query($v_sql)->result();
	}
	
	function get_formulir_test_is_approved($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
		$i= 0;
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
				for ($i=0; $i<count($fields);$i++){
				
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
					$where = str_replace("name",$tableName.".name",$where);
                }
            } else {
			}			
			
			if ($i > 0) {
				$where .= " ) "; 
			}
			
			
			$where .= " AND status = 'APPROVE' ";
			 //All Level Administrator
			
			$fields = implode(",", $fields);
			$query = "SELECT ".$fields."
			FROM ".$tableName."			
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." 			
			WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();

            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
	}
}