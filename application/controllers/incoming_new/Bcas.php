<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bcas extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		 
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('incoming/bcas_model');
	}
	
	function index()
    {
	    $position = $this->session->userdata('position');
	    $this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		
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
		
		//load view
		$this->template->set('title','Summary BCA Syariah');
		$this->template->load('template','incoming/bcas/index',$data);
    }
	
	function get_data()
    {
		
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		// $dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader2 = array('BSH', 'RSM', 'ASM');

		if($position == 'BSH'){
			$where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM', 'SPV')";
		}
		else if($position == 'RSM'){
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		}
		else if($position == 'ASM'){
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
			// $where = "SM_Code = '$nik' AND Position IN $asm_position";
		}
		else if($position == 'SPV')
		{
			$where = "SM_Code = '$nik'";
		}
		else{
			$where = "DSR_Code = '$nik'";
		}
		
        $query = $this->bcas_model->get_datatables($where);
		
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
			$sales = $row->DSR_Code;

			if (in_array($row->Position, $array_leader)) {
				$var = 't2.'.$row->Position.'_Code';
			}
			else{
				$var = 't2.DSR_Code';
			}

			$getPemol = $this->bcas_model->get_pemol($var,$sales,$date_from,$date_to);
			$actual = $getPemol->total;
			$dsr_active = $getPemol->dsr_active;
			$dsr_input = $getPemol->dsr_input;
			//$target = 120;
			//$gap = $actual - $target;
			// $disallow_position = array('DSR');
			
			if (in_array($position, $array_leader2)) {
				$cols = array(
				    '<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active).'</span>',
					'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input).'</span>',
					'<span title="Total Input" class="badge bg-green">'.number_format($actual).'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			else{
				$cols = array(
				    '<span title="Total Input" class="badge bg-green">'.number_format($actual).'</span>'
				);
			}
			
			$data[] = array_merge(array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch),
				$cols
			);
        }

		if($position == 'ASM'){
			$get_pemol_dummy_spv = $this->bcas_model->get_pemol_dummy($nik,$date_from,$date_to,'spv');
			$actual_dummy_spv = $get_pemol_dummy_spv->total;
			$dsr_active_dummy = $get_pemol_dummy_spv->dsr_active;
			$dsr_input_dummy = $get_pemol_dummy_spv->dsr_input;
			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active_dummy).'</span>',
				'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input_dummy).'</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual_dummy_spv).'</span>',
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}
		
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->bcas_model->count_filtered($where),
            "recordsFiltered" => $this->bcas_model->count_filtered($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	public function filter_data()
	{
	    $date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		$range = $this->datediff($date_from,$date_to);
	    $data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($range > 31)
		{
			$data['inputerror'][] = 'date_to';
			$data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
		$session_data = array(
			'date_from' => $this->input->post('date_from'),
			'date_to' => $this->input->post('date_to')
		);
		$this->session->set_userdata($session_data);
		echo json_encode(array("status" => TRUE));
	}
	
	function detail($sales, $pos)
    {
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('incoming/bcas/detail');
    }
	
	function get_data_spv()
    {
	    $position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');
		$user 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader2 = array('BSH', 'RSM', 'ASM');

		if($pos == 'RSM'){
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		}
		else if($pos == 'ASM'){
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		}
		else{
			$where = "SM_Code = '$nik' AND Position IN $dsr_position";
		}
		
        $query = $this->bcas_model->get_datatables($where);
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
		    $sales = $row->DSR_Code;
			
			/*if (in_array($row->Position, $array_leader)) {
				$var = 't2.'.$row->Position.'_Code';
			}
			else{
				$var = 't2.DSR_Code';
			}*/

			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$button = '<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
					$var = 't2.'.$row->Position.'_Code';
				}else{
					$button = '';
					$var = 't2.DSR_Code';
				}
			}

			$getPemol = $this->bcas_model->get_pemol($var,$sales,$date_from,$date_to);
			$actual = $getPemol->total;
			$dsr_active = $getPemol->dsr_active;
			$dsr_input = $getPemol->dsr_input;
			
			/*if (in_array($position, $array_leader2)) {
				$cols = array(
				    '<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active).'</span>',
					'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input).'</span>',
					'<span title="Total Input" class="badge bg-green">'.number_format($actual).'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			else{
				$cols = array(
				    '<span title="Total Input" class="badge bg-green">'.number_format($actual).'</span>'
				);
			}*/
			
			//$target = 120;
			//$gap = $actual - $target;
			$data[] = array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch,
				'<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active).'</span>',
				'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input).'</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual).'</span>',
				$button
			);
        }

		if($pos == 'ASM'){
			$get_pemol_dummy_spv = $this->bcas_model->get_pemol_dummy($nik,$date_from,$date_to,'spv');
			$actual_dummy_spv = $get_pemol_dummy_spv->total;
			$dsr_active_dummy = $get_pemol_dummy_spv->dsr_active;
			$dsr_input_dummy = $get_pemol_dummy_spv->dsr_input;
			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active_dummy).'</span>',
				'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input_dummy).'</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual_dummy_spv).'</span>',
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}
		
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->bcas_model->count_filtered($where),
            "recordsFiltered" => $this->bcas_model->count_filtered($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	//================================================= INTERNAL FUNCTION =============================================//
	//datedif
	private function datediff($start,$end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);
		
		$days = date_diff($date1,$date2);
		
		return $days->format('%R%a');
	}
 	 
}