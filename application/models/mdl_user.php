<?php
class Mdl_user extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
		$this->load->library("session");
    }	
    function save_user($arr_post) {
			$arr_post['active'] = isset($arr_post['active']) ? $arr_post['active'] : 0;
            if ($this->checkt_user_available(0, $arr_post['user_id'])) {
                $result_arr = array("failure" => "true", "msg" => "UserID is available!!");
                echo json_encode($result_arr);
                die;
            }
			$data_arr = array("user_id"=>$arr_post['user_id'],
						"password"=>md5($arr_post['password']),
						"level"=>$arr_post['level'],
						"user_section"=>$arr_post['user_section'],
						"group_user"=>$arr_post['group_user'],
						"amount_approve"=>(isset($arr_post['amount_approve']) ? $arr_post['amount_approve']: 0),
						"create_date"=>"ADMIN",
						"nama_user"=>$arr_post['nama_user'],"active"=>$arr_post['active'],
						"owner"=>$arr_post['owner']
						);
            return $this->db->insert("user", $data_arr);
    }
    function update_user($arr_post, $id_pk) {         
			$arr_post['active'] = isset($arr_post['active']) ? $arr_post['active'] : 0;			
			$data_arr = array(
						"level"=>$arr_post['level'],
						"user_section"=>$arr_post['user_section'],
						"group_user"=>$arr_post['group_user'],
						"amount_approve"=>(isset($arr_post['amount_approve']) ? $arr_post['amount_approve']: 0),
						"create_date"=>"ADMIN",
						"owner"=>$arr_post['owner'],
						"nama_user"=>$arr_post['nama_user'],"active"=>$arr_post['active']);
			if (isset($arr_post['password'])) 
				$data_arr["password"] = $arr_post['password'];
            return $this->db->update("user", $data_arr, array("id_seq" => $id_pk));
    }

    function update_rules_access($user, $level, $data_rule) {
        
    }



    function get_data_users($fields, $tableName, $where, $sortProperty, $sortDirection, $start, $count) {
            $where .= " AND del = 0  AND owner = '".$this->session->userdata('owner')."' ";
            if (isset($_REQUEST['query']) && !isset($_REQUEST['filter'])) {
                if (is_array($fields)) {
		    for ($i=0;$i<count($fields);$i++){
                        if ($i==0)
                           $where .= " AND ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                        else
                            $where .= " OR ".$fields[$i]." LIKE '%".$_REQUEST['query']."%' " ;
                    }
                }
            }
            $fields = implode(",", $fields);
            $query = "SELECT ".$fields." FROM ".$tableName." WHERE ".$where;
            $query .= " ORDER BY ".$sortProperty." ".$sortDirection;
            $query .= " LIMIT ".$start.",".$count;            
            $rs = $this->db->query($query);
            $rowsData = $rs->result_array();
            $query = "SELECT COUNT(*) FROM ".$tableName." WHERE ".$where;
            $rs = $this->db->query($query);
            $rowsCount = $rs->result_array();
			

            echo json_encode(Array(
                "total"=>$rowsCount[0]['COUNT(*)'],
                "data"=>$rowsData
            ));
    }


    function checkt_user_available($id_seq, $user) {
        $query = "SELECT COUNT(*) FROM user WHERE id_seq <> $id_seq AND  user_id = '$user' ";
        $rs = $this->db->query($query);
        $rowsCount = $rs->result_array();
        if ($rowsCount[0]['COUNT(*)'] > 0)
            return true;
        return false;
    }

    function get_user_id($id_seq) {
            $q = "SELECT user_id FROM user WHERE id_seq = ".$id_seq;
            $query = $this->db->query($q);
            $row = $query->row();
            return $row->user_id;
    }

}



