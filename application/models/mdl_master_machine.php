<?php
class Mdl_master_machine extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function get_data_machine($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
            $i= 0;
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
			
                        if (isset($_REQUEST['group_machine'])) {
                            $where .= " AND  labor_master_report_test.idmachine='".$_REQUEST['group_machine']."' ";
                        }
			 //All Level Administrator
			
			$fields = implode(",", $fields);
			$query = "SELECT ".$fields."
			FROM ".$tableName."	
                        left join labor_master_machine_test k1 on k1.idmachine = ".$tableName.".idmachine
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." 	
                    left join labor_master_machine_test k1 on k1.idmachine = ".$tableName.".idmachine
                    WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();

            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
    }
	
    function get_machine_fields($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
            $i= 0;
			#die( $where);
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
					for ($i=0; $i<count($fields);$i++){
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
                }
                
                if ($i > 0) {
                    $where .= " ) "; 
                }
            } else {
                if (isset($_REQUEST['query'])) {
                    $where .= " AND ( k1.name_report LIKE '%".$_REQUEST['query']."%' ";
                    $where .= " OR idmachinereport LIKE '%".$_REQUEST['query']."%' ";
                    $where .= " OR textlabel LIKE '%".$_REQUEST['query']."%' ";
                    $where .= " OR xtype LIKE '%".$_REQUEST['query']."%' ) ";
                } 
            }			
			
            
            if (isset($_REQUEST['lookup'])) {
                $where .= " AND idmachinereport = '".$_REQUEST['lookup']."'";
                $start = 0;
                $count = 20;        
            }
            

             //All Level Administrator

            $fields = implode(",", $fields);
            $query = "SELECT ".$fields."
            FROM ".$tableName."	
            inner join labor_master_report_test k1 on k1.idreport = ".$tableName.".idmachinereport
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
			
			#die($query);
			
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." 	
                    inner join labor_master_report_test k1 on k1.idreport = ".$tableName.".idmachinereport
                    WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();
            //print_r($rowsData);
            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
    }
    
    
    function get_machine_fields_toleransi($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
            $i= 0;
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
		    for ($i=0; $i<count($fields);$i++){
                        if ($i==0)
                           $where .= " AND (".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
                }
                
                if ($i > 0) {
                    $where .= " ) "; 
                }
            } else {
                if (isset($_REQUEST['query'])) {
                    $where .= " AND ( compound LIKE '%".$_REQUEST['query']."%' ";
                    $where .= " OR k1.field_params LIKE '%".$_REQUEST['query']."%') ";
                } 
            }			
			
            
            if (isset($_REQUEST['lookup'])) {
                $where .= " AND idreport = '".$_REQUEST['lookup']."'";
                $start = 0;
                $count = 20;        
            }
            

             //All Level Administrator

            $fields = implode(",", $fields);
            $query = "SELECT ".$fields."
            FROM ".$tableName."	
            inner join labor_master_report_test k1 on k1.idreport = ".$tableName.".machine_test_report
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
			#print_r($query);
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
			//print_r($rowsData);
            $query = "SELECT COUNT(*) FROM ".$tableName." 	
                    inner join labor_master_report_test k1 on k1.idreport = ".$tableName.".machine_test_report
                    WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();
            
            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
    }
    
    function get_field_name_master_machine($id_report) {
        $q = "SELECT * FROM labor_master_machine_fields WHERE idmachinereport = '".$id_report."' order by sortorder ASC";
		#die($q);
        $query = $this->db->query($q);
        return $query->result_array();
    }
    
    function get_mechine_user_test($userid, $owner = '') {
        $q = "SELECT a.idreport, a.name_report ,IF(b.userid IS NULL,'false','true') as is_check FROM labor_master_report_test a"
                . " left join labor_report_mesin_user b on (a.idreport = b.idreport and  b.userid = '".$userid."')"
                . " WHERE 1 = 1 AND `owner` = '" . $owner . "' order by a.idmachine ASC";
		
		
        $query = $this->db->query($q);
        $rowsData = $query->result_array();
         echo json_encode(Array(
                "total"=>999999999999,
                "data"=>$rowsData
            ));
    }
    
    function save_report_user($user_id, $post) {
        $data = $post['data'];
		var_dump($data);
        $this->db->delete("labor_report_mesin_user",array("userid"=>$user_id));
        foreach ($data as $v) {
            $arr = array("userid"=>$user_id,"idreport"=>$v['idreport']);
            $this->db->insert("labor_report_mesin_user",$arr);
        }
    }
    
    
    
	   
}



