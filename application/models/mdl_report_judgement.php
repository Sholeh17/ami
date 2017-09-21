<?php
    class Mdl_report_judgement extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
		$this->load->library("session");
    }

	function insertTest(){
		for($i = 0; $i < 10; $i++){							
			$this->db->insert("tbl_dummy", array("kolom"=>$i));
		}
	}
		
	function get_result_test_judgement($idformulir, $id_report_test) {
			$q = "SELECT * FROM labor_result_value_test where idformulir = '".$idformulir.
					  "'AND idmachinereport = '".$id_report_test."' AND owner = '".$this->session->userdata('owner')."' order by namfields, batch ASC";
					
			$query = $this->db->query($q);
			$result = $query->result();
			
			$arr_temp = array();
			$j = 0;
			foreach ($result as $v) {
				if ($v->namfields != "") {
					$arr_temp[$v->namfields][$j]['value'] = $v->value;
					$arr_temp[$v->namfields][$j]['mode'] = $v->mode;
					$arr_temp[$v->namfields][$j]['batch'] = $v->batch;
					$arr_temp[$v->namfields][$j]['lock_row'] = $v->lock_row;
					$arr_temp[$v->namfields][$j]['pallet'] = $v->pallet;
					$arr_temp[$v->namfields][$j]['no_lot'] = $v->no_lot;
					$arr_temp[$v->namfields][$j]['time_sample'] = $v->time_sample;
					$arr_temp[$v->namfields][$j]['datetrx'] = $v->datetrx;
					$j++;
				}
			}
			
			
			return $arr_temp;	
	}
	
	function rheometer195_result_test($id_formulir) {
			$rheometer = $this->get_result_test_judgement($id_formulir, 1);
			$arr_temp = array();
			$j=0;
			foreach ($rheometer['MH'] as $v2) {
				$arr_temp[$j]['pallet'] = $v2['pallet'];
				$arr_temp[$j]['no_lot'] = $v2['no_lot'];
				$arr_temp[$j]['time_sample'] = $v2['time_sample'];
				$arr_temp[$j]['batch'] = $v2['batch'];
				$arr_temp[$j]['MH'] = $v2['value'];
				$j++;
			}
			$j=0;
			foreach ($rheometer['ML'] as $v2) {
				$arr_temp[$j]['ML'] = $v2['value'];
				$j++;
			}
			$j=0;
			foreach ($rheometer['tc10'] as $v2) {
				;
				$arr_temp[$j]['tc10'] = $v2['value'];
				$j++;
			}
			$j=0;
			foreach ($rheometer['tc50'] as $v2) {
				$arr_temp[$j]['tc50'] = $v2['value'];
				$j++;
			}
			$j=0;
			foreach ($rheometer['tc90'] as $v2) {
				$arr_temp[$j]['tc90'] = $v2['value'];
				$j++;
			}
			return $arr_temp;
	}
     
	 
	function denstie_result_test($id_formulir) {
			$rheometer = $this->get_result_test_judgement($id_formulir, 8);
			$arr_temp = array();
			$j=0;
			foreach ($rheometer['DENSITY'] as $v2) {
				$arr_temp[$j]['pallet'] = $v2['pallet'];
				$arr_temp[$j]['no_lot'] = $v2['no_lot'];
				$arr_temp[$j]['time_sample'] = $v2['time_sample'];
				$arr_temp[$j]['batch'] = $v2['batch'];
				$arr_temp[$j]['DENSITY'] = $v2['value'];
				$j++;
			}
			return $arr_temp;
	}
	
	function moone_v_result_test($id_formulir) {
			$rheometer = $this->get_result_test_judgement($id_formulir, 10);
			//print_r($rheometer);
			$arr_temp = array();
			$j=0;
			foreach ($rheometer['ML_4'] as $v2) {
				$arr_temp[$j]['pallet'] = $v2['pallet'];
				$arr_temp[$j]['no_lot'] = $v2['no_lot'];
				$arr_temp[$j]['time_sample'] = $v2['time_sample'];
				$arr_temp[$j]['batch'] = $v2['batch'];
				$arr_temp[$j]['ML_4'] = $v2['value'];
				$j++;
			}
			return $arr_temp;
	}         
	
	
	function moone_s_result_test($id_formulir) {
			$rheometer = $this->get_result_test_judgement($id_formulir, 9);
			$arr_temp = array();
			$j=0;
			foreach ($rheometer['TS_5'] as $v2) {
				$arr_temp[$j]['pallet'] = $v2['pallet'];
				$arr_temp[$j]['no_lot'] = $v2['no_lot'];
				$arr_temp[$j]['time_sample'] = $v2['time_sample'];
				$arr_temp[$j]['batch'] = $v2['batch'];
				$arr_temp[$j]['TS_5'] = $v2['value'];
				$j++;
			}
			
			$j=0;
			foreach ($rheometer['TS_35'] as $v2) {
				$arr_temp[$j]['TS_35'] = $v2['value'];
				$j++;
			}
			
			return $arr_temp;
	} 
	
	function physical_result_test($id_formulir) {
			$rheometer = $this->get_result_test_judgement($id_formulir, 11);
			$arr_temp = array();
			$j=0;
			foreach ($rheometer['Hardenss'] as $v2) {
				$arr_temp[$j]['pallet'] = $v2['pallet'];
				$arr_temp[$j]['no_lot'] = $v2['no_lot'];
				$arr_temp[$j]['time_sample'] = $v2['time_sample'];
				$arr_temp[$j]['batch'] = $v2['batch'];
				$arr_temp[$j]['Hardenss'] = $v2['value'];
				$j++;
			}
			
			$j=0;
			foreach ($rheometer['M_100mpa'] as $v2) {
				$arr_temp[$j]['M_100mpa'] = $v2['value'];
				$j++;
			}
			
			
			$j=0;
			foreach ($rheometer['M_300mpa'] as $v2) {
				$arr_temp[$j]['M_300mpa'] = $v2['value'];
				$j++;
			}
			
			$j=0;
			foreach ($rheometer['Tensile'] as $v2) {
				$arr_temp[$j]['Tensile'] = $v2['value'];
				$j++;
			}
			
			$j=0;
			foreach ($rheometer['Elongation'] as $v2) {
				$arr_temp[$j]['Elongation'] = $v2['value'];
				$j++;
			}
			
			return $arr_temp;
	}        
	
	
	function get_toleransi_param_test_report($compound, $idmenchinereport, $field) {
		$q = "SELECT start_toleransi,end_toleransi, remarks FROM labor_limit_toleransi_compound WHERE compound = '".$compound."' 
				AND  field_params = '".$field."' and  machine_test_report = '".$idmenchinereport."'";
		return $this->db->query($q)->row();		
	}
	
	function set_set_text_toleransi_param($compound, $idmenchinereport, $field) {
		$row = $this->get_toleransi_param_test_report($compound, $idmenchinereport, $field);
		$result_text = $row->start_toleransi." - ".$row->end_toleransi;
		return $result_text;
	}
	
	function get_UOM($idmenchinereport, $field) {
		$q = "SELECT UOM FROM labor_master_machine_fields WHERE  
				namefield = '".$field."' and  idmachinereport = '".$idmenchinereport."'";
		$row = $this->db->query($q)->row();	
		return $row->UOM == "" ? "Undefined" : $row->UOM;
	}
	
	function get_RemarkToleransi($compound, $idmenchinereport, $field) {
		$row = $this->get_toleransi_param_test_report($compound, $idmenchinereport, $field);
		$result_text = $row->remarks;
		return $result_text;
	}
	
	function get_Data_report_quality($idFormulir) {
		/*$q = 	" SELECT IF(data1.average_value BETWEEN data1.start_toleransi AND data1.end_toleransi, 'OK', 'NG') AS hasil, data1.*, c.UOM, c.`textlabel` FROM (
					SELECT AVG(a.`value`) AS average_value, a.`idformulir`, a.`idmachinereport`, a.`namfields`, a.`idcompound`, a.`value`,
						a.`lock_row`, a.`pallet`, a.`no_lot`, b.`start_toleransi`, b.`end_toleransi`, b.`remarks`
						FROM labor_result_value_test a 
						LEFT JOIN labor_limit_toleransi_compound b ON a.`idcompound` = b.`compound` AND a.`namfields` = b.`field_params` AND
						a.`idmachinereport` = b.`machine_test_report` 
					WHERE a.idformulir = $idFormulir GROUP BY a.`namfields`
				) data1
				LEFT JOIN labor_master_machine_fields c 
				ON data1.namfields = c.namefield AND data1.idmachinereport = c.idmachinereport";*/
		$q = "SELECT dat1.*,dat1.hasil, IF(dat1.hasil BETWEEN dat1.start_toleransi AND dat1.end_toleransi, 'OK', 'NG') AS res FROM (
				SELECT 
				  a.`idformulir`, name_report, namefield, 
				  (SELECT AVG(`value`) FROM labor_result_value_test ab WHERE ab.namfields = namefield AND ab.idformulir = a.`idformulir` AND ab.idmachinereport = c.`idreport`) AS hasil ,
				  a.`no_req`, textlabel, c.`idreport`,
				  e.`start_toleransi`, e.`end_toleransi`, e.`remarks`,
				  d.`UOM`
				FROM
				  labor_formulir_request_test a 
				  INNER JOIN labor_forumlir_item_test b 
					ON a.`idformulir` = b.`idformulir` 
				  INNER JOIN labor_master_report_test c 
					ON b.`idmachinetest_detail` = c.`idreport` 
				  INNER JOIN labor_master_machine_fields d 
					ON c.`idreport` = d.idmachinereport 
				  LEFT JOIN labor_limit_toleransi_compound e 
					ON e.machine_test_report = d.idmachinereport 
					AND e.field_params = d.namefield 
					AND e.compound = a.sample 
				WHERE a.idformulir = $idFormulir ORDER BY c.`idreport`, idfield ASC) dat1";
		
		$row = $this->db->query($q);
		return $row;
	}    
	
	
	function get_jml_data_report_quality($idFormulir) {
		
		$q = "SELECT COUNT(*) AS jml, name_report FROM (
				SELECT 
				  a.`idformulir`, name_report, namefield, 
				  (SELECT AVG(`value`) FROM labor_result_value_test ab WHERE ab.namfields = namefield AND ab.idformulir = a.`idformulir` AND ab.idmachinereport = c.`idreport`) AS hasil ,
				  a.`no_req`, textlabel, c.`idreport`,
				  e.`start_toleransi`, e.`end_toleransi`, e.`remarks`,
				  d.`UOM`
				FROM
				  labor_formulir_request_test a 
				  INNER JOIN labor_forumlir_item_test b 
					ON a.`idformulir` = b.`idformulir` 
				  INNER JOIN labor_master_report_test c 
					ON b.`idmachinetest_detail` = c.`idreport` 
				  INNER JOIN labor_master_machine_fields d 
					ON c.`idreport` = d.idmachinereport 
				  LEFT JOIN labor_limit_toleransi_compound e 
					ON e.machine_test_report = d.idmachinereport 
					AND e.field_params = d.namefield 
					AND e.compound = a.sample 
				WHERE a.idformulir = $idFormulir ORDER BY c.`idreport`, idfield ASC) dat1 GROUP BY name_report";
		
		$row = $this->db->query($q);
		return $row;
	}
	
	function get_no_reg_checksheet($idMaterial) {
		$rv_str = "";
		$q = "select no_reg_checksheet,label_testing_report, idmaterial, `name` from labor_master_material_compound where idmaterial = '$idMaterial'";
		$rows = $this->db->query($q);
		
		return $rows;
	}
	
	function get_details_formulir($idformulir){
		$q = "SELECT * FROM labor_formulir_request_test a INNER JOIN labor_forumlir_item_test b 
					ON a.`idformulir` = b.`idformulir` 
				  INNER JOIN labor_master_report_test c 
					ON b.`idmachinetest_detail` = c.`idreport` 
					WHERE a.`idformulir` =$idformulir";
		
		$rows = $this->db->query($q);
		
		return $rows;
	}
	
	function get_control_date($idformulir){
		$q = "SELECT DATE_FORMAT(datetrx,'%m-%d-%Y') AS dt, a.* FROM labor_result_value_test a WHERE a.`idformulir` = $idformulir ORDER BY datetrx DESC LIMIT 1 ";
		
		$rows = $this->db->query($q);
		
		return $rows;
	}
	
}

    

