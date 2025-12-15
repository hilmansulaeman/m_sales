<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Pl extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('decision/pl_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code'); //DSR_Code
		$position 	= $this->session->userdata('position');
		$date_filter = $this->input->post('date');
		if(isset($date_filter)){
			$this->session->set_userdata('groupDate', $date_filter);
		}
		else{
			$period = $this->pl_model->get_last_period();
			$group_date = $period->num_rows() == 0 ? date('Y-m') : $period->row()->Group_Date;
			$this->session->set_userdata('groupDate', $group_date);
		}
		$groupDate 	= $this->session->userdata('groupDate');
		
		if($position == 'BSH'){
			$data['position'] = "RSM";
			$data['detail'] = "ASM";
		}
		else if($position == 'RSM'){
			$data['position'] = "ASM";
			$data['detail'] = "SPV";
		}
		else if($position == 'ASM'){
			$data['position'] = "SPV";
			$data['detail'] = "DSR";
		}
		else if($position == 'SPV'){
			$data['position'] = "DSR";
			$data['detail'] = "DSR";
		}
		else{
			$data['position'] = "DSR";
		}

		if ($position == 'DSR') {
			$views = 'index_dsr';

			$data['breakdown_pl'] = $this->pl_model->breakdown_pl_dsr($sales_code, $groupDate);
			$data['sales_code'] = $sales_code;
		}else{
			$views = 'index';
		}
		//load view
		$this->template->set('title','Data Decision PL');
		$this->template->load('template','decision/pl/'.$views, $data);
	}

	// START UPDATE By m.a
	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('username');
		$groupDate 	= $this->session->userdata('groupDate');
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		if($position == 'BSH'){
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
			$group_name = "RSM_Name";
		}
		else if($position == 'RSM'){
			$where = "RSM_Code = '$nik'";
			$groups = "ASM_Code";
			$group_name = "ASM_Name";
		}
		else if($position == 'ASM'){
			$where = "ASM_Code = '$nik'";
			$groups = "SPV_Code";
			$group_name = "SPV_Name";
		}
		else
		{
			$where = "SPV_Code = '$nik'";
			$groups = "Sales_Code";
			$group_name = "Sales_Name";
		}
		$query = $this->pl_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row){
			if ($position == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$var = 'RSM_Code';
			}else if($position == 'RSM'){
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$var = 'ASM_Code';
			}else if($position == 'ASM'){
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$var = 'SPV_Code';
			}else{
				$sales_code = $row->Sales_Code;
				$sales_name = $row->Sales_Name;
				$var = 'Sales_Code';
			}

			$sales_position = substr($var, 0, -5);

			$getActualStatus = $this->pl_model->breakdown_pl($sales_code, $groupDate);

			$data[] = array(
				++$no,
				$sales_code.', '.$sales_name.' ('.$sales_position.')',
				'<a href="javascript:void(0);" onclick="view_detail(\''.$sales_code.'\',\'APPROVE\',\''.$sales_name.'\')"><span title="Total Accept Approve" class="badge bg-green">'.$getActualStatus->approve.'</span></a>',
				'<a href="javascript:void(0);" onclick="view_detail(\''.$sales_code.'\',\'INPROCESS\',\''.$sales_name.'\')"><span title="Total Accept In Process" class="badge bg-yellow">'.$getActualStatus->inprocess.'</span></a>',
				'<a href="javascript:void(0);" onclick="view_detail(\''.$sales_code.'\',\'CANCEL\',\''.$sales_name.'\')"><span title="Total Accept Cancel" class="badge bg-red">'.$getActualStatus->cancel.'</span></a>', 
				'<a href="javascript:void(0);" onclick="view_detail(\''.$sales_code.'\',\'DECLINE\',\''.$sales_name.'\')"><span title="Total Accept Decline" class="badge bg-black">'.$getActualStatus->decline.'</span></a>',
				'<a href="javascript:void(0);" onclick="view_spv(\''.$sales_code.'\',\''.$sales_position.'\',\''.$sales_name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->pl_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->pl_model->count_filtered($where, $groups, $groupDate),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function filter_data()
	{
		$group_date = $this->input->post('group_date');
		// $date_to = $this->input->post('date_to');
		// $range = $this->datediff($date_from,$date_to);
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		// if($range > 31)
		// {
		// 	$data['inputerror'][] = 'date_to';
		// 	$data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
		// 	$data['status'] = FALSE;
		// }

		// if($data['status'] === FALSE)
		// {
		// 	echo json_encode($data);
		// 	exit();
		// }
		$session_data = array(
			'groupDate' => $this->input->post('group_date'),
			// 'date_to' => $this->input->post('date_to')
		);
		$this->session->set_userdata($session_data);
		echo json_encode(array("status" => TRUE));
	}

	function detailSPV($sales, $pos)
	{
		$this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('decision/pl/detailSPV');
	}

	function detailActual($sales, $status)
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('username');
		$groupDate 	= $this->session->userdata('groupDate');

		$data['query'] = $this->pl_model->getBreakdownPl($sales, $status, $groupDate);
		$data['status'] = $status;

		$this->load->view('decision/pl/breakdown_pl', $data);
	}

	function detailActualLink($sales, $status, $name)
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('username');
		$groupDate 	= $this->session->userdata('groupDate');

		$data['query'] = $this->pl_model->getBreakdownPl($sales, $status, $groupDate);
		$data['status'] = $status;

		$this->template->set('title','Detail Decision PL');
		$this->template->load('template','decision/pl/breakdown_pl_link', $data);
	}

	function get_data_spv()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
		$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
		$user 		= $this->session->userdata('username'); // id yang sedang login
		$groupDate 	= $this->session->userdata('groupDate');
		
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		/*if($pos == 'BSH'){
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
		}*/
		if($pos == 'RSM'){
			$where = "RSM_Code = '$nik' AND BSH_Code = '$user'";
			$groups = "ASM_Code";
		}
		else if($pos == 'ASM'){
			$where = "ASM_Code = '$nik' AND (RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "SPV_Code";
		}
		else{
			$where = "SPV_Code = '$nik' AND (ASM_Code = '$user' OR RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "Sales_Code";
		}
		
		$query = $this->pl_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row){
			if ($pos == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$upliner = $row->BSH_Code;
				$var  = 'RSM_Code';
				$var2 = 'BSH_Code';
			}else if($pos == 'RSM'){
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$upliner = $row->RSM_Code;
				$var  = 'ASM_Code';
				$var2 = 'RSM_Code';
			}else if($pos == 'ASM'){
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$upliner = $row->ASM_Code;
				$var  = 'SPV_Code';
				$var2 = 'ASM_Code';
			}else{
				$sales_code = $row->Sales_Code;
				$sales_name = $row->Sales_Name;
				$upliner = $row->SPV_Code;
				$var  = 'Sales_Code';
				$var2 = 'SPV_Code';
			}

			$sales_position = substr($var, 0, -5);

			$getActualStatus = $this->pl_model->breakdown_pl_detail($sales_code, $var2, $upliner, $groupDate);

			if (in_array($sales_position, $array_structure)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\''.$sales_code.'\',\''.$sales_position.'\',\''.$sales_name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
				';
			}else{
				$buttons = '';
			}
			
			$data[] = array(
				++$no,
				$sales_code.', '.$sales_name.' ('.$sales_position.')',
				'<a href="'.site_url('decision/pl/detailActualLink/'.$sales_code.'/APPROVE/'.$sales_name).'" target="_blank"><span title="Total Accept Approve" class="badge bg-green">'.$getActualStatus->approve.'</span></a>', 
				'<a href="'.site_url('decision/pl/detailActualLink/'.$sales_code.'/INPROCESS/'.$sales_name).'" target="_blank"><span title="Total Accept In Process" class="badge bg-yellow">'.$getActualStatus->inprocess.'</span></a>',
				'<a href="'.site_url('decision/pl/detailActualLink/'.$sales_code.'/CANCEL/'.$sales_name).'" target="_blank"><span title="Total Accept Cancel" class="badge bg-red">'.$getActualStatus->cancel.'</span></a>',
				'<a href="'.site_url('decision/pl/detailActualLink/'.$sales_code.'/DECLINE/'.$sales_name).'" target="_blank"><span title="Total Accept Decline" class="badge bg-black">'.$getActualStatus->decline.'</span></a>',
				$buttons
			);
		}
		
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->pl_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->pl_model->count_filtered($where, $groups, $groupDate),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	/*function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
		if($this->input->post('date') == "")
		{
			$tgl = date('Y-m');
		}
		else
		{
			$tgl = $this->input->post('date');
		}
		
		if($posisi == "DSR" or $posisi == "SPG" or $posisi == "SPB")
		{
			$varPos = "DSR_Code";
		}elseif($posisi == "SPV")
		{
			$varPos = "SPV_Code";
		}elseif($posisi == "ASM")
		{
			$varPos = "ASM_Code";
		}elseif($posisi == "RSM")
		{
			$varPos = "RSM_Code";
		}elseif($posisi == "BSH" OR $posisi == "ASH")
		{
			$varPos = "BSH_Code";
		}
		//$data['counter'] = $this->pl_model->getCounter($sales_code, $varPos, $tgl);
		$data['breakdown_pl'] = $this->pl_model->breakdown_pl($sales_code, $tgl);
		//load view
		$this->template->set('title','Data Decision PL');
		$this->template->load('template','decision/pl/index', $data);
	}
	
	function det_breakdown_pl($status, $tgl)
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
		if($posisi == "DSR" or $posisi == "PSG" or $posisi == "SPB")
		{
			$varPos = "DSR_Code";
		}elseif($posisi == "SPV")
		{
			$varPos = "SPV_Code";
		}elseif($posisi == "ASM")
		{
			$varPos = "ASM_Code";
		}elseif($posisi == "RSM")
		{
			$varPos = "RSM_Code";
		}elseif($posisi == "BSH" OR $posisi == "ASH")
		{
			$varPos = "BSH_Code";
		}
		$data['query'] = $this->pl_model->getBreakdownPl($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/pl/breakdown_pl', $data);
	}*/
}