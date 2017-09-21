<?php
class Mdl_master_data extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
	
     function get_common_data($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count, $filter_downlink = "") {
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

			 //All Level Administrator

			$fields = implode(",", $fields);
			$query = "SELECT ".$fields."
			FROM ".$tableName."			
            WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;
			#die($query);
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



