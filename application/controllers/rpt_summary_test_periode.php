<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('blackbox.php');
class Rpt_summary_test_periode extends Blackbox {
	var $access_right;
	function __construct()
	{
		parent::__construct();
		$this->check();		
		$this->load->model('mdl_rpt_summary_test','cm');
	}
	public function index()
	{           
        
		$data['role'] = $this->get_role_leve_user("rpt_summary_test");
		$data['v_list'] = $this->cm->list_formulir();
		
		$this->load->view('rpt_summary_test/list_periode', $data);
	}
	
	public function print_report_formulir(){
		$data['role'] = $this->get_role_leve_user("rpt_summary_test_periode");	
		$sample = $_GET['sample'];
		$datefrom = $_GET['datefrom'];
		$dateto = $_GET['dateto'];
		$mesin_test = $_GET['mesin_report_test'];
		
		$list_data = $this->cm->get_formulir_periode_by_sample($sample, $datefrom, $dateto);
		#print_r($list_data);
		$data['empty'] = (empty($list_data) ? false : true);
		$page_header = $this->load->view('rpt_summary_test/header_print', $data,true);
		$page = array();
		$items = array(); 
		
		echo  $page_header;
		
		
		foreach ($list_data as $v) {
			$data['v_lst'] = $this->cm->list_formulir_periode($v['no_req'], $mesin_test);
			$__rv = $data['v_lst']->result();
			#print_r($__rv);
			if(empty($__rv))
				continue;
			$page[] = $this->load->view('rpt_summary_test/rpt_summary_test_periode', $data, true);
			$items['sample'][] = $v['sample'];
			$items['no_req'][] = $v['no_req'];
			$items['date_request'][] = $v['date_request'];
		}
		
		
		echo "<table border='0'>";
		echo "<tr>";
		
		$ix = 0;
		foreach ($page as $x) {
			echo "<td valign='top' align='center' width='200px'>";
			echo "<b>".$items['no_req'][$ix]."<br>".$items['date_request'][$ix]."</b>";
			echo $x;
			
			echo "</td>";
			$ix++;
		}
		
		
		//var_dump($page);
		//echo $page[0];
		echo "</tr><table>";
		echo '</body>
			  </html>';
	}
	
	function get_formulir_test_is_approved() {
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
		$this->cm->get_formulir_test_is_approved($fields, "labor_formulir_request_test", $this->where, $this->sortProperty, $this->sortDirection, $this->start, $this->count);
		
	}
	
}
