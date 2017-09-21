<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Blackbox extends CI_Controller {

	protected $defaultSortProperty = '';
	protected $defaultSortDirection = 'asc';
	protected $defaultLimit = 50;

	protected $start = 0;
	protected $count = 0;
	protected $filters = null;
	protected $sortProperty = '';
	protected $sortDirection = '';
	protected $where = '';
	
	function connectMSSQL(){
		$host = "10.255.238.20";
		$user = "trecs";
		$pass = "trecs";
		$dn_name = "MASA_DEV";
		
		$handle = mssql_connect($host, $user, $pass) or die("Cannot connect to server");
		
		$db = mssql_select_db($dn_name, $handle) or die("Cannot select database"); 
	
	}
	
	function getDetailNoRIR($noRIR){
		$query = "exec zspCustGetDetailsVendor '$noRIR','','',''";
		$result = mssql_query($query);

		$rv = array();
		while($row = mssql_fetch_array($result)) {
			$rv[] = $row;
		}	
		
		return $rv;
	}	
	
		function check(){
                $this->load->library("session");
                $this->load->helper('url');
				if($this->session->userdata('logged_in') != true){
					redirect('./auth');
				}
		
		}
        function cek_user() {
                $this->load->database();
                $this->load->library("session");
                $this->user	=	$_POST['loginUsername'];
                $this->pass	=	md5($_POST['loginPassword']);
                $data 	=	array();
                $sql 	= 	"SELECT q.username, q.`area`, q.`distribusi`, q.`level`, q.`rayon` FROM sys_login q WHERE q.`active` = 1 AND q.`username` = ? AND q.`password` = ? ";
                $query 	= 	$this->db->query($sql, array($this->user, $this->pass));

                if($query->num_rows() > 0){
                        $data = $query->row();
						$sql_act = "INSERT INTO sys_activities (username, activity, tgl) VALUES ('".$this->user."', 'LOGIN', NOW())";
						$this->db->query($sql_act);
                        // Register Session
                        $this->set_register_session($data);
                } else {
						$data_login = array(
											'logged_in'  		=> false
						);
						$this->session->set_userdata($data_login);
                }

                $query->free_result();
                return $data;
        }

		function changePasswordx() {
			$this->load->database();
			$this->load->library("session");						
			$q1 = "UPDATE sys_login SET password = '".$_POST['password-inputEl']."' WHERE username = '".$this->session->userdata('username')."'";
			print_r($_POST);
			$query2 	= 	$this->db->query($q1);
			$result_arr = array("success" => true, "msg" => "Change Success!!");
			echo json_encode($result_arr);
			die;			
		}		

    function set_register_session($data) {    
				
                $new_session = array(
                       'username'  			=> $data->username,                                              
                       'date_created' 		=> $data->date_created,
					   'level'				=> $data->level,
                       'area' 				=> $data->area,
					   'distribusi' 		=> $data->distribusi,
					   'rayon'				=> $data->rayon,
					   'logged_in'			=> true
                );
                $this->session->set_userdata($new_session);
        }

	function check_out(){
		$this->load->library("session");
		$array_items = array(
				'user_id'  	=> '',
                'group_user' => '',
               	'nama_user' 	=> '',	
				'nik'			=> '',	
                'level'          => '',
				'user_section' => '',
				'user_head'		=> '',
				'id_employee'	=> '',
			    'id_atasan_1'	=> '',
			    'id_atasan_2'	=> '',
			    'id_atasan_3'	=> '',					  
				'amount_approve' => 0,
                'last_login' => '',
                'logged_in' => false,
				'owner' =>  ''
		);
		$this->session->unset_userdata($array_items);
	}	
	function get_access_right($controller) {		
		$this->load->database();
		$this->load->library("session");
		$q = "SELECT access_new,access_change,access_delete,access_approve,
					access_print, access_download, access_upload,access_sync, access_search, access_verify
				  FROM pms_rule_access_menu 
				WHERE controller = '".$controller."' AND  user_id = '".$this->session->userdata('user_id')."'";
		$query 	= 	$this->db->query($q);
		$menuArr = array();
		$v2 = array();
		$data_arr = $query->result_array();
		return $data_arr;
	}	
	
	function get_role_leve_user($fiture) {
		$this->load->database();
		$this->load->library("session");
		$this->db->from('user_role_access');
		$this->db->where('name_menu', $fiture);
		$this->db->where("level",$this->session->userdata('level'));
		$query = $this->db->get();			
		if($query->num_rows() > 0)
			return $query->row();
		return false;
	}
	
	
	protected function prep(){

		// collect request parameters
		$this->start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
		$this->count  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : $this->defaultLimit;
		//$sort   = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : null;
		$this->filters = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : null;

		if (!isset($_REQUEST['sort'])) {
			$this->sortProperty = $this->defaultSortProperty;
			$this->sortDirection = $this->defaultSortDirection;
		} else {
			$sort = $_REQUEST['sort'];
			$sort = str_replace('\"', '', $sort); //hack, beda antara di mac dan windows
			$sort = str_replace('"', '', $sort);
			$sort = explode(',direction:', $sort);
			$sort[0] = explode(':', $sort[0]);
			$sort[0] = $sort[0][1];
			$sort[1] = explode('}]', $sort[1]);
			$sort[1] = $sort[1][0];
			$this->sortProperty = $sort[0];
			$this->sortDirection = $sort[1];
		}

		// GridFilters sends filters as an Array if not json encoded
		if (is_array($this->filters)) {
		    $encoded = false;
		} else {
		    $encoded = true;
		    $this->filters = json_decode($this->filters);
		}

		$this->where = ' 0 = 0 ';
		$qs = '';

		// loop through filters sent by client
		if (is_array($this->filters)) {
		    for ($i=0;$i<count($this->filters);$i++){
		        $filter = $this->filters[$i];

		        // assign filter data (location depends if encoded or not)
		        if ($encoded) {
		            $field = $filter->field;
		            $value = $filter->value;
		            $compare = isset($filter->comparison) ? $filter->comparison : null;
		            $filterType = $filter->type;
		        } else {
		            $field = $filter['field'];
		            $value = $filter['data']['value'];
		            $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
		            $filterType = $filter['data']['type'];
		        }

		        switch($filterType){
		            case 'string' :
		            	if ($value == '<blank>') {
		            		$qs .= " AND ".$field." IS NULL";
		            	} else {
		            		$qs .= " AND ".$field." LIKE '%".$value."%'";
		            	}
		            	Break;
		            case 'list' :
		                if (strstr($value,',')){
		                    $fi = explode(',',$value);
		                    for ($q=0;$q<count($fi);$q++){
		                        $fi[$q] = "'".$fi[$q]."'";
		                    }
		                    $value = implode(',',$fi);
		                    $qs .= " AND ".$field." IN (".$value.")";
		                }else{
		                    $qs .= " AND ".$field." = '".$value."'";
		                }
		            Break;
		            case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
		            case 'numeric' :
		                switch ($compare) {
		                    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
		                    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
		                    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
		                }
		            Break;
		            case 'date' :
		                switch ($compare) {
		                    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
		                    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
		                    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
		                }
		            Break;
		        }
		    }
		    $this->where .= $qs;
		}
	}
	
	function get_interval_date_x($count_day_interval, $date, $unit="day") {
		$datetime = new DateTime($date);
		$datetime->modify($count_day_interval." ". $unit);
		return $datetime->format('Y-m-d');
	}	
	
	function convertTimeToSeconds($time) {
		// Check the duration input of time
		if (!preg_match("/^\d+(:\d{1,2})?(:\d{1,2})?$/", $time)) {
			throw new exception('Invalid input format for duration.');
		}
		// Retrieve hours, minutes, and seconds
		$hours = substr($time, 0, -6);
		$minutes = substr($time, -5, 2);
		$seconds = substr($time, -2);
		$total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
		// Ensure that the result is an integer
		if (!is_int($total_seconds)) {
			throw new Exception('Output format is not integer.');
		} else {
			return $total_seconds;
		}
	}
	
	function convertIntToTime($int_time) {
		return gmdate('H:i:s', $int_time);
	}
	
	function addDateInterval($date, $interval_day = "+ 1") {		
		return date('Y-m-d', strtotime($date . " $interval_day day"));
	}
	
	function create_zip($files = array(),$file_folder = '') {		
		extension_loaded('zip');
		$zip = new ZipArchive(); // Load zip library 
		$zip_name = time().".zip"; // Zip name
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
		{ 
		 // Opening zip file to load files
			return  "* Sorry ZIP creation failed at this time";
		}
		foreach($files as $file)
		{ 
			$zip->addFile($file_folder.$file); // Adding files into zip						
		}
		$zip->close();		
		if(file_exists($zip_name))
		{
			// push to download the zip
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$zip_name.'"');
			readfile($zip_name);
			// remove zip file is exists in temp path
			unlink($zip_name);
		}
	}
	
	function generateNumberPO($cust_acc, $cust_type) {
			$this->load->database();
			$cust_type = $cust_type;  // (Export & Local)          
            $code = $cust_acc; // Customer Code
            $number_romawi = $this->formatCharRomawi((date("m")*1));             
			$this->db->select_max('counter', 'counter');
            $this->db->where('ref_doc', $code);
            $query = $this->db->get('counter');
            if($query->num_rows() > 0){
                    $row = $query->row();
                    $data = $row->counter + 1;
            }else{
                    $data = 1;
            }
            if ($this->getAvailableNoTrX($code,$data))
                    $data++;

            $data2 = array(
                    'id_counter'=>null,
                    'year'=>date('Y'),                    
					'month'=>date('m'),
                    'ref_doc'=>$code,
                    'counter'=>$data
            );
			
            $this->db->insert('counter', $data2);            
            $year		= date('ym');
			
            $counter	= $data;
            $trxno 		= $code."/".$counter."/".$number_romawi."/".date("Y");
            return $trxno;
	}
	
	function getAvailableNoTrX($code, $counter_generate) {
			$this->load->database();
			$this->db->select('counter');			
			$this->db->where('counter', $counter_generate);
			$this->db->where('ref_doc', $code);			
			$this->db->limit(1);
			$query = $this->db->get('counter');
			if($query->num_rows() > 0)
					return true;
			return false;
	}
	
	function formatCharRomawi($number=0) {
            $arr_romawi = array("0","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII","XIII");
            return $arr_romawi[$number];
	}
	
/*
* Excel library for Code Igniter applications
* Author: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
*/
	function to_excel($fields, $query_result, $title, $filename='exceloutput')
	{
			 $headers = ''; // just creating the var for field headers to append to below
			 $data = ''; // just creating the var for field data to append to below

			 $obj =& get_instance();

			 
			 if (!$query_result) {
				  echo '<p>Data tidak ditemukan.</p>';
			 } else {
				  
				  foreach ($fields as $field) {					
					 $headers .= $field . "\t";
				  }

				  foreach ($query_result as $row) {
						
					   $line = '';
					   foreach($row as $value) {                                            
							
							if ((!isset($value)) OR ($value == "")) {
								 $value = "\t";
							} else {
								 $value = str_replace('"', '""', $value);
								 $value = '"' . $value . '"' . "\t";
							}
							$line .= $value;
					   }
					   $data .= trim($line)."\n";
				  }
				  
				  $data = str_replace("\r","",$data);
				  
				  header("Content-type: application/x-msdownload");
				  header("Content-Disposition: attachment; filename=$filename.xls");
				  echo $title."\t \n";
				  echo "$headers\n$data";  
			 }
		}
		
		
		
		//For Report 
		
		function getIndexResultForumlaGebo($data, $c=30) {
					$index = 0;
					$arr_temp_1 = $data;
					switch($c) {
						case 30;
							$array_30 = array();
							$array_smaller30 = array();
							$index_get30 = "";
							$k30 =  0;
							$k30small = 0;
							if (isset($arr_temp_1['T'])) {
								foreach ($arr_temp_1['T'] as $k=>$v) {
									if ($v < 31 & $v >= 30) {
										$array_30[$k30]['v_T30'] = $v;
										$array_30[$k30]['i_T30'] = $k;
										$k30++;
									}
									
									if ($v < 30) {
										$array_smaller30[$k30small]['v_T30'] = $v;
										$array_smaller30[$k30small]['i_T30'] = $k;
										$k30small++;
									}
									
									if ($v == 30) {
										$index_get30 = $k;
									}
										
								}
							}
							//get 30 > && 30.9 , Priority yang diambil jika tidak ditemukan nilai 30
							if ($index_get30 == "") {
								if(!empty($array_30)) {
									$smaller30X = 30.9;
									foreach($array_30  as $k1=>$v1) {
										if ($v1['v_T30'] < $smaller30X) {
											$smaller30X = $v1['v_T30'];
											$indexsmaller30X = $v1['i_T30'];
										}
									}
									$index_get30 = $indexsmaller30X;
								}
							}
							
							
							// Get Max < 30
							if ($index_get30 == "") {
								if(!empty($array_smaller30)) {
									$max30X = 0;
									//print_r($array_smaller30);
									foreach($array_smaller30  as $k2=>$v2) {
										
										if ($v2['v_T30'] > $max30X) {
											//$ = $v1['v_T30'];
											$indexdown30 = $v2['i_T30'];
										}
									}
									$index_get30 = $indexdown30;
								}
							}
							$index = $index_get30;
						break;
						case 60:
							
							$array_60 = array();
							$array_smaller60 = array();
							$index_get60 = "";
							$k60 =  0;
							$k60small = 0;
							if (isset($arr_temp_1['T'])) {
								foreach ($arr_temp_1['T'] as $k=>$v) {
									if ($v < 61 & $v >= 60) {
										$array_60[$k60]['v_T60'] = $v;
										$array_60[$k60]['i_T60'] = $k;
										$k60++;
									}
									
									if ($v < 60) {
										$array_smaller60[$k60small]['v_T60'] = $v;
										$array_smaller60[$k60small]['i_T60'] = $k;
										$k60small++;
									}
									
									if ($v == 60) {
										$index_get60 = $k;
									}
										
								}
							}
							//print_r($array_60);
							//get 60 > && 60.9 , Priority yang diambil jika tidak ditemukan nilai 30
							if ($index_get60 == "") {
								if(!empty($array_60)) {
									$smaller60X = 60.9;
									foreach($array_60  as $k1=>$v1) {
										if ($v1['v_T60'] < $smaller60X) {
											$smaller60X = $v1['v_T60'];
											$indexsmaller60X = $v1['i_T60'];
										}
									}
									$index_get60 = $indexsmaller60X;
								}
							}
							
							
							// Get Max < 60
							if ($index_get60 == "") {
								if(!empty($array_smaller60)) {
									$max60X = 0;
									//print_r($array_smaller60);
									foreach($array_smaller60  as $k2=>$v2) {
										
										if ($v2['v_T60'] > $max60X) {
											//$ = $v1['v_T30'];
											$indexdown60 = $v2['i_T60'];
										}
									}
									$index_get60 = $indexdown60;
								}
							}
							$index = $index_get60;
							
						break;
					}
					return $index;
			}
		
		

}

/* End of file grid.php */
/* Location: ./application/controllers/grid.php */