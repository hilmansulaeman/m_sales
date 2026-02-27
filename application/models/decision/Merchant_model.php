<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Merchant_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query part 2
	private function _get_dataTable_query($where, $groups, $groupDate)
	{
		$column_order = array(null, 'DSR_Code', 'Name', 'Branch', 'Position'); //field yang ada di table recruitment
		$column_search = array('DSR_Code', 'Name', 'Branch', 'Position'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('`internal`.`edc_result`');
		// $this->db->where("(CASE WHEN Position = 'SPV' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		//                     WHEN Position = 'DSR' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					WHEN Position = 'SPG' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					WHEN Position = 'SPB' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					ELSE Status = 'ACTIVE'
		// 					END)");
		if ($where) {
			$this->db->where($where);
		}
		$this->db->where("Group_Date", $groupDate);
		$this->db->group_by($groups);

		$i = 0;
		foreach ($column_search as $item) // looping awal
		{
			if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
			{
				if ($i === 0) { // looping awal
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i) {
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->order_by('id', 'DESC');

		if (isset($_POST['order'])) {
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	// get datatable query part 1
	function get_datatables($where, $groups, $groupDate)
	{
		$this->_get_dataTable_query($where, $groups, $groupDate);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	// count datatable query
	function count_filtered($where, $groups, $groupDate)
	{
		$this->_get_dataTable_query($where, $groups, $groupDate);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_edc_result($var, $sales, $group_date, $type)
	{
		// $query = $this->db->query("SELECT count(1) as total
		// 							FROM data_upload_oa_pemol_detail
		// 							WHERE $var = '$sales'
		// 							AND Group_date = '$group_date'
		// 						")->row();
		if ($type == "accepted") {
			$query = $this->db->query("SELECT
										SUM(IF(MID_Type = 'New',1,0)) AS new,
										SUM(IF(MID_Type != 'New',1,0)) AS exis,
										COUNT(1) as total
										FROM `internal`.`edc_result` WHERE $var = '$sales'
										AND Status_1 = 'ACCEPTED'
										AND Group_Date = '$group_date'
									")->row();
		} else {
			$query = $this->db->query("SELECT
										COUNT(1) as total
										FROM `internal`.`edc_result` WHERE $var = '$sales'
										AND Status_1 IN ('REJECTED', 'REJECT')
										AND Group_Date = '$group_date'
									")->row();
		}
		return $query;
	}

	function get_summary($var, $sales, $group_date, $product)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		$upPosition = $position . '_Code';

		$query = $this->db->query("SELECT
									SUM(IF(MID_Type = 'NEW',1,0)) AS ntb,
									SUM(IF(MID_Type = 'EXISTING',1,0)) AS exis,
									SUM(IF(MID_Type IN('REJECT','REJECTED'),1,0)) AS rejected,
									SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS kredit,
									SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS non_kredit,
									SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
									SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
									SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
									SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
									COUNT(1) as total
									FROM `internal`.`edc_result` WHERE $var = '$sales'
									AND Product LIKE '$product%'
									AND Group_Date = '$group_date'
									AND $upPosition = '$nik'
								")->row();
		return $query;
		$query->free_result();
	}

	function get_summary_detail($var, $sales, $group_date, $product)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');

		if ($position == 'DSR') {
			$where = "";
		} else {
			$upPosition = $position . '_Code';
			$where = " AND $upPosition = '$nik'";
		}

		$query = $this->db->query("SELECT
									SUM(IF(MID_Type = 'NEW',1,0)) AS ntb,
									SUM(IF(MID_Type = 'EXISTING',1,0)) AS exis,
									SUM(IF(MID_Type IN('REJECT','REJECTED'),1,0)) AS rejected,
									SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS kredit,
									SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS non_kredit,
									SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
									SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
									SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
									SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
									COUNT(1) as total
									FROM `internal`.`edc_result` WHERE $var = '$sales'
									AND Product = '$product'
									AND Group_Date = '$group_date'
									$where
								")->row();
		return $query;
		$query->free_result();
	}

	function get_summary_qriss($var, $sales, $group_date, $type)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');

		if ($position == 'DSR') {
			$upPosition = 'Sales_Code';
		} else {
			$upPosition = $position . '_Code';
		}

		if ($type == "New") {
			$wheres = "MID_Type = 'New'";
		} else {
			$wheres = "MID_Type = 'EXISTING'";
		}

		$query = $this->db->query("SELECT
									SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
									SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
									SUM(IF(Status_1 IN ('REJECT','REJECTED'),1,0)) AS rejected,
									COUNT(1) as total
									FROM `internal`.`edc_result` WHERE $var = '$sales'
									AND Product LIKE 'QRIS'
									AND Group_Date = '$group_date'
									AND $wheres
									AND $upPosition = '$nik'
								")->row();
		return $query;
		$query->free_result();
	}

	function get_summary_qris($var, $sales, $group_date, $type)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');

		if ($position == 'DSR') {
			$upPosition = 'Sales_Code';
		} else {
			$upPosition = $position . '_Code';
		}

		$query = $this->db->query("SELECT
									SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
									SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
									SUM(IF(MID_Type IN ('REJECT','REJECTED'),1,0)) AS rejected,
									COUNT(1) as total
									FROM `internal`.`edc_result` WHERE $var = '$sales'
									AND Product LIKE 'QRIS' 
									AND MID_Type = '$type' 
									AND Group_Date = '$group_date' 
									AND $upPosition = '$nik'
								")->row();
		return $query;
		$query->free_result();
	}

	function get_point_all_new($var, $sales, $group_date, $products)
	{
		$wheres = "$var = '$sales' AND Status_1 = 'ACCEPTED' AND Product = '$products' AND MID_Type = 'New' AND Group_Date = '$group_date'";

		if ($products == 'EDC' || $products == 'EDC_QRIS') {
			$query = $this->db->query("SELECT 
									SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS fk,
									SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS fnk,
									COUNT(1) AS total
									FROM `internal`.`edc_result` 
									WHERE $wheres
								")->row();
			$fk = $query->fk;
			$fnk = $query->fnk;

			$pointFK = $this->get_point('EDC', 'KREDIT');
			$pointFNK = $this->get_point('EDC', 'NON KREDIT');

			$totalPoints = ($fk * $pointFK) + ($fnk * $pointFNK);
		} else if ($products == 'QRIS') {
			$query = $this->db->query("SELECT 
									SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
									SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
									COUNT(1) AS total
									FROM `internal`.`edc_result` 
									WHERE $wheres
								")->row();

			$qrd = $query->qrd;
			$qsd = $query->qsd;
			$plus = $qrd + $qsd;

			$pointQrisNew = $this->get_point('QRIS', 'New');

			$totalPoints = $plus * $pointQrisNew;
		}

		return $totalPoints;
	}

	function get_point_all_exis($var, $sales, $group_date, $products)
	{
		$wheres = "$var = '$sales' AND Status_1 = 'ACCEPTED' AND Product = '$products' AND MID_Type != 'New' AND Group_Date = '$group_date'";

		if ($products == 'EDC' || $products == 'EDC_QRIS') {
			$query = $this->db->query("SELECT 
								SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
								SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
								SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
								SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
								COUNT(1) AS total
								FROM `internal`.`edc_result` 
								WHERE $wheres
							")->row();
			$tc = $query->tc;
			$terminal = $query->terminal;
			$uf = $query->uf;
			$ra = $query->ra;
			$plus = $tc + $terminal + $uf + $ra;

			$pointExisEDC = $this->get_point('EDC', 'Existing');

			$totalPoints = $plus * $pointExisEDC;
		} else if ($products == 'QRIS') {
			$query = $this->db->query("SELECT 
								SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
								SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
								COUNT(1) AS total
								FROM `internal`.`edc_result` 
								WHERE $wheres
							")->row();

			$qrd = $query->qrd;
			$qsd = $query->qsd;
			$plus = $qrd + $qsd;

			$pointExisQRIS = $this->get_point('QRIS', 'Existing');

			$totalPoints = $plus * $pointExisQRIS;
		}

		return $totalPoints;
	}

	// EDC DAN EDC_QRIS
	function get_actualEDC($var, $sales, $groupDate, $type, $products)
	{
		if ($type == 'new') {
			$query = $this->db->query("SELECT
					SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS fnk,
					SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS fk,
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type = 'New'
					AND Group_Date = '$groupDate'
			")->row();
		} else {
			$query = $this->db->query("SELECT
					SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
					SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
					SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
					SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type != 'New'
					AND Group_Date = '$groupDate'
			")->row();
		}

		return $query;
	}

	// QRIS
	function get_actualQRIS($var, $sales, $groupDate, $type, $products)
	{
		if ($type == 'new') {
			$query = $this->db->query("SELECT
					SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
					SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type = 'New'
					AND Group_Date = '$groupDate'
			")->row();
		} else {
			$query = $this->db->query("SELECT
					SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
					SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type != 'New'
					AND Group_Date = '$groupDate'
			")->row();
		}

		return $query;
	}

	function get_point_edc($var, $sales, $groupDate, $mid_type, $products)
	{
		$queryPoint = $this->get_point('EDC', $mid_type);
		$points = $queryPoint;

		$query = $this->db->query("SELECT
					SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS fnk,
					SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS fk,
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type = '$mid_type'
					AND Group_Date = '$groupDate'
			")->row();

		if ($mid_type == 'KREDIT') {
			$ft = $query->fk;
		} else if ($mid_type == 'NON KREDIT') {
			$ft = $query->fnk;
		} else {
			$ft = $query->total;
		}
		$totalPoints = $ft * $points;

		return $totalPoints;
	}

	function get_point_qris($var, $sales, $groupDate, $mid_type, $products)
	{
		$queryPoint = $this->get_point('QRIS', $mid_type);
		$points = $queryPoint;

		if ($mid_type == 'New') {
			$wheres = "MID_Type = 'New'";
		} else {
			$wheres = "MID_Type != 'New'";
		}

		$query = $this->db->query("SELECT
					COUNT(1) AS total
					FROM `internal`.`edc_result`
					WHERE $var = '$sales'
					AND Product = '$products'
					AND Status_1 = 'ACCEPTED'
					AND MID_Type = '$mid_type'
					AND Group_Date = '$groupDate'
			")->row();

		$ft = $query->total;
		$totalPoints = $ft * $points;

		return $totalPoints;
	}

	function detBreakdownMerchantLeader($sales_code, $sales, $mid, $ft, $product, $groupDate)
	{
		$mid_ = strtoupper($mid);
		if ($mid == 'reject') {
			$where = "";
		} else {
			$where = " AND Facilities_Type2 = '$ft'";
		}
		$query = $this->db->query("SELECT *
				FROM `internal`.`edc_result`
				WHERE $sales_code = '$sales'
				AND Product = '$product'
				AND MID_Type = '$mid_'
				AND Group_Date = '$groupDate'
				$where 
				ORDER BY Sales_Name ASC
		");
		return $query;
		$query->free_result();
	}

	function detBreakdownMerchantLeaderEDC($sales_code, $sales, $mid, $ft, $product, $groupDate)
	{
		$mid_ = strtoupper($mid);
		$ft_ = urlencode($ft);
		$query = $this->db->query("SELECT *
				FROM `internal`.`edc_result`
				WHERE $sales_code = '$sales'
				AND Product = '$product'
				AND Status_1 = 'ACCEPTED'
				AND MID_Type = '$mid_'
				AND Facilities_Type2 = '$ft_'
				AND Group_Date = '$groupDate'
		");
		return $query;
		$query->free_result();
	}

	function detBreakdownMerchantLeaderQRIS($sales_code, $sales, $mid, $ft, $product, $groupDate)
	{
		$mid_ = strtoupper($mid);
		$ft_ = urlencode($ft);
		$query = $this->db->query("SELECT
				*
				FROM `internal`.`edc_result`
				WHERE $sales_code = '$sales'
				AND Product = '$product'
				AND Status_1 = 'ACCEPTED'
				AND MID_Type = '$mid_'
				AND Facilities_Type2 = '$ft_'
				AND Group_Date = '$groupDate'
		");

		return $query;
		$query->free_result();
	}

	function get_point($product, $mid)
	{
		$query = $this->db->query("SELECT * FROM `set_point` WHERE Product = '$product' AND MID_Type = '$mid'");
		if ($query->num_rows() == 0) {
			$point = 0;
		} else {
			$row = $query->row();
			$point = $row->Point;
		}
		return $point;
	}

	function calculate_point($product, $mid, $total)
	{
		$query = $this->db->query("SELECT * FROM `set_point` WHERE Product = '$product' AND MID_Type = '$mid'");
		if ($query->num_rows() == 0) {
			$point = 0;
		} else {
			$row = $query->row();
			$point = $row->Point;
		}
		return $point * $total;
	}

	function get_last_period()
	{
		$query = $this->db->query("SELECT Group_Date FROM internal.edc_result ORDER BY Group_Date DESC LIMIT 1");
		return $query;
		$query->free_result();
	}

	function get_last_week($group_date)
	{
		$query = $this->db->query("SELECT Week FROM internal.edc_result WHERE Group_Date='$group_date' ORDER BY Week DESC LIMIT 1");
		$result = $query->num_rows() > 1 ? $query->rows() : array('Week' => '0');
		return $result;
		//$query->free_result();
	}


	function edc_export($groupDate)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
		} else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
		} else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
		} else if ($position == 'SPV') {
			$where = "SPV_Code = '$nik'";
		} else {
			$where = "Sales_Code = '$nik'";
		}
		
		$this->db->select('*');
		$this->db->from('internal.edc_result');
		$this->db->where_in('Product',array('EDC','EDC_QRIS'));
		$this->db->where('Group_Date', $groupDate);
		$this->db->where($where);
		$this->db->order_by('Week', 'ASC');
		$query = $this->db->get();
		return $query;
		$query->free_result(); 
	}

	function qris_export($groupDate)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		
		
		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
		} else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
		} else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
		} else if ($position == 'SPV') {
			$where = "SPV_Code = '$nik'";
		} else {
			$where = "Sales_Code = '$nik'";
		}
		
		$this->db->select('*');
		$this->db->from('internal.edc_result');
		$this->db->where('Product','QRIS');
		$this->db->where('Group_Date', $groupDate);
		$this->db->where($where);
		$this->db->order_by('Week', 'ASC');
		$query = $this->db->get();
		
		return $query;
		$query->free_result();
	}
}
