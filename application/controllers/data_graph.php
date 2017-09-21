<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Data_graph extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('Mdl_master_data','ma');
                $this->load->model('Mdl_report_test','mf');
                $this->load->model('Mdl_data_graph','md');
	}
	public function index()
	{           
		
	}
        
        function get_data_result_test_by_field($id_formulir=25, $id_reports=3) {
            $id_formulir = $_REQUEST['id_formulir'];
            $id_reports = $_REQUEST['id_reports'];
            $config_graph = $this->md->get_config_graph($id_reports);
           
            
            $type_graph = "Line";
            $data_field = array();
            $i = 0;
            $fieldSeries = "";
            $category = "";
            foreach ($config_graph as $k=>$v) {     
                if ($v['y_field_data'] != "") {
                    $data_field[0][$v['y_field_data']] =  "";
                    $data_field[0][$v['x_categorit_axis']] =  "";
                    $category = $v['x_categorit_axis'];
                }
            }
            
            foreach ($data_field[0] as $k=>$v) {
                    $yf = $this->md->get_data_result_by_field($id_formulir, $id_reports, $k);
                    $i = 0;
                    foreach($yf as $v1) {
                        if ($v1 != "") {
                            $data_field[$i][$k] = ($category != $k) ? ($v1*1) : $v1;
                            $i++;
                        }
                    }
            }
            //unset($data_field[0]['']);
            
            echo json_encode(array("data"=>$data_field)); die;
            //$yf = $this->md->get_data_result_by_field($id_formulir, $id_reports, $Xcatogories);
            
            //print_r($data_field); 
            
            $data_field = array();
            $data_field[] = array("ML"=>20,"TC50"=>"A","MH"=>50);
            $data_field[] = array("ML"=>120,"TC50"=>"B","MH"=>50);
            $data_field[] = array("ML"=>89,"TC50"=>"C","MH"=>90);
            $data_field[] = array("ML"=>109,"TC50"=>"D","MH"=>90);
            //print_r($data_field);
            //die;
            $data['graph_data'] = json_encode($data_field);
            $data['series'] = $data_series;
            $data['type_graph'] = $type_graph;
            echo json_encode(array("data"=>$data_field));
        }
        
        function get_data_avarage_field_Y() {
            $data = $this->md->get_data_result_by_field(25,2,"MH");
            print_r($data);
        }
        
        function get_data_avarage_field_X() {
            
        }
	
}
