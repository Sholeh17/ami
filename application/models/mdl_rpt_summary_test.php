<?php
class mdl_rpt_summary_test extends CI_Model{
	
	//variable table
	private $v_tformulir_test = "labor_formulir_request_test";	
	private $v_tmachine_field = "labor_master_machine_test";
	private $v_tvalue_test = "labor_result_value_test";
	
	function construct(){
	
		parent::__construct();
		$this->load->database();
	}
	
	function list_formulir($v_pno_req=''){		
		
		$v_sql = "SELECT a.idformulir,a.idmachinetest_detail,b.sample, b.no_req,c.idreport,c.name_report as `name` ";
		$v_sql .= "from labor_forumlir_item_test a ";
		$v_sql .= "inner join labor_formulir_request_test b ";
		$v_sql .= "on b.idformulir = a.idformulir ";
		$v_sql .= "inner join labor_master_report_test c ";
		$v_sql .= "on a.idmachinetest_detail = c.idreport ";		
		if($v_pno_req){
			$v_sql .= "where b.no_req = '".$v_pno_req."' ";
		}
		$v_sql .= "order by trim(c.name_report) asc ";
		
		return $this->db->query($v_sql);
	}
	
	function list_formulir_periode($v_pno_req='', $mesin_test){		
		
		$v_sql = "SELECT a.idformulir,a.idmachinetest_detail,b.sample, b.no_req,c.idreport,c.name_report as `name` ";
		$v_sql .= "from labor_forumlir_item_test a ";
		$v_sql .= "inner join labor_formulir_request_test b ";
		$v_sql .= "on b.idformulir = a.idformulir ";
		$v_sql .= "inner join labor_master_report_test c ";
		$v_sql .= "on a.idmachinetest_detail = c.idreport ";		
		$v_sql .= "where b.no_req = '".$v_pno_req."' ";
		
		if ($mesin_test != "")
			$v_sql .= "AND a.idmachinetest = '".$mesin_test."' ";
		
		
		$v_sql .= "order by trim(c.name_report) asc ";#print $v_sql;
		
		return $this->db->query($v_sql);
	}
	
	
	function list_machine($v_pno_req, $id_report_test, $idformulir){
		
		
			
			$v_sql = "SELECT a.idformulir,c.idfield,c.idmachinereport,c.namefield,textlabel,
						coalesce((select avg(x.value) from labor_result_value_test x 
							where x.idmachinereport = c.idmachinereport 
							and x.idformulir = '".$idformulir."' 
							and  x.namfields = c.namefield and x.value > 0
						),0) as avg_val
						from labor_formulir_request_test a ";
			$v_sql .= "inner join labor_forumlir_item_test b ";
			$v_sql .= "on a.idformulir = b.idformulir ";
			$v_sql .= "INNER JOIN labor_master_machine_fields c ";
			$v_sql .= "on b.idmachinetest_detail = c.idmachinereport ";		
			$v_sql .= "where  a.no_req = '".$v_pno_req."' and c.idmachinereport = '".$id_report_test."' ";
			$v_sql .= "group by c.namefield, b.idmachinetest_detail ";	
		
		
		//echo $v_sql;
		$res = $this->db->query($v_sql);
		return $res->result();
	}	
	
	function machine_ave($v_pno_req, $id_report_test, $idformulir){		
		
		$v_sql = "SELECT x.idformulir, c.idfield,c.namefield,x.`value` as avg_val ";
		$v_sql .= "from labor_result_value_test x ";
		$v_sql .= "INNER JOIN labor_master_machine_fields c on x.namfields = c.namefield "; 
		$v_sql .= " where x.idmachinereport = '".$id_report_test."' ";
		$v_sql .= " and c.idmachinereport = '".$id_report_test."' ";
		$v_sql .= "and x.idformulir = '".$idformulir."' ";
		$v_sql .= "order by x.id_result ";
		
		$res = $this->db->query($v_sql);
		return $res->result_array();
		
	}
	
	function formula_sepc($v_pno_req, $id_report_test, $idformulir,$v_pid){

		$v_sql = "SELECT c.idfield,c.idmachinereport,c.namefield,d.`value` as res_val ";
		$v_sql .= "from labor_formulir_request_test a  ";
		$v_sql .= "inner join labor_forumlir_item_test b ";
		$v_sql .= "on a.idformulir = b.idformulir ";
		$v_sql .= "INNER JOIN labor_master_machine_fields c ";
		$v_sql .= "on b.idmachinetest_detail = c.idmachinereport ";
		$v_sql .= "inner join labor_result_value_test d ";
		$v_sql .= "on d.idmachinereport = c.idmachinereport ";
		$v_sql .= "where a.no_req = '".$v_pno_req."' ";
		$v_sql .= "and c.idmachinereport = ".$id_report_test." ";
		$v_sql .= "and c.idfield = ".$v_pid." ";
		$v_sql .= "and d.idformulir = ".$idformulir." ";
		$v_sql .= "and d.namfields = c.namefield ";
		$v_sql .= "order by c.namefield desc ";	
		
		$res = $this->db->query($v_sql);
		return $res->result();
	}
	
	function list_value($v_frm_pid, $id_report){
	
		$v_sql = "select namfields, value ";
		$v_sql .= "from labor_result_value_test ";
		$v_sql .= "where idformulir = ".$v_frm_pid." and idmachinereport = '".$id_report."'";
		$v_sql .= "order by namfields, idmachinereport,id_result asc ";
		
		return $this->db->query($v_sql)->result_array();
	}
	
	
	function list_value_two($v_frm_pid, $id_report){
	
		$v_sql = "select namfields, value ";
		$v_sql .= "from labor_result_value_test ";
		$v_sql .= "where idformulir = ".$v_frm_pid." and idmachinereport = '".$id_report."'";
		$v_sql .= "order by namfields, idmachinereport asc ";
		
		//echo $v_sql;
		return $this->db->query($v_sql);
	}
	function list_machine_formulir($id_formulir) {
		$v_sql = "select * ";
		$v_sql .= "from labor_forumlir_item_test ";
		$v_sql .= "where idformulir = ".$id_formulir." ";
		$v_sql .= "order by idmachinetest_detail asc ";
		
		return $this->db->query($v_sql)->result();
	}
	
	/*function get_formulir_test_by_user($where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = ""){
		#print "____________########################";
		$level = $this->session->userdata['level'];
		$user_section = $this->session->userdata['user_section'];
		#print $level;
		#print $user_section;
		switch($level){
			case "USER": 
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` = 'USER' AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` = 'USER' AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								"; 
				}
				else{
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` = 'USER' AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` = 'USER' AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								"; 
				}
				break;
			case "GENERAL": case "ANALISA LAB": 
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('USER','ANALISA LAB') AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('USER','ANALISA LAB') AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								"; 
				}
				else{
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('USER','ANALISA LAB') AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('USER','ANALISA LAB') AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								"; 
				}
				break;
			case "SECTION HEAD":
				if(($user_section == "CD 1") || ($user_section == "CD 2")){
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('SECTION HEAD','USER','ANALISA LAB') AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('SECTION HEAD','USER','ANALISA LAB') AND b.`user_section` IN ('CD 1','CD 2') AND a.`status` = 'APPROVE'
								"; 
				}
				else{
					$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('SECTION HEAD','USER','ANALISA LAB') AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								";
								
					$sql1 = "SELECT COUNT(*) AS jml
								FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
								WHERE b.`owner` = 'RD' AND b.`level` IN ('SECTION HEAD','USER','ANALISA LAB') AND b.`user_section` = '$user_section' AND a.`status` = 'APPROVE'
								"; 
				}
				break;
			case "DEPARTMENT HEAD": 
			case "DIVISION HEAD": 
			case "ADMIN": 
				$sql = "SELECT a.idformulir, a.`no_req`, a.`date_request`, a.`date_line`, a.`sample`, a.`type_request`, a.request_by, a.`porpose`, a.`sample_spec`, a.`notes`, a.`scale`, a.`status`, a.`user_create`
							FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
							WHERE b.`owner` = 'RD' AND a.`status` = 'APPROVE'
							";
							
				$sql1 = "SELECT COUNT(*) AS jml
							FROM labor_formulir_request_test a LEFT JOIN `user` b ON a.user_create = b.`user_id`
							WHERE b.`owner` = 'RD' AND a.`status` = 'APPROVE'
							"; 
				break;
			default: break;
		}
		
		$sql .= " ORDER BY ".$sortProperty." ".$sortDirection;
		$sql .= " LIMIT ".$start.",".$count;#print $sql;print $sql1;
		$rs = $this->db->query($sql);
		$rowsData = $rs->result_array();
		$rs = $this->db->query($sql1);
		$rowsCount = $rs->result_array();
		print $rowsCount[0]['jml'];
		echo json_encode(Array(
			"total"=>$rowsCount[0]['jml'],
			"data"=>$rowsData
		));
	}*/
	
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
	
	function formula_report($id_formulir, $id_report, $arr_field) {
		
		switch ($id_report) {
			case "1" :
				$redata = $this->list_value($id_formulir, $id_report);
				$res = 0;
				$a = 0; $b = 0;
				foreach ($redata as $key => $v) {
						if ($key == "mh")
							$a = $v['mh'];
						if ($key == "ml")
							$a = $v['ml'];
				}
				$res = $b-$a;
			break;
			
		}
		
		
	}
	
	function get_formulir_periode_by_sample($sampel, $start, $end) {
		$q = "SELECT idformulir,no_req, sample,date_request  FROM labor_formulir_request_test 
				WHERE sample = '".$sampel."' AND date(date_request) >= '".$start."'
				 AND date(date_request) <= '".$end."' ";#print $q;
		$rs = $this->db->query($q);	
		return $rs->result_array();	 
	}
	
	function get_formulir_periode_by_sample_and_tahun($sampel, $tahun, $user_name) {
		$q = "SELECT idformulir,no_req, sample,date_request  FROM labor_formulir_request_test 
				WHERE sample = '".$sampel."' AND year(date_request) = $tahun and user_create = '$user_name'";
		$rs = $this->db->query($q);	
		return $rs->result_array();	 
	}
	
	
}