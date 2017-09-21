<?php
class Mdl_master_karyawan extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function get_data_karyawan($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
			$i= 0;
			$where .= " AND ".$tableName.".del_karyawan = 0 ";
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
		    for ($i=0; $i<count($fields);$i++){
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
                }
            }			
			
			if ($i > 0) {
				$where .= " ) "; 
			}
			
			 //All Level Administrator
			
			$fields = implode(",", $fields);
			$query = "SELECT ".$fields.",
			(SELECT k1.name_karyawan FROM karyawan k1 WHERE k1.id_seq_karyawan = ".$tableName.".id_atasan_1) as name_atasan_1,
			(SELECT k2.name_karyawan FROM karyawan k2 WHERE k2.id_seq_karyawan = ".$tableName.".id_atasan_2) as name_atasan_2,
			(SELECT k3.name_karyawan FROM karyawan k3 WHERE k3.id_seq_karyawan = ".$tableName.".id_atasan_3) as name_atasan_3
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
	
	function save_karyawan($arr_post) {
        return $this->db->insert("karyawan", $arr_post);
    }

    function update_karyawan($arr_post, $arr_key) {
            return $this->db->update("karyawan", $arr_post, array("id_seq_karyawan" => $arr_key));
    } 

	function get_data_karyawan_by_user($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
			$i= 0;
			$where .= " AND ".$tableName.".del = 0 ";
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
		    for ($i=0; $i<count($fields);$i++){
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
                }
            }			
			
			if ($i > 0) {
				$where .= " ) "; 
			}
			
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



