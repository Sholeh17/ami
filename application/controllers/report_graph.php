<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Report_graph extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','ma');
                $this->load->model('mdl_report_test','mf');
	}
	public function index()
	{           
		$data['role'] = $this->get_role_leve_user("input_report_test");
		$this->load->view('form_genarate_graph', $data);
	}
        
        function get_list_item_test_graph() {
                $this->mf->get_is_item_graph();
        }
	
        function get_filed_itemtes() {
               $idreport = $_REQUEST['idreport'];
               $data_attr = $this->mf->get_fields_machine($idreport);
               $j= 0;
               foreach ($data_attr['fields'] as $k=>$v) {
                   $rowsData[$j]['fields'] = $v;
                   $rowsData[$j]['textlabel'] = $data_attr['textlabel'][$k];
                   $j++;
               }
              /* $rowsData[$j]['fields'] = 'Avarage_x';
               $rowsData[$j]['textlabel'] = 'Avarage (X)';
               $j++;
               $rowsData[$j]['fields'] = 'Avarage_y';
               $rowsData[$j]['textlabel'] = 'Avarage (Y)';*/
               
               echo json_encode(Array(
                "total"=>1000,
                "data"=>$rowsData
            ));
        }
        
        function save_config() {
            $this->mf->save_config_graph($_POST);
            echo json_encode(array("success"=>true, "msg"=>"Success Save!!!"));
        }
        
        
        function get_data_config() {
            $data_arr = array("id_report_test"=>$_POST['id_report_test']);
            $data = $this->mf->get_items_config_graph($data_arr);
            echo json_encode(array("success"=>true,"data"=>$data,"count"=>count($data)));
        }
	
	
}
