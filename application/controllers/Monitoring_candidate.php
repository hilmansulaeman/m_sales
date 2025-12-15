<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_candidate extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('auth', 'template', 'unit_test'));
		$this->load->model('monitoring_candidate_model', 'model');
	}

	function index()
	{
		$sl_code  = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');
		$data['position'] = $position;

		$this->template->set('title', 'Monitoring Candidate');
		$this->template->load('template', 'monitoring_candidate/index', $data);
	}

	function get_data()
	{
		$position  = $this->session->userdata('position');
		$nik 		   = $this->session->userdata('sl_code');
		
		$query = $this->model->get_datatables($position, $nik);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			// if (is_null($row->Attendance)) {
			// 	$statusAttendance = "<span class='label label-default'>No status</span>";
			// } else if($row->Attendance == 'hadir') {
			// 	$statusAttendance = "<span class='label label-success'>Hadir</span>";
			// } else {
			// 	$statusAttendance = "<span class='label label-danger'>Tidak Hadir</span>";
			// }

			$ccDSR = array('SPG', 'SPB', 'DSR');
			if ($row->Product == 'CC') {
				if ($row->Position == 'Mobile Sales') {
					$product = "CC (MS)";
				} else if (in_array($row->Position, $ccDSR)) {
					$product = "CC (DSR)";
				} else {
					$product = "CC";
				}
			} else {
				$product = $row->Product;
			}

			$voucher    = $row->Code_Voucher;
			$attendance = "<span class='label label-default'>-</span>";
			$score      = "<span class='label label-default'>-</span>";
			$grades     = "<span class='label label-default'>-</span>";

			if ($voucher != '') {
				$getDataParticipant = $this->model->get_data_participant($row->Participant_ID);
				if (is_null($getDataParticipant->Score_Grade)) {
					$getCourseID        = $this->model->get_course_id($row->Product);
					$getSingleVoucher   = $this->model->get_single_voucher($voucher);
					if (is_array($getSingleVoucher)) {
						$gsv = $getSingleVoucher[0];
						$getGrades          = $this->model->get_grades($getCourseID->Course_ID, $gsv->useridnumber);
						$getAttendance      = $this->model->get_attendance($getCourseID->Course_ID, $gsv->useridnumber);
						
						if (is_array($getGrades)) {
							if (count($getGrades) == 1) {
								$rowGetGrades = $getGrades[0];
								$colorScore = ($rowGetGrades->status == "Belum Lulus") ? 'warning' : 'success';
			
								$score = "<span class='label label-{$colorScore}'>{$rowGetGrades->grade}</span>";
								$grades = "<span class='label label-{$colorScore}'>{$rowGetGrades->status}</span>";
							}
						}
	
						if (is_array($getAttendance)) {
							if (count($getAttendance) == 1) {
								$rowGetAttendance = $getAttendance[0];
								$colorAttendance = ($rowGetAttendance->status == "Hadir") ? 'primary' : 'danger';
			
								$attendance = "<span class='label label-{$colorAttendance}'>{$rowGetAttendance->status}</span>";
							}
						}
					} else {
						$colorScore = ($getDataParticipant->Grade == "Belum Lulus") ? 'warning' : 'success';
						$score  = "<span class='label label-{$colorScore}'>{$getDataParticipant->Score_Grade}</span>";
						$grades = "<span class='label label-{$colorScore}'>{$getDataParticipant->Grade}</span>";

						$colorAttendance = ($getDataParticipant->Attendance == "Hadir") ? 'primary' : 'danger';
						$attendance = "<span class='label label-{$colorAttendance}'>{$getDataParticipant->Attendance}</span>";
					}
				} else {
					$colorScore = ($getDataParticipant->Grade == "Belum Lulus") ? 'warning' : 'success';
					$score  = "<span class='label label-{$colorScore}'>{$getDataParticipant->Score_Grade}</span>";
					$grades = "<span class='label label-{$colorScore}'>{$getDataParticipant->Grade}</span>";

					$colorAttendance = ($getDataParticipant->Attendance == "Hadir") ? 'primary' : 'danger';
					$attendance = "<span class='label label-{$colorAttendance}'>{$getDataParticipant->Attendance}</span>";
				}
			}

			$data[] = array(
				++$no,
				$row->Name,
				$product,
				"Hari : {$row->Training_Day} </br> Tanggal : {$row->Training_Date} - {$row->Training_Time_24}",
				"Kode : {$row->Trainer_Code} </br> Nama : {$row->Trainer_Name}",
				"Tipe : {$row->Room_Type} </br> Ruangan : {$row->Room_Name} </br> Lokasi : {$row->Room_Location}",
				$attendance,
				$score,
				$grades
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->model->count_filtered($position, $nik),
			"recordsFiltered" => $this->model->count_filtered($position, $nik),
			"data" => $data,
		);
		
		echo json_encode($output);
	}
}
