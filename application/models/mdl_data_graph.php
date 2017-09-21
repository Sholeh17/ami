<?php
    class Mdl_data_graph extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }


    function get_row_report_test_detail($id_report_test) {
        $query = "SELECT a.* FROM labor_master_report_test a "
                . " WHERE a.idreport = '".$id_report_test."' ";
        $rs = $this->db->query($query);
        $datarow = $rs->row();
        return $datarow;
    }
    
    function get_type_graph($id_report_test) {
        $query = "SELECT a.type_graph FROM labor_config_report_test_graph a "
                . " WHERE a.id_report_test = '".$id_report_test."' ";
        $rs = $this->db->query($query);
        $datarow = $rs->row();
        if ($datarow)
               return $datarow->type_graph;
        return false;
    }
	
    
    function get_config_graph($id_report_test) {
        $q = "SELECT * FROM labor_config_report_test_graph WHERE id_report_test = '".$id_report_test."'";
        $rs = $this->db->query($q);
        $datarow = $rs->result_array();
        return $datarow;
    }
   
    
    function get_value_data_config($id_formulir, $id_report_test, $fields) {
            $q = "SELECT value FROM labor_result_value_test "
                . "WHERE idmachinereport = '".$id_report_test."' AND owner = '" . $this->session->userdata('owner') . "' "
                . " AND idformulir = '".$id_formulir."' AND namfields = '".$fields."'";
            $rs = $this->db->query($q);
            $datarow = $rs->result_array();
            return $datarow;
    }
    
    
    function get_data_result_by_field($id_formulir, $id_report_test, $fields) {
        $q = "SELECT value FROM labor_result_value_test "
                . "WHERE idmachinereport = '".$id_report_test."' AND owner = '" . $this->session->userdata('owner') . "' "
                . " AND idformulir = '".$id_formulir."' AND namfields = '".$fields."'";
        $rs = $this->db->query($q);
        $datarow = $rs->result();
        
        $arr = array();
        switch ($fields) {
            case "Avarage_y" :
                $dt = $this->get_data_result_by_average_Y($id_formulir, $id_report_test);
                $arr = $dt;
                break;
            case "Avarage_x" :
                $dt = $this->get_data_result_by_average_X($id_formulir, $id_report_test);
                $arr = $dt;
                break;
            default :
                foreach ($datarow as $v) {
                    $arr[] = $v->value;
                }
                break;
        }
        return $arr;
    }
    
    function get_data_result_by_average_X($id_formulir, $id_report_test) {
        $q = "SELECT namfields,value FROM labor_result_value_test "
                . "WHERE idmachinereport = '".$id_report_test."' AND owner = '" . $this->session->userdata('owner') . "' "
                . " AND idformulir = '".$id_formulir."'";
        $rs = $this->db->query($q);
        $datarow = $rs->result();
        
        //Get Fields 
        $fields = $this->get_labor_master_machine_fields($id_report_test);

        //Set field Array Values
        $arr_x = array();
        foreach ($fields as $v) {
            $j = 0;
            foreach ($datarow as $v1) {
                if ($v == $v1->namfields) {
                    $arr_x[$v][$j] = $v1->value;         
                    $j++;
                }
            }
        }
       
        
        //Set Field Avarage Array
        $j = 0;
        $avrg_array = array();
        foreach ($arr_x as $k=>$v) {
            //$avrg_array[$j][$k] = (array_sum($v) / count($v));
            $avrg_array[] = (array_sum($v) / count($v));
            $j++;
         }   
        return $avrg_array;
    }
    
    function get_data_result_by_average_Y($id_formulir, $id_report_test) {
        $q = "SELECT namfields,value FROM labor_result_value_test "
                . "WHERE idmachinereport = '".$id_report_test."' AND owner = '" . $this->session->userdata('owner') . "' "
                . " AND idformulir = '".$id_formulir."'";
        $rs = $this->db->query($q);
        $datarow = $rs->result();
        
        //Get Fields 
        $fields = $this->get_labor_master_machine_fields($id_report_test);

        //Set field Array Values
        $arr_x = array();
        
        foreach ($fields as $v) {
            $j = 0;
            foreach ($datarow as $v1) {
                if ($v == $v1->namfields) {
                    $arr_x[$v][$j] = $v1->value;  
                    $j++;
                }
            }
        }
        
        $cnt_nf = count($arr_x[$fields[0]]);
        $arr_y = array();
        $tv = 0;
        for ($k = 0; $k < $cnt_nf; $k++) {
            $tv = 0;
            foreach ($fields as $d) {
               $tv += $arr_x[$d][$k];
            }
          
            $arr_y[] = $tv / count($fields); 
        }
       
        return $arr_y;
    }
    
    
    
    function get_labor_master_machine_fields($id_report_test) {
        $q = "SELECT * FROM labor_master_machine_fields "
                . "WHERE idmachinereport = '".$id_report_test."'";
        $rs = $this->db->query($q);
        $datarow = $rs->result();
        $fields = array();
        foreach ($datarow as $v) {
            $fields[] = $v->namefield;
        }
        return $fields; 
    }
	
}

    

