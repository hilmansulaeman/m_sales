<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Presence_meeting extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper(array('url', 'html', 'form'));
		$this->load->model('Model_presence_meeting');
	}

	function index()
	{
		$data['title']  = 'Presence Meeting';
		$start_date     = date('Y-m-01');
		$end_date       = date('Y-m-d');

		$this->session->set_userdata('start_date', $start_date);
		$this->session->set_userdata('end_date', $end_date);

        $this->template->load('template', 'meeting/Presence/index', $data);
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
		}
		else if($position == 'SPV')
		{
			$where = "SM_Code = '$nik'";
		}
		else{
			$where = "DSR_Code = '$nik'";
		}
        $query = $this->Model_presence_meeting->get_datatables($where)->result();
		
        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row){
			$sales = $row->DSR_Code;
            // cekvar($sales);
			
			//Total Meeting Up
			$getTotal_meeting_up = $this->Model_presence_meeting->get_total_meeting_up($nik,$sales,$date_from,$date_to)->result();
			$getTotal_meeting_up = (!empty($getTotal_meeting_up))?$getTotal_meeting_up[0]->total:0;
			
			//Total Hadir Up
			$getHadir_meeting_up = $this->Model_presence_meeting->get_total_hadir_up($nik,$sales,$date_from,$date_to)->result();
			$getHadir_meeting_up = (!empty($getHadir_meeting_up))?$getHadir_meeting_up[0]->total:0;
			
			//Total Meeting Bottom
			$getTotal_meeting_bottom = $this->Model_presence_meeting->get_total_meeting_bottom($nik,$sales,$date_from,$date_to)->result();
			$getTotal_meeting_bottom = (!empty($getTotal_meeting_bottom))?$getTotal_meeting_bottom[0]->total:0;
			
            //Total Invite Room
            $getTotal_invite_room = $this->Model_presence_meeting->get_total_invite_room($nik,$sales,$date_from,$date_to)->result();
            $getTotal_invite_room = (!empty($getTotal_invite_room))?$getTotal_invite_room[0]->total:0;

			//Total Hadir  
			$getHadir_meeting_bottom = $this->Model_presence_meeting->get_total_hadir_bottom($nik,$sales,$date_from,$date_to)->result();
			$getHadir_meeting_bottom = (!empty($getHadir_meeting_bottom))?$getHadir_meeting_bottom[0]->total:0;

            //Total Tidak Hadir
            $getTidak_hadir_meeting_bottom = $this->Model_presence_meeting->get_total_tidak_hadir_bottom($nik,$sales,$date_from,$date_to)->result();
			$getTidak_hadir_meeting_bottom = (!empty($getTidak_hadir_meeting_bottom))?$getTidak_hadir_meeting_bottom[0]->total:0;

			if($getTotal_meeting_up==0){	
				$total_meeting_up = '<span title="Total Meeting Up" class="">'.number_format($getTotal_meeting_up).'</span>';
				$total_meeting = '<span title="Total Meeting" class="">'.number_format($getHadir_meeting_up).'</span>';
				$total_meeting_bottom = '<span title="Total Meeting Up" class="">'.number_format($getTotal_meeting_bottom).'</span>';
			}else{
				$total_meeting_bottom = '<a href="javascript:void(0);" onclick="view_total_meeting_bottom(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" title="Total Meeting Bootom" class="">'.number_format($getTotal_meeting_bottom).'</a>';
			}
			
			if (in_array($position, $array_leader2)) {
                $cols = array(		
                    '<span title="Total Invite Room" class="">'.number_format($getTotal_invite_room).'</span>',
					'<span title="Total Hadir Bottom" class="">'.number_format($getHadir_meeting_bottom).'</span>',
					'<span title="Total Tidak Hadir Bottom" class="">'.number_format($getTidak_hadir_meeting_bottom).'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			else{
				$cols = array(
				    $total_meeting,				    
				    '<span title="Total Hadir" class="">'.number_format($actual_absent).'</span>'
				);
			}
			
			$data[] = array_merge(array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')'),
				$cols
			);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Model_presence_meeting->count_filtered($where),
            "recordsFiltered" => $this->Model_presence_meeting->count_filtered($where),
            "data" => $data,
        );

        echo json_encode($output);
    }


    function detail($sales, $pos)
    {
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		$this->load->view('meeting/Presence/detail');
    }

    function get_data_spv()
    {

	    $position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');
		$user 		= $this->session->userdata('sl_code');
		
		$date_from  = $this->input->post('date_from');
		$date_to    = $this->input->post('date_to');
		
		$dsr_position       = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position       = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure    = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader       = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_leader2      = array('BSH', 'RSM', 'ASM');

		if($pos == 'RSM'){
			$where          = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		}
		else if($pos == 'ASM'){
			$where          = "SM_Code = '$nik' AND Position = 'SPV'";
		}
		else{
			$where          = "SM_Code = '$nik' AND Position IN $dsr_position";
		}
		
        $query      = $this->Model_presence_meeting->get_datatables($where)->result();
		$data       = array();
        $no         = $this->input->post('start');

		foreach ($query as $row){
		    $sales  = $row->DSR_Code;
			
			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$button         = '<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				}else{
					$button         = '';
				}
			}

            //Total Invite Room
            $getTotal_invite_room   = $this->Model_presence_meeting->get_total_invite_room($nik,$sales,$date_from,$date_to)->result();
            $getTotal_invite_room   = (!empty($getTotal_invite_room))?$getTotal_invite_room[0]->total:0;

			//Total Hadir
            $getHadirAbsent         = $this->Model_presence_meeting->get_total_hadir_bottom($nik,$sales,$date_from,$date_to)->result();
			$getHadirAbsent         = (!empty($getHadirAbsent))?$getHadirAbsent[0]->total:0;

            //Total Tidak Hadir
            $getTidakAbsent         = $this->Model_presence_meeting->get_total_tidak_hadir_bottom($nik,$sales,$date_from,$date_to)->result();
			$getTidakAbsent         = (!empty($getTidakAbsent))?$getTidakAbsent[0]->total:0;
			
			$data[] = array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
                '<span title="Total Invite Room" class="">'.number_format($getTotal_invite_room).'</span>',
                '<span title="Total Hadir" class="">'.number_format($getHadirAbsent).'</span>',
				'<span title="Total Tidak Hadir" class="">'.number_format($getTidakAbsent).'</span>',
				$button
			);
        }

        $output = array(
            "draw"              => $this->input->post('draw'),
            "recordsTotal"      => $this->Model_presence_meeting->count_filtered($where),
            "recordsFiltered"   => $this->Model_presence_meeting->count_filtered($where),
            "data"              => $data,
        );

        echo json_encode($output);

    }
}