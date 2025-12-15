<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cc_model_new extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query
	// private function _get_datatables($url, $where)
	// {
	// 	$data = array(
	// 		'length' 	=> $this->input->post('length'),
	// 		'start' 	=> $this->input->post('start'),
	// 		'search' 	=> $this->input->post('search')['value'],
	// 		'where'		=> $where
	// 	);

	// 	$rowAPI = $this->get_api();

	// 	$apiKey = $rowAPI->api_key;

	// 	// API auth credentials
	// 	$apiUser = $rowAPI->Username;
	// 	$apiPass = $rowAPI->Password;

	// 	// Create a new cURL resource
	// 	$ch = curl_init($url);

	// 	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
	// 	curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
	// 	curl_setopt($ch, CURLOPT_POST, 1);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	// 	$result = curl_exec($ch);

	// 	// Close cURL resource
	// 	curl_close($ch);

	// 	$data = json_decode($result);
	// 	return $data;
	// }

	// // get datatable query
	// function get_datatables($where)
	// {
	// 	$rowAPI = $this->get_api();

	// 	// API URL
	// 	$url = $rowAPI->url . 'api/incoming_cc_new/getDataTable';
	// 	// echo $url; die;

	// 	$query = $this->_get_datatables($url, $where);

	// 	$data = $query->data;
	// 	return $data;
	// }

	// // count datatable query
	// function count_filtered($where)
	// {
	// 	$rowAPI = $this->get_api();

	// 	// API URL
	// 	$url = $rowAPI->url . 'api/incoming_cc_new/countDataTable';

	// 	$query = $this->_get_datatables($url, $where);

	// 	$data = $query->data;
	// 	return $data;
	// }

	// ===============================================================================================================================================================
	// API LEADER

	// get data input leader CCMS
	// function getDataInputLeader($var, $sales, $from, $to, $type)
	// {
	// 	$rowAPI = $this->get_api();

	// 	// API URL

	// 	$url = $rowAPI->url . 'api/incoming_cc_new/getDataInputLeader?lead_code=' . $var . '&sales=' . $sales . '&from=' . $from . '&to=' . $to . '&type=' . $type;
	// 	// echo($url); die();
	// 	// https://dev.ptdika.com/rest-api/api/incoming_cc_new/getDataInputLeader?lead_code=t3.SPV_Code&sales=F3400045&from=2024-02-01&to=2024-02-13&type=reg

	// 	$query = $this->get_incoming($url);

	// 	// var_dump($query->data);
	// 	// die;
	// 	$data = $query->data;
	// 	return $data;
	// }



	function getDataExportCcms($var, $sales, $from, $to)
	{
		$rowAPI = $this->get_api();

		// API URL

		$url = $rowAPI->url . 'api/incoming_cc_new/getDataExportCcms?lead_code=' . $var . '&sales=' . $sales . '&from=' . $from . '&to=' . $to;
		// echo($url); die();
		// https://dev.ptdika.com/rest-api/api/incoming_cc_new/getDataInputLeader?lead_code=t3.SPV_Code&sales=F3400045&from=2024-02-01&to=2024-02-13&type=reg

		$query = $this->get_incoming($url);

		// var_dump($query->data);
		// die;
		$data = $query->data;
		return $data;
	}

	// get data input leader CCMS
	function getDataInputLeaderTmcc($var, $sales, $from, $type)
	{
		$query = $this->db->query("SELECT
					(SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position IN ('DSR','SPG','SPB') AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$sales') AS total_dsr,
					(SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position = 'Mobile Sales' AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$sales') AS total_ms,
					(SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position = 'RO' AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$sales') AS total_ro,
	
					COUNT(1) AS total
					FROM internal.data_summary_incoming_cc t1
					LEFT JOIN internal.data_sales_copy t3 ON t3.DSR_Code = t1.Sales_Code
					WHERE $var = '$sales' AND t1.Group_Date = '$from'
					
					");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function getDataInputDummyTmcc($nik, $from, $to, $position, $type)
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/incoming_cc_new/getDataInputDummyTmcc?nik=' . $nik . '&from=' . $from . '&to=' . $to . '&position=' . $position . '&type=' . $type;
		// echo $url; die;

		$query = $this->get_incoming($url);
		// var_dump($query);
		// die;
		$data = $query->data;
		return $data;
	}

	function getDataExportTmcc($var, $sales, $from, $to)
	{
		$rowAPI = $this->get_api();

		// API URL

		$url = $rowAPI->url . 'api/incoming_cc_new/getDataExportTmcc?lead_code=' . $var . '&sales=' . $sales . '&from=' . $from . '&to=' . $to;
		// echo($url); die();
		// https://dev.ptdika.com/rest-api/api/incoming_cc_new/getDataInputLeader?lead_code=t3.SPV_Code&sales=F3400045&from=2024-02-01&to=2024-02-13&type=reg

		$query = $this->get_incoming($url);

		// var_dump($query->data);
		// die;
		$data = $query->data;
		return $data;
	}

	///////////////////////////


	function getDataInputMSLeader($var, $sales, $from, $to)
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/incoming_cc_new/getDataInputMSLeader?lead_code=' . $var . '&sales=' . $sales . '&from=' . $from . '&to=' . $to;
		// cekvar($url);

		$query = $this->get_incoming($url);

		$data = $query->data;
		return $data;
	}



	function getDataInputMSDummy($nik, $from, $to, $position, $type)
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/incoming_cc_new/getDataInputMSDummy?nik=' . $nik . '&from=' . $from . '&to=' . $to . '&position=' . $position . '&type=' . $type;

		$query = $this->get_incoming($url);

		$data = $query->data;
		return $data;
	}

	// ===============================================================================================================================================================

	// ===============================================================================================================================================================
	// API DSR

	public function unit_test()
	{
		$this->db->select('*');
		$this->db->from('internal.applications');

		$query = $this->db->get();
		return $query->result();
	}

	// NEW


	private function _get_dataTable_query($where)
	{
		$column_order = array(null, 'DSR_Code', 'Name', 'Branch'); //field yang ada di table recruitment
		$column_search = array('DSR_Code', 'Name', 'Branch'); //field yang diizin untuk pencarian 
		$order = array('Branch' => 'ASC', 'Name' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('`internal`.`data_sales_copy`');
		$this->db->where("
			(CASE WHEN Position = 'SPV' THEN Status = 'ACTIVE'
			WHEN Position = 'DSR' THEN Status = 'ACTIVE'
			WHEN Position = 'SPG' THEN Status = 'ACTIVE'
			WHEN Position = 'SPB' THEN Status = 'ACTIVE'
			WHEN Position = 'Mobile Sales' THEN Status = 'ACTIVE'
			ELSE Status = 'ACTIVE'
			END)
		");
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
	}


	function get_dataTable($where)
	{
		$this->_get_dataTable_query($where);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}


	function count_dataTable($where)
	{
		$this->_get_dataTable_query($where);
		$query = $this->db->get();
		return $query->num_rows();
	}

	// CCMS

	function getDataInputLeader($var, $sales, $from, $type)
	{
		$query = $this->db->query("SELECT
				(SELECT COUNT(DISTINCT(Sales_Code)) FROM internal.data_summary_incoming_ccms WHERE $var = '$sales') AS total_ms,

				COUNT(1) AS total
				FROM internal.data_summary_incoming_ccms
				WHERE $var = '$sales' AND Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function getDataInputDummy($nik, $from, $position, $type = '')
	{
		if ($position == 'rsm') {
			$var = 'RSM_Code';
			$var2 = 't1.RSM_Code';
			$where1 = "t3.BSH_Code = '$nik' AND t3.RSM_Code = '0'";
		} else if ($position == 'asm') {
			$var = 'ASM_Code';
			$var2 = 't1.ASM_Code';

			$where1 = "t3.RSM_Code = '$nik' AND t3.ASM_Code = '0'";
		} else if ($position == 'spv') {
			$var = 'SPV_Code';
			$var2 = 't1.SPV_Code';
			$where1 = "t3.ASM_Code = '$nik' AND t3.SPV_Code = '0'";
		}
		// (SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position IN ('DSR','SPG','SPB') AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$nik') AS total_dsr,
		$query = $this->db->query("SELECT
					
					(SELECT COUNT(1) FROM internal.data_summary_incoming_ccms WHERE SPV_Code = '0' AND $var = '$nik') AS total_ms,
					COUNT(1) AS total
					FROM internal.data_summary_incoming_ccms
					WHERE $var = '$nik' AND Group_Date = '$from'
				");

		return $query;
	}



	function get_dataExportCcms($lead_code, $sales, $from)
	{
		$query = $this->db->query("SELECT * FROM internal.data_summary_incoming_ccms 
			WHERE $lead_code = '$sales' AND Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function detBreakdownInputLeader($lead_code, $sales, $from)
	{
		$query = $this->db->query("SELECT * FROM internal.data_summary_incoming_ccms
				
				WHERE $lead_code = '$sales' AND Group_Date = '$from'
				
				");

		return $query;
		$query->free_result();
	}


	// TMCC

	function get_dataInputLeaderTmcc($lead_code, $sales, $from, $type = '')
	{
		$query = $this->db->query("SELECT
		(SELECT COUNT(DISTINCT(Sales_Code)) FROM internal.data_summary_incoming_cc WHERE $lead_code = '$sales') AS total_ms,
					COUNT(1) AS total
					FROM internal.data_summary_incoming_cc 
					WHERE $lead_code = '$sales' AND Group_Date = '$from'
					
					");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function get_dataInputDummyTmcc($nik, $from, $position, $type = '')
	{
		if ($position == 'rsm') {
			$var = 'RSM_Code';
			$where1 = "t3.BSH_Code = '$nik' AND t3.RSM_Code = '0'";
		} else if ($position == 'asm') {
			$var = 'ASM_Code';
			$where1 = "t3.RSM_Code = '$nik' AND t3.ASM_Code = '0'";
		} else if ($position == 'spv') {
			$var = 'SPV_Code';
			$where1 = "t3.ASM_Code = '$nik' AND t3.SPV_Code = '0'";
		}

		// (SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position IN ('DSR','SPG','SPB') AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$nik') AS total_dsr,
		$query = $this->db->query("SELECT
					(SELECT COUNT(1) FROM internal.data_summary_incoming_cc WHERE SPV_Code = '0' AND $var = '$nik') AS total_ms,

					COUNT(1) AS total
					FROM internal.data_summary_incoming_cc
					WHERE $var = '$nik' AND Group_Date = '$from'
				");

		return $query;
	}

	function get_dataDetailTmcc($lead_code, $sales, $from)
	{
		$query = $this->db->query("SELECT * FROM internal.data_summary_incoming_cc
		WHERE $lead_code = '$sales' AND Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function get_dataExportTmcc($lead_code, $sales, $from)
	{
		// $query = $this->db->query("SELECT * FROM internal.data_summary_incoming_cc WHERE $lead_code = '$sales' AND Created_Date >= '$from 00:00:00' AND Created_Date <= '$to 23:59:59'");
		$query = $this->db->query("SELECT t1.*,t2.Name,t2.SPV_Name,t2.ASM_Name,t2.RSM_Name,t2.BSH_Name FROM internal.data_summary_incoming_cc t1 LEFT JOIN internal.data_sales_copy t2 on t2.DSR_Code = t1.Sales_Code
		WHERE t1.$lead_code = '$sales' AND t1.Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	// TMSC


	function get_dataInputLeaderTmsc($lead_code, $sales, $from, $type = '')
	{
		$query = $this->db->query("SELECT
					
					(SELECT COUNT(DISTINCT(Sales_Code)) FROM internal.data_summary_incoming_tmsc WHERE $lead_code = '$sales') AS total_ms,
					
	
					COUNT(1) AS total
					FROM internal.data_summary_incoming_tmsc
					WHERE $lead_code = '$sales' AND Group_Date = '$from'
					
					");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function get_dataInputDummyTmsc($nik, $from, $position, $type = '')
	{
		if ($position == 'rsm') {
			$var = 'RSM_Code';
			$var2 = 't1.RSM_Code';
			$where1 = "t3.BSH_Code = '$nik' AND t3.RSM_Code = '0'";
		} else if ($position == 'asm') {
			$var = 'ASM_Code';
			$var2 = 't1.ASM_Code';
			$where1 = "t3.RSM_Code = '$nik' AND t3.ASM_Code = '0'";
		} else if ($position == 'spv') {
			$var = 'SPV_Code';
			$var2 = 't1.SPV_Code';
			$where1 = "t3.ASM_Code = '$nik' AND t3.SPV_Code = '0'";
		}

		// (SELECT COUNT(1) FROM internal.data_sales_copy t3 WHERE Position IN ('DSR','SPG','SPB') AND Product = 'CC' AND Status = 'ACTIVE' AND $var = '$nik') AS total_dsr,
		$query = $this->db->query("SELECT
					
					(SELECT COUNT(DISTINCT(Sales_Code)) FROM internal.data_summary_incoming_tmsc WHERE $var = '$nik') AS total_ms,
					
					
				
					COUNT(1) AS total
					FROM internal.data_summary_incoming_tmsc
					WHERE $var = '$nik' AND Group_Date = '$from'
				");

		return $query;
	}

	function get_dataDetailTmsc($lead_code, $sales, $from)
	{
		$query = $this->db->query("SELECT * FROM internal.data_summary_incoming_tmsc
		WHERE $lead_code = '$sales' AND Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}

	function get_dataExportTmsc($lead_code, $sales, $from)
	{
		// $query = $this->db->query("SELECT * FROM internal.data_summary_incoming_cc WHERE $lead_code = '$sales' AND Created_Date >= '$from 00:00:00' AND Created_Date <= '$to 23:59:59'");
		// $query = $this->db->query("SELECT * FROM internal.data_summary_incoming_tmsc t1 
		// LEFT JOIN internal.data_sales_copy t3 ON t3.DSR_Code = t1.Sales_Code
		// WHERE $lead_code = '$sales' AND t1.Group_Date = '$from'");

		$query = $this->db->query("SELECT t1.*,t2.Name,t2.SPV_Name,t2.ASM_Name,t2.RSM_Name,t2.BSH_Name FROM internal.data_summary_incoming_tmsc t1 LEFT JOIN internal.data_sales_copy t2 on t2.DSR_Code = t1.Sales_Code
WHERE t1.$lead_code = '$sales' AND t1.Group_Date = '$from'");


		// return $this->db->last_query($query);
		return $query;
		$query->free_result();
	}
}
