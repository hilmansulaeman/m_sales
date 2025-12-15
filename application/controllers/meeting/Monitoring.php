<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('meeting_monitoring_model');
	}

	function index()
    {
	    $position = $this->session->userdata('position');
		
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
		$this->template->set('title','Monitoring');
		$this->template->load('template','meeting/monitoring/index',$data);
    }
	
	function get_data()
    {
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');

		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');

		if($date_from !='' && $date_to != ''){
			$date_from = $this->input->post('date_from');
			$date_to = $this->input->post('date_to');
		}else{
			$date_from = date('Y-m-01');
			$date_to = date('Y-m-d');
		}

		// $dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader2 = array('BSH', 'RSM', 'ASM');

		if($position == 'BSH'){
			$where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM')";
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
        $query = $this->meeting_monitoring_model->get_datatables($where)->result();
		
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
			$sales = $row->DSR_Code;
			
			//Total Meeting Up
			$getTotal_meeting_up = $this->meeting_monitoring_model->get_total_meeting_up($nik,$sales,$date_from,$date_to)->result();
			$getTotal_meeting_up = (!empty($getTotal_meeting_up))?$getTotal_meeting_up[0]->total:0;
			
			//Total Hadir Up
			$getHadir_meeting_up = $this->meeting_monitoring_model->get_total_hadir_up($nik,$sales,$date_from,$date_to)->result();
			$getHadir_meeting_up = (!empty($getHadir_meeting_up))?$getHadir_meeting_up[0]->total:0;
			
			//Total Meeting Bottom
			$getTotal_meeting_bottom = $this->meeting_monitoring_model->get_total_meeting_bottom($nik,$sales,$date_from,$date_to)->result();
			$getTotal_meeting_bottom = (!empty($getTotal_meeting_bottom))?$getTotal_meeting_bottom[0]->total:0;
			
			//Total Hadir Bottom 
			$getHadir_meeting_bottom = $this->meeting_monitoring_model->get_total_hadir_bottom($nik,$sales,$date_from,$date_to)->result();
			$getHadir_meeting_bottom = (!empty($getHadir_meeting_bottom))?$getHadir_meeting_bottom[0]->total:0;

			//if($actual_absent_leader==0){				    
			if($getTotal_meeting_up==0){	
				$total_meeting_up = '<span title="Total Meeting Up" class="">'.number_format($getTotal_meeting_up).'</span>';
				$total_meeting = '<span title="Total Meeting" class="">'.number_format($getHadir_meeting_up).'</span>';
				$total_meeting_bottom = '<span title="Total Meeting Up" class="">'.number_format($getTotal_meeting_bottom).'</span>';
			}else{
				$total_meeting_up = '<a href="javascript:void(0);" onclick="view_total_meeting_up(\''.$nik.'\',\''.$row->Position.'\',\''.$row->Name.'\')" title="Total Meeting Up" class="">'.number_format($getTotal_meeting_up).'</a>';
				$total_meeting_bottom = '<a href="javascript:void(0);" onclick="view_total_meeting_bottom(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" title="Total Meeting Bootom" class="">'.number_format($getTotal_meeting_bottom).'</a>';
			}
			
			if (in_array($position, $array_leader2)) {
				$cols = array(					
				    //$total_meeting_up,
					//'<span title="Total Hadir Up" class="">'.number_format($getHadir_meeting_up).'</span>',
					$total_meeting_bottom,
					'<span title="Total Hadir Bottom" class="">'.number_format($getHadir_meeting_bottom).'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			else{
				$cols = array(
					// '<span title="Total DSR" class="badge bg-black">'.number_format($actual_dsr).'</span>',
				    // '<span title="Total Meeting" class="badge bg-red">'.number_format($actual).'</span>',
				    $total_meeting,				    
				    '<span title="Total Hadir" class="">'.number_format($actual_absent).'</span>'
				);
			}
			
			$data[] = array_merge(array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch),
				$cols
			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered($where),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

	function detail($sales, $pos)
    {
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('meeting/monitoring/detail');
    }
	
	function get_data_spv()
    {
	    $position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');
		$user 		= $this->session->userdata('sl_code');
		
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
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
		
        $query = $this->meeting_monitoring_model->get_datatables($where)->result();

		// cekvar($query);
		// die();

		$data = array();
        $no = $this->input->post('start');

		foreach ($query as $row){
		    $sales = $row->DSR_Code;

			// cekdb();
			
			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$button = '<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				}else{
					$button = '';
				}
			}

			
			$getMeeting = $this->meeting_monitoring_model->get_total_meeting_up($nik,$sales,$date_from,$date_to)->result();

			$actual = (!empty($getMeeting))?$getMeeting[0]->total:0;

			// echo $actual;
			// die();
			

			if($actual==0){				    
				$total_meeting = '<span title="Total Meeting" class="">'.number_format($actual).'</span>';
			}else{
				// $total_meeting = '<a href="javascript:void(0);" onclick="view_schedule(\''.$sales.'\',\''.$date_from.'\',\''.$date_to.'\')" title="Total Meeting" class="">'.number_format($actual).'</a>';
				$total_meeting = '<a href="javascript:void(0);" onclick="view_schedule(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" title="Total Meeting" class="">'.number_format($actual).'</a>';
			}

			$getAbsent = $this->meeting_monitoring_model->get_absent_by_Schedule_ID_up($nik,$sales,$date_from,$date_to)->result();
			
			$actual_absent = (!empty($getAbsent))?$getAbsent[0]->total:0;
			
			$data[] = array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch,
				$total_meeting,
				'<span title="Total Hadir" class="">'.number_format($actual_absent).'</span>',
				$button
			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered($where),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

	// ------------------------------ Back Up get_data_svp lama --------------------------------- //

	// function get_data_spv()
    // {
	//     $position 	= $this->session->userdata('position');
	// 	$nik 		= $this->session->userdata('sm_code');
	// 	$pos 		= $this->session->userdata('sm_position');
	// 	$user 		= $this->session->userdata('sl_code');
		
	// 	$date_from = $this->input->post('date_from');
	// 	$date_to = $this->input->post('date_to');
		
	// 	$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
	// 	$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
	// 	$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');
	// 	$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
	// 	$array_leader2 = array('BSH', 'RSM', 'ASM');

	// 	if($pos == 'RSM'){
	// 		$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
	// 	}
	// 	else if($pos == 'ASM'){
	// 		$where = "SM_Code = '$nik' AND Position = 'SPV'";
	// 	}
	// 	else{
	// 		$where = "SM_Code = '$nik' AND Position IN $dsr_position";
	// 	}
		
    //     $query = $this->meeting_monitoring_model->get_datatables($where)->result();
    //     $data = array();
    //     $no = $this->input->post('start');
    //     foreach ($query as $row){
	// 	    $sales = $row->DSR_Code;
			
	// 		if (in_array($position, $array_structure)) {
	// 			if (in_array($row->Position, $array_structure)) {
	// 				$button = '<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
	// 			}else{
	// 				$button = '';
	// 			}
	// 		}

	// 		// $getMeeting = $this->meeting_monitoring_model->get_meeting($nik,$date_from,$date_to)->result();
			
	// 		// $actual = (!empty($getMeeting))?$getMeeting[0]->total:0;
			
	// 		$getMeeting = $this->meeting_monitoring_model->get_total_meeting_up($nik,$sales,$date_from,$date_to)->result();
	// 		//cekdb();
	// 		$actual = (!empty($getMeeting))?$getMeeting[0]->total:0;
			

	// 		if($actual==0){				    
	// 			$total_meeting = '<span title="Total Meeting" class="">'.number_format($actual).'</span>';
	// 		}else{
	// 			$total_meeting = '<a href="javascript:void(0);" onclick="view_schedule(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" title="Total Meeting" class="">'.number_format($actual).'</a>';
	// 		}

	// 		/*$getDSR = $this->meeting_monitoring_model->get_anak_buah($sales)->result();
	// 		$actual_dsr = $getDSR[0]->total;*/
			
	// 		//$getAbsent = $this->meeting_monitoring_model->get_absent($nik,$sales,$date_from,$date_to)->result();
	// 		$getAbsent = $this->meeting_monitoring_model->get_absent_by_Schedule_ID_up($nik,$sales,$date_from,$date_to)->result();
			
	// 		$actual_absent = (!empty($getAbsent))?$getAbsent[0]->total:0;
			
	// 		$data[] = array(
	// 			++$no,
	// 			$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
	// 			$row->Branch,
	// 			// '<span title="Total DSR" class="badge bg-black">'.number_format($actual_dsr).'</span>',
	// 			// '<span title="Total Meeting" class="badge bg-red">'.number_format($actual).'</span>',
	// 			$total_meeting,
	// 			'<span title="Total Hadir" class="">'.number_format($actual_absent).'</span>',
	// 			$total_meeting,
	// 			'<span title="Total Hadir" class="">'.number_format($actual_absent).'</span>',
	// 			$button
	// 		);
    //     }

    //     $output = array(
    //         "draw" => $this->input->post('draw'),
    //         "recordsTotal" => $this->meeting_monitoring_model->count_filtered($where),
    //         "recordsFiltered" => $this->meeting_monitoring_model->count_filtered($where),
    //         "data" => $data,
    //     );
    //     //output dalam format JSON
    //     echo json_encode($output);
    // }


	function detail_schedule($sales, $pos)
    {
    	// $sales = str_replace("_"," ",$sales); 
	    // $this->session->set_userdata('sm_name', $sales);
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('meeting/monitoring/detail_schedule');
    }
	
	function detail_schedule_up($sales, $pos)
    {
    	// $sales = str_replace("_"," ",$sales); 
	    // $this->session->set_userdata('sm_name', $sales);
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('meeting/monitoring/detail_schedule_up');
    }

    function get_data_schedule()
    {
		// $sales 		= $this->session->userdata('sm_name');
		$sales 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');	
		
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
	
		if($date_from !='' && $date_to != ''){
			$date_from = $this->input->post('date_from');
			$date_to = $this->input->post('date_to');
		}else{
			$date_from = date('Y-m-01');
			$date_to = date('Y-m-d');
		} 
			
        /*$query = $this->meeting_monitoring_model->get_schedule($sales,$date_from,$date_to)->result();*/
        $query = $this->meeting_monitoring_model->get_schedule($sales,$date_from,$date_to)->result();
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
        	$getHadir= $this->meeting_monitoring_model->get_absent_by_Schedule_ID($row->Schedule_ID)->result();
			$getTidakHadir= $this->meeting_monitoring_model->get_tidak_absent_by_Schedule_ID($row->Schedule_ID)->result();
			
			// $total_hadir = (!empty($getHadir))?$getHadir[0]->total:0;
			// $total_tidak_hadir = (!empty($getTidakHadir))?$getTidakHadir[0]->total:0;
			// if($total_hadir==0){				    
			// 	$total_hadir = '<span title="Total Hadir" class="badge bg-red">'.number_format($total_hadir).'</span>';
			// }else{				
			// 	$total_hadir = number_format($total_hadir);
			// 	$total_tidak_hadir = number_format($total_tidak_hadir);
			// }

			$total_hadir = (!empty($getHadir))?$getHadir[0]->total:0;
			$total_tidak_hadir = (!empty($getTidakHadir))?$getTidakHadir[0]->total:0;
			if($total_hadir==0){				    
				$total_hadir = '<a href="javascript:void(0);" class="badge bg-red" onclick="view_absenhadir('.$row->Schedule_ID.')" >'.number_format($total_hadir).'</a>';
				$total_tidak_hadir = '<a href="javascript:void(0);" onclick="view_absentidakhadir('.$row->Schedule_ID.')" >'.number_format($total_tidak_hadir).'</a>';
			}else if($total_tidak_hadir==0){				    
				$total_hadir = '<a href="javascript:void(0);" onclick="view_absenhadir('.$row->Schedule_ID.')" >'.number_format($total_hadir).'</a>';
				$total_tidak_hadir = '<a href="javascript:void(0);" class="badge bg-red" onclick="view_absentidakhadir('.$row->Schedule_ID.')" >'.number_format($total_tidak_hadir).'</a>';
			}else{				
				$total_hadir = '<a href="javascript:void(0);" onclick="view_absenhadir('.$row->Schedule_ID.')" >'.number_format($total_hadir).'</a>';
				$total_tidak_hadir = '<a href="javascript:void(0);" onclick="view_absentidakhadir('.$row->Schedule_ID.')" >'.number_format($total_tidak_hadir).'</a>';
			}

			$mom = '<a href="javascript:void(0);" onclick="view_mom('.$row->Schedule_ID.')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';

			$data[] = array(
				++$no,
				$row->Schedule_Date,
				$row->Created_By,
				$row->Tema,
				$row->Schedule_Day,
				$row->Schedule_Type,
				$row->Location_Name,
				$row->Link_Meeting,
				$total_hadir,
				$total_tidak_hadir,
				$row->Status,
				$mom

			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	function get_data_schedule_up()
    {
		// $sales 		= $this->session->userdata('sm_name');
		$sales 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');	
		
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
	
		if($date_from !='' && $date_to != ''){
			$date_from = $this->input->post('date_from');
			$date_to = $this->input->post('date_to');
		}else{
			$date_from = date('Y-m-01');
			$date_to = date('Y-m-d');
		}
			
        /*$query = $this->meeting_monitoring_model->get_schedule($sales,$date_from,$date_to)->result();*/
        $query = $this->meeting_monitoring_model->get_schedule_up($sales,$date_from,$date_to)->result();
		
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
        	$getHadir= $this->meeting_monitoring_model->get_absent_by_Schedule_ID_up($row->Schedule_ID)->result();
			$total_hadir = (!empty($getHadir))?$getHadir[0]->total:0;
			if($total_hadir==0){				     
				$total_hadir = '<span title="Total Hadir" class="badge bg-red">'.number_format($total_hadir).'</span>';
			}else{				
				$total_hadir = '<a href="javascript:void(0);" onclick="view_participant(\''.$row->Schedule_ID.'\')" title="Total Meeting" class="badge bg-red">'.number_format($total_hadir).'</a>';
			}

			$mom = '<a href="javascript:void(0);" onclick="view_mom('.$row->Schedule_ID.')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';

			$data[] = array(
				++$no,
				$row->Schedule_Date,
				$row->Created_By,
				$row->Tema,
				$row->Schedule_Day,
				$row->Schedule_Type,
				$row->Location_Name,
				$row->Link_Meeting,
				$row->Status,
				
				$mom

			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	function detail_participant($Schedule_ID)
    {
    	$this->session->set_userdata('sm_schedule_id', $Schedule_ID);    	    
		
		//load view
		$this->load->view('meeting/monitoring/detail_participant');
    }

	function detail_mom($Schedule_ID){

    	// $this->session->set_userdata('sm_schedule_id', $Schedule_ID);    
		
		$dataHasilMom = $this->meeting_monitoring_model->get_by_Id($Schedule_ID)->row_array();
		$dataListData = $this->meeting_monitoring_model->get_list_data($Schedule_ID);

		$data = [
			'dataMom' => $dataHasilMom,
			'dataList' => $dataListData->result()
		];

		echo json_encode($data);

	}

	function detail_viewmeeting($Schedule_ID){

    	// $this->session->set_userdata('sm_schedule_id', $Schedule_ID);    
		
		$dataHasilMom = $this->meeting_monitoring_model->get_by_Id($Schedule_ID)->row_array();
		$dataListData = $this->meeting_monitoring_model->get_list_meeting($Schedule_ID);

		$data = [
			'dataviewmeeting' => $dataHasilMom,
			'dataList' => $dataListData->result()
		];

		echo json_encode($data);

	}

	function detail_tidakabsen($Schedule_ID){

		$dataListData = $this->meeting_monitoring_model->get_list_tidakabsen($Schedule_ID);

		$data = [
			'dataList' => $dataListData->result()
		];

		echo json_encode($data);

	}

	function detail_meetinghadir($Schedule_ID){

		$dataListData = $this->meeting_monitoring_model->get_list_meeting($Schedule_ID);

		$data = [
			'dataList' => $dataListData->result()
		];

		echo json_encode($data);

	}

	function detail_meetingtidakhadir($Schedule_ID){

		$dataListData = $this->meeting_monitoring_model->get_list_meetingtidakhadir($Schedule_ID);

		$data = [
			'dataList' => $dataListData->result()
		];

		echo json_encode($data);

	}


	function detail_meeting($sales, $pos)
    {
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('meeting/monitoring/detail_meeting');
    }

	function set_id_schedule(){
		$Schedule_ID = $this->input->post('Schedule_ID');
		$this->session->set_userdata('sm_schedule_id', $Schedule_ID);
		echo json_encode($data);
	}


    function get_data_participant()
    {
		
		$Schedule_ID = $this->session->userdata('sm_schedule_id');
				
        $query = $this->meeting_monitoring_model->get_participant($Schedule_ID)->result();
        
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
			$data[] = array(
				++$no,
				$row->Name,
			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered_participant($Schedule_ID),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered_participant($Schedule_ID),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

	function get_data_meeting()
    {
		$sales 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');	
		
		$date_from 	= $this->input->post('date_from');
		$date_to 	= $this->input->post('date_to');
	
		if($date_from !='' && $date_to != ''){
			$date_from 	= $this->input->post('date_from');
			$date_to 	= $this->input->post('date_to');
		}else{
			$date_from 	= date('Y-m-01');
			$date_to 	= date('Y-m-d');
		} 
			
        $query 		= $this->meeting_monitoring_model->get_schedule($sales,$date_from,$date_to)->result();
        $data 		= array();
        $no 		= $this->input->post('start');
        foreach ($query as $row){

        	$getHadir		= $this->meeting_monitoring_model->get_absent_by_Schedule_ID($row->Schedule_ID)->result();
			$getTidakHadir	= $this->meeting_monitoring_model->get_tidak_absent_by_Schedule_ID($row->Schedule_ID)->result();
			
			$total_hadir 		= (!empty($getHadir))?$getHadir[0]->total:0;
			$total_tidak_hadir 	= (!empty($getTidakHadir))?$getTidakHadir[0]->total:0;

			if($total_hadir==0){				    
				$total_hadir 		= '<span class="badge bg-red" >'.number_format($total_hadir).'</span>';
				$total_tidak_hadir 	= '<a href="javascript:void(0);" onclick="view_meetingtidakhadir('.$row->Schedule_ID.')" >'.number_format($total_tidak_hadir).'</a>';
			}else if($total_tidak_hadir==0){				    
				$total_hadir 		= '<a href="javascript:void(0);" onclick="view_meetinghadir('.$row->Schedule_ID.')" >'.number_format($total_hadir).'</a>';
				$total_tidak_hadir 	= '<span class="badge bg-red" >'.number_format($total_tidak_hadir).'</span>';
			}else{				
				$total_hadir 		= '<a href="javascript:void(0);" onclick="view_meetinghadir('.$row->Schedule_ID.')" >'.number_format($total_hadir).'</a>';
				$total_tidak_hadir 	= '<a href="javascript:void(0);" onclick="view_meetingtidakhadir('.$row->Schedule_ID.')" >'.number_format($total_tidak_hadir).'</a>';
			}

			if($row->Link_Meeting == ''){
				$link_meeting = '<span title="Link Meeting" class="">-</span>';
			}else{
				$link_meeting = $row->Link_Meeting;
			}

			$mom = '<a href="javascript:void(0);" onclick="view_detailmeeting('.$row->Schedule_ID.')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';

			$data[] = array(
				++$no,
				$row->Schedule_Date,
				$row->Created_By,
				$row->Tema,
				$row->Schedule_Day,
				$row->Schedule_Type,
				$row->Location_Name,
				$link_meeting ,
				$total_hadir,
				$total_tidak_hadir,
				$row->Status,
				$mom

			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "recordsFiltered" => $this->meeting_monitoring_model->count_filtered_schedule($sales,$date_from,$date_to),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
 	 
}
