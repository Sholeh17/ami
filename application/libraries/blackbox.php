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

	public function index()
	{
		//echo 'Hello World!';
	}

	protected function prep()
	{

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
	
	
}

/* End of file grid.php */
/* Location: ./application/controllers/grid.php */