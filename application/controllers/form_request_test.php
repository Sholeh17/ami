<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Form_request_test extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','ma');
		$this->load->model('Mdl_formulir_request','mf');
		$this->load->model('Mdl_report_test','mrt');
		
	}
	public function index()
	{           
               
		$data['role'] = $this->get_role_leve_user("form_request_test");
		$this->load->view('form_request_test', $data);
	}
	function save_formulir() {            
		$array_post = $_POST;
		#print_r($array_post);
		if ($array_post['data']['idformulir'] == "")
			$execute = $this->mf->save_draft($array_post);
		else
			$execute = $this->mf->update_draft($array_post, $array_post['data']['idformulir']);
		
	}
        
	function approve_formulir() {
		$status_value = "APPROVE";
		$idFormulir = $_REQUEST['idformulir'];
		$status_formulir = $this->mrt->get_row_formulir_test($idFormulir);	
		if ($status_formulir->status != "CHECKED") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not Success. Data harus status Checked!!'
			));
			die;
		}
		$this->mf->apdate_status_approve($idFormulir, $status_value);
	}
	
	function control_formulir(){
		$status_value = "CONTROLLED";
		$idFormulir = $_REQUEST['idformulir'];
		
		$idSample = isset($_REQUEST['idSample']) ? $_REQUEST['idSample'] : "";
		$idOwner = isset($_REQUEST['idOwner']) ? $_REQUEST['idOwner'] : "";
		if(($idSample != "") && ($idOwner != "")){
			if(($idSample == "Compound") && ($idOwner == "RD")){
				$__nb_of_rows = $this->mrt->checkValue($idFormulir);
				if($__nb_of_rows < 1){
					echo json_encode(Array(                
						"success"=>false,
						"msg"=>'Gagal. Data Rheometer belum masuk ke dalam system!!'
					));
					die;
				}
			}
		}
		
		$status_formulir = $this->mrt->get_row_formulir_test($idFormulir);	
		
		/*if ($status_formulir->date_reciept_sample == "0000-00-00" || $status_formulir->date_reciept_sample == "") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Gagal. Tanggal terima sample belum diinput oleh user input.!!'
			));
			die;
		}*/
		
		if ($status_formulir->status != "DRAFT") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not Success. Data harus status Draft!!'
			));
			die;
		}
		$this->mf->apdate_status($idFormulir, $status_value);
	}
		
        
        function check_formulir() {
		$status_value = "CHECKED";
		$idFormulir = $_REQUEST['idformulir'];
		
		$idSample = isset($_REQUEST['idSample']) ? $_REQUEST['idSample'] : "";
		$idOwner = isset($_REQUEST['idOwner']) ? $_REQUEST['idOwner'] : "";
		#print $idSample;
		#print $idOwner;
		/*if(($idSample != "") && ($idOwner != "")){
			if(($idSample == "Compound") && ($idOwner == "RD")){
				$__nb_of_rows = $this->mrt->checkValue($idFormulir);
				if($__nb_of_rows < 1){
					echo json_encode(Array(                
						"success"=>false,
						"msg"=>'Gagal. Data Rheometer belum masuk ke dalam system!!'
					));
					die;
				}
			}
		}*/
		
		$status_formulir = $this->mrt->get_row_formulir_test($idFormulir);	
		
		if ($status_formulir->date_reciept_sample == "0000-00-00" || $status_formulir->date_reciept_sample == "") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Gagal. Tanggal terima sample belum diinput oleh user input.!!'
			));
			die;
		}
		
		if ($status_formulir->status != "CONTROLLED") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not Success. Data harus status CONTROLLED!!'
			));
			die;
		}
		$this->mf->apdate_status($idFormulir, $status_value);
	}
    
	function backtodraft_formulir() {
		$status_value = "DRAFT";
		$idFormulir = $_REQUEST['idformulir'];
		$status_formulir = $this->mrt->get_row_formulir_test($idFormulir);	
		if ($status_formulir->status == "DRAFT") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Data sebelumnya sudah DRAFT!!'
			));
			die;
		}
		$this->mf->apdate_status($idFormulir, $status_value);
	}
	
	
	function delete_formulir() {
		$status_value = "DRAFT";
		$idFormulir = $_REQUEST['idformulir'];
		$status_formulir = $this->mrt->get_row_formulir_test($idFormulir);	
		if ($status_formulir->status != "DRAFT") {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Data tidak dapat dihapus. Data tidak status DRAFT!!'
			));
			die;
		}
		$this->mf->delete_formulir($idFormulir, $status_value);
	}
	
	function cencel_item_test() {
		
		$idformuliritem = $_REQUEST['idformuliritem'];
		$idformulir = $_REQUEST['idformulir'];
		$idmachine = $_REQUEST['idmachine'];
		
		if ($this->mf->chekc_item_test_intest($idformulir, $idmachine)) {
			echo json_encode(Array(                
                "success"=>false,
				"msg"=>'Not Success, Item is ready test!!'
			));
			die;
		}
		
		$this->mf->update_status_item_test($idformuliritem);
		echo json_encode(Array(                
                "success"=>true,
				"msg"=>'Success!!'
			));
	}
        
	function get_data_machine_test() {
			$idformulir = (isset($_REQUEST['idformulir']) ? $_REQUEST['idformulir'] : "");
			$this->mf->get_item_test_formulir_test($idformulir);
        }

	
	function get_data_machine_report_test() {
		$this->defaultSortProperty = 'idmachine';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();   
		$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";		
		$fields[] = 'idreport';
		$fields[] = 'idmachine';
		$fields[] = 'name_report';
		$this->ma->get_common_data($fields, "labor_master_report_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
        }
	    
    function get_list_formulir() {
                $this->defaultSortProperty = 'date_request';
                $this->defaultSortDirection = 'DESC';
                $this->where = '';
                $this->prep();      
				$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";				
                $fields[] = 'idformulir';
                $fields[] = 'no_req';
				$fields[] = 'shift_formulir';
                $fields[] = 'date_request';
                $fields[] = 'date_line';
				$fields[] = 'date_reciept_sample';
                $fields[] = 'sample';
                $fields[] = 'sample_category';
                $fields[] = 'type_request';
                $fields[] = 'request_by';
                $fields[] = 'request_by_people';
                $fields[] = 'porpose';
                $fields[] = 'sample_spec';//form_request_test
                $fields[] = 'notes';
                $fields[] = 'scale';
                $fields[] = 'status';
                $fields[] = 'user_create';
				$fields[] = 'rir';
				
				$fields[] = 'date_approved';
				$fields[] = 'queue_number';
				
                $this->ma->get_common_data($fields, "labor_formulir_request_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
        }

        function get_list_formulir_items() {
			$id_formulir = $_REQUEST['id_formulir'];
			$this->mf->get_formulir_item_test($id_formulir);
        }

        function get_formulir_item_compound_raw_material() {
			$id_formulir = $_REQUEST['id_formulir'];
			$this->mf->get_formulir_item_compound_raw_material($id_formulir);
        }
		
		function print_formulir() {
			$id_formulir = $_REQUEST['idformulir'];
			$data['rowformulir']  = $this->mrt->get_row_formulir_test($id_formulir);
            $data['items'] = $this->mrt->get_formulir_item_test_result_arr($id_formulir);
			$data['itemsraw'] = $this->mrt->get_formulir_item_raw_material_result_arr($id_formulir);
			
			$this->load->view('report/rpt_formulir', $data);
		}
		
		function print_item_progres() {
			$id_formulir = $_REQUEST['idformulir'];
			$data['rowformulir']  = $this->mrt->get_row_formulir_test($id_formulir);
            $data['items'] = $this->mrt->get_formulir_item_test_progress($id_formulir);
			$data['itemsraw'] = $this->mrt->get_formulir_item_raw_material_result_arr($id_formulir);
			$this->load->view('report/rpt_item_progress', $data);
		}
		
		function print_judgement_quick_check() {
			$this->load->model('Mdl_report_judgement','mrj');
			
			$id_formulir = $_REQUEST['idformulir'];
			$data['rowformulir']  = $this->mrt->get_row_formulir_test($id_formulir);
        
			$data['rheometer195'] = $this->mrj->rheometer195_result_test($id_formulir);
			
			$data['denstie'] = $this->mrj->denstie_result_test($id_formulir);
			
			$data['moone_v'] = $this->mrj->moone_v_result_test($id_formulir);
			
			$data['moone_s'] = $this->mrj->moone_s_result_test($id_formulir);
			
			$data['physical'] = $this->mrj->physical_result_test($id_formulir);
				
			$this->load->view('report/rpt_judgment_check', $data);
		}
		
		function print_judgement_complited() {
			#error_reporting(E_ALL);
			$this->load->model('Mdl_report_judgement','mrj');
			
			$id_formulir = $_REQUEST['idformulir'];
			$data['rowformulir']  = $this->mrt->get_row_formulir_test($id_formulir);
        
			$data['rheometer195'] = $this->mrj->rheometer195_result_test($id_formulir);
			
			$data['denstie'] = $this->mrj->denstie_result_test($id_formulir);
			
			$data['moone_v'] = $this->mrj->moone_v_result_test($id_formulir);
			
			$data['moone_s'] = $this->mrj->moone_s_result_test($id_formulir);
			
			$data['physical'] = $this->mrj->physical_result_test($id_formulir);
				
			$this->load->view('report/rpt_judgment', $data);
		}
		
		
		
		function print_judgement_quality() {
			#error_reporting(E_ALL);
			$data['is_excel'] = 0;
			$this->connectMSSQL();
			
			$this->load->model('Mdl_report_judgement','mrj');
			
			$id_formulir = $_REQUEST['idformulir'];
			$data['rowformulir']  = $this->mrt->get_row_formulir_test($id_formulir);
			
			$data['AX'] = $this->getDetailNoRIR($data['rowformulir']->rir);
			#var_dump($data['AX']);
			/*$data['rheometer195'] = $this->mrj->rheometer195_result_test($id_formulir);
			
			$data['denstie'] = $this->mrj->denstie_result_test($id_formulir);
			
			$data['moone_v'] = $this->mrj->moone_v_result_test($id_formulir);
			
			$data['moone_s'] = $this->mrj->moone_s_result_test($id_formulir);
			
			$data['physical'] = $this->mrj->physical_result_test($id_formulir);*/
				
			$this->load->view('report/report_quality_judgement', $data);
		}
		
		
		
}
