<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemol_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query
	function get_datatables($where)
	{
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'where'		=> $where
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Pemol Dev'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/summary_api_new/getDataTable';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);

		$data = $dataDecode->data;
		return $data;
	}

	// count datatable query
	function count_filtered($where)
	{
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'where'		=> $where
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Pemol Dev'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/summary_api_new/countDataTable';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// get pemol
	function get_pemol($var, $sales, $from)
	// function get_pemol($var, $sales)
	{
		$query = $this->db->query("SELECT COUNT(1) as total, COUNT(DISTINCT(Sales_Code)) as dsr_input,SUM(IF(Source = 'BCA Mobile',1,0)) as bcaM,SUM(IF(Source = 'MyBCA',1,0)) as mBca
			FROM internal.data_summary_incoming_pemol
			WHERE $var = '$sales'
			AND Group_Date = '$from'
		");
		return $query;
		$query->free_result();
	}

	// get pemol dummy
	// function get_pemol_dummy($var, $sales, $from, $to, $part)
	function get_pemol_dummy($sales, $from, $part)
	{
		if ($part == 'rsm') {
			$query = $this->db->query("SELECT count(1) as total, COUNT(DISTINCT(Sales_Code)) as dsr_input,SUM(IF(Source = 'BCA Mobile',1,0)) as bcaM,SUM(IF(Source = 'MyBCA',1,0)) as mBca
						FROM internal.data_summary_incoming_pemol 
						WHERE RSM_Code = '0'
						AND BSH_Code = '$sales'
						AND Group_Date = '$from'
					");
		} else if ($part == 'asm') {
			$query = $this->db->query("SELECT count(1) as total, 
			COUNT(DISTINCT(Sales_Code)) as dsr_input,SUM(IF(Source = 'BCA Mobile',1,0)) as bcaM,SUM(IF(Source = 'MyBCA',1,0)) as mBca
			FROM internal.data_summary_incoming_pemol
						WHERE ASM_Code = '0'
						AND RSM_Code = '$sales'
						AND Group_Date = '$from'
					");
		} else if ($part == 'spv') {
			$query = $this->db->query(
				"SELECT count(1) as total, 
						COUNT(DISTINCT(Sales_Code)) as dsr_input,SUM(IF(Source = 'BCA Mobile',1,0)) as bcaM,SUM(IF(Source = 'MyBCA',1,0)) as mBca
						FROM internal.data_summary_incoming_pemol
						WHERE SPV_Code = '0'
						AND ASM_Code = '$sales'
						AND Group_Date = '$from'"
			);
		}
		return $query;
		$query->free_result();
	}

	function get_pemol_export($var, $sales, $from)
	// function get_pemol($var, $sales)
	{
		$query = $this->db->query("SELECT * FROM internal.data_summary_incoming_pemol
		WHERE $var = '$sales'
		AND Group_Date = '$from'
	");
		return $query;
		$query->free_result();
	}

	// //get SPV BY ASM
	// function get_by_asm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales`');
	// 	$this->db->where('SM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// //get SPV BY RSM
	// function get_by_rsm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales_structure`');
	// 	$this->db->where('RSM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// API AREA

	// function get_pemol($var,$sales,$from,$to)
	// {
	// 	$getData = $this->db->query("SELECT count(1) as total
	// 									FROM data_pemol t1
	// 									INNER JOIN internal.data_sales_copy t2
	// 									ON t1.Sales_Code = t2.DSR_Code
	// 									WHERE $var = '$sales'
	// 									AND t1.Input_Date >= '$from'
	// 									AND t1.Input_Date <= '$to'
	// 								");
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol_dummy_rsm($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.RSM_Code = '0'
	// 								 AND t2.BSH_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol_dummy_asm($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.ASM_Code = '0'
	// 								 AND t2.RSM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol_dummy_spv($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '0'
	// 								 AND t2.ASM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol2($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.ASM_Code = '0'
	// 								 AND t2.RSM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol3($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '0'
	// 								 AND t2.ASM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_pemol_spv($spv,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total 
	// 	                             FROM data_pemol t1
	// 								 LEFT JOIN internal.data_sales_copy t2 
	// 								 ON t1.Sales_Code=t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '$spv'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }


	// get datatable query part 2
	private function _get_dataTable_query($where)
	{
		$column_order = array(null, 'DSR_Code', 'Name', 'Branch', 'Position'); //field yang ada di table recruitment
		$column_search = array('DSR_Code', 'Name', 'Branch', 'Position'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('internal.data_sales_copy');
		// $this->db->where('Status', 'ACTIVE');
		$this->db->where("(CASE WHEN Position = 'DSR' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
								WHEN Position = 'SPG' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
								WHEN Position = 'SPB' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
								ELSE Status = 'ACTIVE'
								END)");
		if ($where) {
			$this->db->where($where);
		}

		$i = 0;
		foreach ($column_search as $item) // looping awal
		{
			if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
			{

				if ($i === 0) // looping awal
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}
		$this->db->order_by('Position', 'DESC');

		// if(isset($_POST['order'])) 
		// {
		// 	$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } 
		// else if(isset($order))
		// {
		// 	$this->db->order_by(key($order), $order[key($order)]);
		// }
	}

	// get datatable query part 1
	function get_dataTable($where)
	{
		$this->_get_dataTable_query($where);
		if ($_POST['length'] != -1)
			$this->db->limit($this->input->post('length'), $this->input->post('start'));
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	// count datatable query
	function count_dataTable($where)
	{
		$this->_get_dataTable_query($where);
		$query = $this->db->get();
		return $query->num_rows();
	}
}
