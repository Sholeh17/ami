<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include('blackbox.php');
class Input_report_test extends Blackbox {
	var $access_right;
	function __construct()
	{
		ini_set('post_max_size', '120M');	
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','ma');
                $this->load->model('mdl_report_test','mf');
                $this->load->model('Mdl_data_graph','md');
		 $this->load->model('Mdl_report_judgement','mrj');
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("input_report_test");
                
		$this->load->view('input_report_test', $data);
	}
	function save_report_values() {            
		$array_post = $_POST;
		if ($array_post['data']['idformulir'] == "")
                    $execute = $this->mf->save_draft($array_post);
		else
                    $execute = $this->mf->update_draft($array_post, $array_post['data']['idformulir']);
		
	}
        
        
	function get_list_reports() {
		$this->defaultSortProperty = 'orderindex';
		$this->defaultSortDirection = 'asc';
		
		$this->prep();                
		$fields[] = 'labor_master_report_test.idreport';
		$fields[] = 'name_report';
                $fields[] = 'orderindex';
		
		if(isset($_REQUEST['idmachine']))
			$this->where = " idmachine = '".$_REQUEST['idmachine']."' ";
		
		
		$this->mf->get_list_report_test_item_input($fields, "labor_master_report_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
	}
	
	function get_formulir_test_is_approved() {
		#print "____________________________________________";
				$this->defaultSortProperty = 'date_request';
                $this->defaultSortDirection = 'DESC';
                $this->where = '';
                $this->prep();    
				$this->where .= " AND owner = '" . $this->session->userdata('owner'). "'";
                $fields[] = 'idformulir';
                $fields[] = 'no_req';
                $fields[] = 'date_request';
                $fields[] = 'date_line';
                $fields[] = 'sample';
                $fields[] = 'type_request';
                $fields[] = 'request_by';
                $fields[] = 'porpose';
                $fields[] = 'sample_spec';
                $fields[] = 'notes';
                $fields[] = 'scale';
                $fields[] = 'status';
                $fields[] = 'user_create';
				
				//$result = array_merge($array1, $array2);
				#print_r($fields);
				$_REQUEST = array_merge($_POST, $_GET);
				#print_r($_REQUEST);
				
				if (isset($_REQUEST['is_report']) && $_REQUEST['is_report'] == "1") {
					$level = $this->session->userdata['level'];
					$user_section = $this->session->userdata['user_section'];
					switch($level){
						case "USER": 
							if(($user_section == "CD 1") || ($user_section == "CD 2")){
								$this->where .= " AND `request_by` IN ('CD 1','CD 2') AND `status` = 'APPROVE'";
							}
							else{
								$this->where .= " AND `request_by` = '$user_section' AND `status` = 'APPROVE'";
							}
							break;
						case "GENERAL": case "ANALISA LAB": 
							if(($user_section == "CD 1") || ($user_section == "CD 2")){
								$this->where .= " AND `request_by` IN ('CD 1','CD 2') AND `status` = 'APPROVE'";
							}
							else{
								$this->where .= " AND `request_by` = '$user_section' AND `status` = 'APPROVE'";
							}
							break;
						case "SECTION HEAD":
							if(($user_section == "CD 1") || ($user_section == "CD 2")){
								$this->where .= " AND `request_by` IN ('CD 1','CD 2') AND `status` = 'APPROVE'";
							}
							else{
								$this->where .= " AND `request_by` = '$user_section' AND `status` = 'APPROVE'";
							}
							break;
						case "DEPARTMENT HEAD": 
						case "DIVISION HEAD": 
						case "ADMIN": 
							$this->where .= " AND `status` = 'APPROVE'";
							break;
						default: break;
					}
				}
				
                $this->mf->get_formulir_test_is_approved($fields, "labor_formulir_request_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
	}
	
	function get_item_compound() {
		$this->defaultSortProperty = 'name';
		$this->defaultSortDirection = 'asc';
		$this->where = '';
		$this->prep();                
		$fields[] = 'idmaterial';
		$fields[] = 'name';
		$fields[] = 'category';
		
		if (isset($_REQUEST['search_filter'])) {
			$this->where .= " AND category = '".$_REQUEST['category']."' ";
		}
		
		$this->ma->get_common_data($fields, "labor_master_material_compound", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
	}


	function get_list_formulir_items() {
			$id_formulir = $_REQUEST['id_formulir'];
			$this->mf->get_formulir_item_test($id_formulir);
	}
	
	function genarateinputformtes() {
		$data_arr = array("idformulir"=>$_REQUEST['idformulir'],"idreport"=>$_REQUEST['idreport'],"id_machine"=>$_REQUEST['idmachine']);
		$data['fields_machine'] = $this->mf->get_fields_machine($_REQUEST['idreport']);
		
		$data['title_report'] = $_REQUEST['name_report'];
		$data['namemachine'] = $_REQUEST['namemachine'];
		$data['name_report'] = $_REQUEST['name_report'];
		$data['idreportmachine'] = $_REQUEST['idreport'];
		$data['rowformulir'] = $this->mf->get_row_formulir_test($_REQUEST['idformulir']);
		
		$this->load->view('forminputtest', $data);
	}
	
	function get_item_result_testform() {
		$data_arr = array("idformulir"=>$_REQUEST['idformulir'],"idreport"=>$_REQUEST['idreport'],"id_machine"=>$_REQUEST['idmachine']);
		$data = $this->mf->get_result_machine_test($data_arr);
		#print_r($data);
		$row_files = $this->mf->get_formulir_item_test_file_attch($_REQUEST['idformulir'],$_REQUEST['idreport']);
		
		echo json_encode(array("success"=>true,"data"=>$data,"count"=>count($data), "files"=>array("data"=>array("idformulir"=>$_REQUEST['idformulir'],"idreport"=>$_REQUEST['idreport'],"file_others_1"=>$row_files->file_others_1,"file_others_2"=>$row_files->file_others_2))));
	}
	
	
	function saveparamter_test() {
		$data_post = $_POST['data'];
		$this->mf->saveFormParamTestMachine($data_post);
		$this->uploadOtherFile();
		echo json_encode(array("success"=>true, "msg"=>"Success Save!!!"));
	}
	
	
	function uploadOtherFile() {
		$arrTemp = array();
		if (!empty($_FILES['file_others_1']['name'])) {
			$file1 = $this->mf->uploadOtherFile('file_others_1',$_POST['data']['list_formulir_test']);
			$arrTemp['file_others_1'] = $file1;
		}
		
		if (!empty($_FILES['file_others_2']['name'])) {
			$file2 = $this->mf->uploadOtherFile('file_others_2', $_POST['data']['list_formulir_test']);
			$arrTemp['file_others_2'] = $file2;
		}
		
		if (!empty($arrTemp)) {
			$key = array("idformulir"=>$_POST['data']['list_formulir_test'], 
						 "idmachinetest_detail"=>$_POST['data']['list_report_test']);
			$this->mf->update_other_file_labor_forumlir_item_test($arrTemp, $key);
		}
		
	}
	
	function uploadCSV() {
		$idformulir = $_POST['data']['list_formulir_test'];
		$idmachinetest = $_POST['data']['list_report_test'];;
		$name_file = $this->mf->uploadFile($idformulir,$idmachinetest);
		 
		$this->mf->WriteTableFromCSV($idformulir, $idmachinetest, $name_file);
		echo json_encode(array("success"=>true, "msg"=>"Upload Success!!!"));
	}
        
    function get_data_graph($id_formulir, $id_reports) {
            $config_graph = $this->md->get_config_graph($id_reports);
           
            $data = array();
            $type_graph = "Line";
            $data_field = array();
            $data_res = array();
            $data_series = array();
            $i = 0;
            $fieldSeries = "";
            $Xcatogories = "";
            foreach ($config_graph as $v) {
                $type_graph = $v['type_graph'];
                    if ($v['y_field_data'] != "") {
                    $yf = $this->md->get_data_result_by_field($id_formulir, $id_reports, $v['y_field_data']);
                    foreach($yf as $a) {
                        $data_field['data'][$v['y_field_data']][$i] = $a;
                        $i++;
                    }
                
                    $i = 0;
                    $data_series[] = $v['y_field_data'];
                    $Xcatogories = $v['x_categorit_axis'];
                }
                
            }
           
            
            $data['graph_data'] = $data_field;
            $data['series'] = $data_series;
            $data['category'] = $Xcatogories;
            $data['type_graph'] = $type_graph;
            
            return $data;
        }
     
	function delete_file() {
		$this->mf->delete_file();
        echo json_encode(array("success"=>true, "msg"=>"Delete Success!!!"));
	}
	
	function print_result() {
                
		$id_formulir = $_REQUEST['list_formulir_test'];
		$id_machinetest = $_REQUEST['list_report_test'];
		$data['rowformulir'] = $this->mf->get_row_formulir_test($id_formulir);
		$data["id_machinetest"] = $_REQUEST['list_report_test'];
		$data["namereporttest"] = $_REQUEST['namereporttest'];
		$data["namemachine"] = $_REQUEST["namemachine"];
		$data['fields_machine'] = $this->mf->get_fields_machine($id_machinetest);
		
                $data['info_report_test'] = $this->md->get_row_report_test_detail($_REQUEST['list_report_test']); 
                $data['info_graph'] = $this->md->get_type_graph($_REQUEST['list_report_test']);
                
                
		$data_arr = array("idformulir"=>$_REQUEST['list_formulir_test'],"idreport"=>$_REQUEST['list_report_test'],"id_machine"=>$_REQUEST['idmachine']);
		$res = $this->mf->get_result_machine_test($data_arr);
		$data['result'] = $res;
                $data['is_excel'] = isset($_REQUEST['is_excel']) ? $_REQUEST['is_excel'] : "0" ;
                if ($data['is_excel'] == 1) {
                    $namefile = $_REQUEST['list_formulir_test'].$_REQUEST["list_report_test"].  str_replace(" ","_",$_REQUEST["namereporttest"]).".xls"; 
                    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
                    header("Content-Disposition: attachment; filename=$namefile");  //File name extension was wrong
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private",false);
                }
                
		$this->load->view('report/rpt_genarate', $data);
	}
	
	function print_result_quality() {
		$id_formulir = $_REQUEST['list_formulir_test'];
		$id_machinetest = $_REQUEST['list_report_test'];
		$data['rowformulir'] = $this->mf->get_row_formulir_test($id_formulir);
		$data["id_machinetest"] = $_REQUEST['list_report_test'];
		$data["namereporttest"] = $_REQUEST['namereporttest'];
		$data["namemachine"] = $_REQUEST["namemachine"];
		$data['fields_machine'] = $this->mf->get_fields_machine($id_machinetest);
		
                $data['info_report_test'] = $this->md->get_row_report_test_detail($_REQUEST['list_report_test']); 
                $data['info_graph'] = $this->md->get_type_graph($_REQUEST['list_report_test']);
                
                
		$data_arr = array("idformulir"=>$_REQUEST['list_formulir_test'],"idreport"=>$_REQUEST['list_report_test'],"id_machine"=>$_REQUEST['idmachine']);
		$res = $this->mf->get_result_machine_test($data_arr);
		$data['result'] = $res;
                $data['is_excel'] = isset($_REQUEST['is_excel']) ? $_REQUEST['is_excel'] : "0" ;
                if ($data['is_excel'] == 1) {
                    $namefile = $_REQUEST['list_formulir_test'].$_REQUEST["list_report_test"].  str_replace(" ","_",$_REQUEST["namereporttest"]).".xls"; 
                    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
                    header("Content-Disposition: attachment; filename=$namefile");  //File name extension was wrong
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private",false);
                }
		$this->connectMSSQL();
		$data['AX'] = $this->getDetailNoRIR($data['rowformulir']->rir);
                
		$this->load->view('report/report_quality_item', $data);
	}
	
	
}
