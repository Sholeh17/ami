<?php
class Mdl_rpt_acheivement extends CI_Model{
	
	function construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function get_user_analisa() {
		$q = "SELECT nama_user, user_id, name_karyawan FROM user 
					left join karyawan on user.user_id = karyawan.nik_karyawan
			WHERE level IN('ANALISA LAB') and user.del = 0 and active = 1 ";
		$query  = $this->db->query($q);
		return $query->result();
	}
	
	function get_count_plan($periode_start, $periode_end){	
		$v_sql = "SELECT bb.idreport ";
		$v_sql .= "from labor_formulir_request_test a 
					inner join labor_forumlir_item_test aa on aa.idformulir = a.idformulir  
					inner join labor_report_mesin_user bb on bb.idreport = aa.idmachinetest_detail 
					inner join user b on b.user_id = bb.userid 
					";		
		$v_sql .= " where a.date_request >= '".$periode_start."' AND a.date_request <= '".$periode_end."' group by bb.idreport";
		$query  = $this->db->query($v_sql);
		if ($query->num_rows() > 0)
			return $query->num_rows();
		return false; 
	}
	
	
	function get_count_acheivement($periode_start, $periode_end){	
		$v_sql = "
					SELECT 
							(SELECT count(distinct(d.idmachinereport))  
								FROM labor_result_value_test d where d.idformulir = a.idformulir 
								AND d.idmachinereport = aa.idmachinetest_detail) as cnt_close
					from labor_formulir_request_test a 
					inner join labor_forumlir_item_test aa on aa.idformulir = a.idformulir
					inner join labor_report_mesin_user bb on bb.idreport = aa.idmachinetest_detail
					where a.date_request >= '".$periode_start."' AND a.date_request <= '".$periode_end."'
					group by bb.idreport
					having cnt_close = 1
				";
		
		$query  = $this->db->query($v_sql);
		if ($query->num_rows() > 0) 
			return $query->num_rows();
		return false; 
	}
	
	function get_count_plan_by_user($periode_start, $periode_end){	
	
		$v_sql = "SELECT b.user_id as user_create, (SELECT count(distinct(d.idmachinereport)) 
					FROM labor_result_value_test d where d.idformulir = a.idformulir AND d.idmachinereport = aa.idmachinetest_detail) as cnt_acv 
					from labor_formulir_request_test a 
					inner join labor_forumlir_item_test aa on aa.idformulir = a.idformulir 
					inner join labor_report_mesin_user bb on bb.idreport = aa.idmachinetest_detail 
					inner join user b on b.user_id = bb.userid 
					where a.date_request >= '".$periode_start."' AND a.date_request <= '".$periode_end."' and
					   b.level IN('ANALISA LAB') 
					group by bb.idreport 
				";
	
		$query  = $this->db->query($v_sql);
		//echo $query->num_rows();
		if ($query->num_rows() > 0)
			return $query->result();
		return array(); 
	}
	
	
	
	function get_count_acheivement_by_user($periode_start, $periode_end){	
		$v_sql = "SELECT bb.userid as user_create, (SELECT count(distinct(d.idmachinereport)) 
					FROM labor_result_value_test d where d.idformulir = a.idformulir AND d.idmachinereport = aa.idmachinetest_detail) as cnt_acv 
					from labor_formulir_request_test a 
					inner join labor_forumlir_item_test aa on aa.idformulir = a.idformulir 
					inner join labor_report_mesin_user bb on bb.idreport = aa.idmachinetest_detail 
					inner join user b on b.user_id = bb.userid 
					where a.date_request >= '".$periode_start."' AND a.date_request <= '".$periode_end."' and
					   b.level IN('ANALISA LAB') 
					group by bb.idreport having cnt_acv = 1
				";
		$query  = $this->db->query($v_sql);
		if ($query->num_rows() > 0) 
			return $query->result();
		return array(); 
	}
	
	
	function get_all_data_by_user($periode_start, $periode_end){	
		$v_sql = "SELECT 
					  a.nama_user,
					  a.user_id,
					  IFNULL(qq1.jml_ach, 0) AS jml_achievement,
					  IFNULL(qq2.jml_plan, 0) AS jml_plan,
					  ww1.file_name					  
					FROM
					  USER a 
					  LEFT JOIN karyawan b 
						ON a.user_id = b.nik_karyawan 
					LEFT JOIN user_photos ww1
						ON ww1.`user_id` = a.`user_id` 
					  LEFT JOIN 
						(SELECT 
						  SUM(cnt_acv) AS jml_ach,
						  user_create 
						FROM
						  (SELECT 
							bb.userid AS user_create,
							(SELECT 
							  COUNT(DISTINCT (d.idmachinereport)) 
							FROM
							  labor_result_value_test d 
							WHERE d.idformulir = a.idformulir 
							  AND d.idmachinereport = aa.idmachinetest_detail) AS cnt_acv 
						  FROM
							labor_formulir_request_test a 
							INNER JOIN labor_forumlir_item_test aa 
							  ON aa.idformulir = a.idformulir 
							INNER JOIN labor_report_mesin_user bb 
							  ON bb.idreport = aa.idmachinetest_detail 
							INNER JOIN USER b 
							  ON b.user_id = bb.userid 
						  WHERE a.date_request >= '$periode_start' 
							AND a.date_request <= '$periode_end' 
							AND b.level IN ('ANALISA LAB') 
						  GROUP BY bb.idreport 
						  HAVING cnt_acv = 1) AS qq 
						GROUP BY user_create) AS qq1 
						ON a.`user_id` = qq1.user_create 
					  LEFT JOIN 
						(SELECT 
						  COUNT(cnt_acv) AS jml_plan,
						  user_create 
						FROM
						  (SELECT 
							b.user_id AS user_create,
							(SELECT 
							  COUNT(DISTINCT (d.idmachinereport)) 
							FROM
							  labor_result_value_test d 
							WHERE d.idformulir = a.idformulir 
							  AND d.idmachinereport = aa.idmachinetest_detail) AS cnt_acv 
						  FROM
							labor_formulir_request_test a 
							INNER JOIN labor_forumlir_item_test aa 
							  ON aa.idformulir = a.idformulir 
							INNER JOIN labor_report_mesin_user bb 
							  ON bb.idreport = aa.idmachinetest_detail 
							INNER JOIN USER b 
							  ON b.user_id = bb.userid 
						  WHERE a.date_request >= '$periode_start' 
							AND a.date_request <= '$periode_end' 
							AND b.level IN ('ANALISA LAB') 
						  GROUP BY bb.idreport 
						  ORDER BY b.`user_id`) AS qq 
						GROUP BY user_create) AS qq2 
						ON a.user_id = qq2.user_create 
					WHERE a.level IN ('ANALISA LAB') 
					  AND a.del = 0 
					  AND a.active = 1 
				";#print $v_sql;
		$query  = $this->db->query($v_sql);
		if ($query->num_rows() > 0) 
			return $query->result();
		return array(); 
	}
	
}