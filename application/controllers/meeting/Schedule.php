<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper(array('url', 'html', 'form'));
		$this->load->model('schedule_meeting_model');
		$this->db2 = $this->load->database('db_user', TRUE);
	}

	function index()
	{
		$data['title'] = 'Schedule';
		$start_date = date('Y-m-01');
		$end_date = date('Y-m-d');
		$this->session->set_userdata('start_date', $start_date);
		$this->session->set_userdata('end_date', $end_date);
		//Load View
		// $this->template->load('template','meeting/schedule/list_schedule');
		$this->template->load('template', 'meeting/schedule/list_schedule', $data);
	}

	function add_new()
	{

		$data['title'] = 'Add Schedule';

		$nik = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');
		$channel = $this->session->userdata('channel');

		$data['query_lokasi'] = $this->schedule_meeting_model->getLokasi();
		$data['position'] = $position;
		$positionDsr = ['DSR', 'SPG', 'SPB', 'Mobile Sales'];

		// if ($position == 'BSH') {
		// 	$data['query_anggota_rsm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'RSM', 'ACTIVE');
		// 	$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'ASM', 'ACTIVE');
		// 	$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'SPV', 'ACTIVE');
		// 	$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('BSH_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		// } elseif ($position == 'RSM') {
		// 	$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'ASM', 'ACTIVE');
		// 	$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'SPV', 'ACTIVE');
		// 	$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('RSM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		// } elseif ($position == 'ASM') {
		// 	$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('ASM_Code', $nik, 'SPV', 'ACTIVE');
		// 	$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('ASM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		// } elseif ($position == 'SPV') {
		// 	$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('SPV_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		// }

		// cekvar($nik);
		// cekvar($position);

		if ($position == 'BSH') {
			$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm($nik);
			
			$data['query_anggota_rsm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'RSM', 'ACTIVE');
			$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'ASM', 'ACTIVE');
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('BSH_Code', $nik, 'ACTIVE', 'Position', $positionDsr);

		} elseif ($position == 'RSM') {
			
			$data['query_atasan_bsh'] = $this->schedule_meeting_model->getAtasanbsh1($nik);
			$get_gm 				  = $data['query_atasan_bsh']->row()->SM_Code;
			$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm1($get_gm);
			
			$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'ASM', 'ACTIVE');
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('RSM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);

		} elseif ($position == 'ASM') {
			
			$data['query_atasan_rsm'] = $this->schedule_meeting_model->getAtasanrsm($nik);

			if (empty($data['query_atasan_rsm']->row()->BSH_Code) || $data['query_atasan_rsm']->row()->BSH_Code == '0') {
				$get_gm = $data['query_atasan_rsm']->row()->SM_Code;
				$get_r  = '';

				$data['query_atasan_bsh'] = $this->schedule_meeting_model->getAtasanbsh($get_r);
				$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm1($get_gm);
			} else {
				$get_r 				  = $data['query_atasan_rsm']->row()->BSH_Code;
				$data['query_atasan_bsh'] = $this->schedule_meeting_model->getAtasanbsh($get_r);
				$get_gm 				  = $data['query_atasan_bsh']->row()->SM_Code;
				$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm1($get_gm);
			}
			
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('ASM_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('ASM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);

		} elseif ($position == 'SPV') {
			$data['query_atasan_asm'] = $this->schedule_meeting_model->getAtasanasm($nik);
			$get_rsm 				= $data['query_atasan_asm']->row()->RSM_Code;
			$data['query_atasan_rsm'] = $this->schedule_meeting_model->getAtasanrsm1($get_rsm);
			if (empty($data['query_atasan_rsm']->row()->BSH_Code) || $data['query_atasan_rsm']->row()->BSH_Code == '0') {
				$get_gm = $data['query_atasan_rsm']->row()->SM_Code;
				$get_r  = '';

				$data['query_atasan_bsh'] = $this->schedule_meeting_model->getAtasanbsh($get_r);
				$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm1($get_gm);
			} else {
				$get_r 				  = $data['query_atasan_rsm']->row()->BSH_Code;
				$data['query_atasan_bsh'] = $this->schedule_meeting_model->getAtasanbsh($get_r);
				$get_gm 				  = $data['query_atasan_bsh']->row()->SM_Code;
				$data['query_atasan_gm']  = $this->schedule_meeting_model->getAtasangm1($get_gm);
			}

			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('SPV_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		
		}

		//Load View
		$this->template->load('template', 'meeting/schedule/add_new', $data);
	}


	function get_dataschedule()
	{

		/*$name = $this->session->userdata('realname');*/
		$nik = $this->session->userdata('sl_code');

		// $date_from = $this->input->post('date_from');
		// $date_to = $this->input->post('date_to');

		// if($date_from !='' && $date_to != ''){
		// 	$date_from = $this->input->post('date_from');
		// 	$date_to = $this->input->post('date_to');
		// }else{
		// 	$date_from = date('Y-m-01');
		// 	$date_to = date('Y-m-d');
		// }

		$mulai = $this->input->post('start_date');
		$sampai = $this->input->post('end_date');
		if (isset($mulai) && isset($sampai)) {
			$mulai = $this->input->post('start_date');
			$sampai = $this->input->post('end_date');
		} else {
			$mulai = date('Y-m-01');
			$sampai = date('Y-m-d');
		}

		/*$query = $this->schedule_meeting_model->get_datatables($name, $mulai, $sampai);*/
		$query = $this->schedule_meeting_model->get_datatables($nik, $mulai, $sampai);
		// cekvar($query);
		// cekdb();

		$data = array();
		$no = $_POST['start'];
		foreach ($query->result() as $row) {

			if($row->Status == 'Open'){
				$action = '<a href="' . site_url('meeting/schedule/editData/' . $row->Schedule_ID) . '" class="btn btn-primary btn-icon btn-circle btn-sm"><i class="fa fa-pencil"></i></a>
				<a href="' . site_url('meeting/update_meeting/' . $row->Schedule_ID) . '" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-book"></i></a>';
			}else{
				$action = '<a href="' . site_url('meeting/update_meeting/' . $row->Schedule_ID) . '" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-book"></i></a>';
			}

			$data[] = array(
				++$no,
				$row->Schedule_Date,
				$row->Schedule_Day,
				$row->Tema,
				
				$row->Schedule_Type,
				$row->Location_Name,
				$row->Link_Meeting,
				$row->Status,
				$action,
			);
		}

		$output = array(
			"draw" => $_POST['draw'],
			/*"recordsTotal" => $this->schedule_meeting_model->count_filtered($name, $mulai, $sampai),
			"recordsFiltered" => $this->schedule_meeting_model->count_filtered($name, $mulai, $sampai),*/
			"recordsTotal" => $this->schedule_meeting_model->count_filtered($nik, $mulai, $sampai),
			"recordsFiltered" => $this->schedule_meeting_model->count_filtered($nik, $mulai, $sampai),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function a($nik, $nama, $no, $position)
	{
		if ($position == '') {
			$Cposition = '';
		} else {
			$Cposition = '("' . $position . '")';
		}
		return '<input type="checkbox" name="anggota[]" value="' . $nik . '-' . $nama . '" id="checkbox' . $no . '"/>' . $nama . $Cposition;
	}

	function get_dataAnggota()
	{
		// print_r('test');
		// die();
		$nik = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');

		// cekvar($position);
		$query = $this->schedule_meeting_model->get_datatables_anggota($nik)->result();
		// cekdb();

		$data = array();
		$no = $_POST['start'];
		foreach ($query as $row) {
			$nik_new = $row->nik;

			if ($position == 'BSH') {
				$checkBox = "";
				$checkBoxAsm = '';
				$checkBoxSpv = "";
				$checkBoxSpg = "";
				$getAsm = $this->schedule_meeting_model->getAnggota($row->nik);
				foreach ($getAsm->result() as $asm) {
					$asmNik = $asm->nik;

					$asmName = $asm->name;

					if ($asm->name == '') {
						$asmName = '';
						$asmNik = '';
						$asmPosition = '';
						$checkBoxAsm .= '';
					} else {
						$asmName = $asm->name;
						$asmNik = $asm->nik;
						if ($asm->position == '') {
							$asmPosition = '';
						} else {
							$asmPosition = '("' . $asm->position . '")';
						}
						$checkBoxAsm .= '<br/><input type="checkbox" name="anggota[]" value="' . $asmNik . '-' . $asmName . '" id="checkbox' . $no . '"/>' . $asmName . $asmPosition;
					}
					$getSpv = $this->schedule_meeting_model->getAnggota($asmNik);
					foreach ($getSpv->result() as $spv) {
						$spvNik = $spv->nik;

						$spvName = $spv->name;

						if ($spv->name == '') {
							$spvName = '';
							$spvNik = '';
							$spvPosition = '';
							$checkBoxSpv .= '';
						} else {
							$spvName = $spv->name;
							$spvNik = $spv->nik;
							if ($spv->position == '') {
								$spvPosition = '';
							} else {
								$spvPosition = '("' . $spv->position . '")';
							}
							$checkBoxSpv .= '<br/><input type="checkbox" name="anggota[]" value="' . $spvNik . '-' . $spvName . '" id="checkbox' . $no . '"/>' . $spvName . $spvPosition;
						}
						$getSpg = $this->schedule_meeting_model->getAnggota($spvNik);

						foreach ($getSpg->result() as $spg) {
							$spgNik = $spg->nik;

							$spgName = $spg->name;

							if ($spg->name == '') {
								$spgName = '';
								$spgNik = '';
								$spgPosition = '';
								$checkBoxSpg .= '';
							} else {
								$spvName = $spg->name;
								$spvNik = $spv->nik;
								if ($spg->position == '') {
									$spgPosition = '';
								} else {
									$spgPosition = '("' . $spg->position . '")';
								}
								$checkBoxSpg .= '<br/><input type="checkbox" name="anggota[]" value="' . $spgNik . '-' . $spgName . '" id="checkbox' . $no . '"/>' . $spgName . $spgPosition;
							}
						}
					}
				}
				$data[] = array(
					++$no,
					$this->a($nik_new, $row->name, $no, $row->position) . $checkBoxAsm . $checkBoxSpv . $checkBoxSpg,
				);
			} elseif ($position == 'RSM') {
				$checkBox = "";
				$checkBoxSpv = "";
				$checkBoxSpg = "";
				$getSpv =  $this->schedule_meeting_model->getAnggota($row->nik);
				foreach ($getSpv->result() as $spv) {
					if ($spv->name == '') {
						$spvName = '';
						$spvNik = '';
						$spvPosition = '';
						$checkBox .= '';
					} else {
						$spvName = $spv->name;
						$spvNik = $spv->nik;
						if ($spv->position == '') {
							$spvPosition = '';
						} else {
							$spvPosition = '("' . $spv->position . '")';
						}
						$checkBoxSpv .= '<br/><input type="checkbox" name="anggota[]" value="' . $spvNik . '-' . $spvName . '" id="checkbox' . $no . '"/>' . $spvName . $spvPosition;
					}
					// $spvNik = $spv->nik;
					// $spvName = $spv->name;

					$getSpg =  $this->schedule_meeting_model->getAnggota($spvNik);
					foreach ($getSpg->result() as $spg) {
						$spgNik = $spg->nik;
						$spgName = $spg->name;

						if ($spg->name == '') {
							$spgName = '';
							$spgNik = '';
							$spgPosition = '';
							$checkBox .= '';
						} else {

							$spgName = $spg->name;
							$spgNik = $spg->nik;
							if ($spg->position == '') {
								$spgPosition = '';
							} else {
								$spgPosition = '("' . $spg->position . '")';
							}
							$checkBoxSpg .= '<br/><input type="checkbox" name="anggota[]" value="' . $spgNik . '-' . $spgName . '" id="checkbox' . $no . '"/>' . $spgName . $spgPosition;
						}
					}
				}
				$data[] = array(
					++$no,
					// $this->a($nik_new,$row->name,$no,$row->position).$checkBox,
					$this->a($nik_new, $row->name, $no, $row->position) . $checkBoxSpv . $checkBoxSpg,

				);
			} elseif ($position == 'ASM') {
				// $a=array();
				// array_push($a,"blue","yellow");
				$checkBox = "";
				// $a="2";
				// cekvar($nik_new);
				$getSpg =  $this->schedule_meeting_model->getAnggota($nik_new);
				foreach ($getSpg->result() as $spg) {
					// $spgNik = $spg->nik;
					// $spgName = $spg->name;
					if ($spg->name == '') {
						$spgName = '';
						$spgNik = '';
						$spgPosition = '';
						$checkBox .= '';
					} else {

						$spgName = $spg->name;
						$spgNik = $spg->nik;
						if ($spg->position == '') {
							$spgPosition = '';
						} else {
							$spgPosition = '("' . $spg->position . '")';
						}
						$checkBox .= '<br/><input type="checkbox" name="anggota[]" value="' . $spgNik . '-' . $spgName . '" id="checkbox' . $no . '"/>' . $spgName . $spgPosition;
					}
				}

				$data[] = array(
					++$no,
					// $this->a($nik_new,$row->name,$no,$row->position).$checkBox,
					$this->a($nik_new, $row->name, $no, $row->position) . $checkBox,

				);
			}
			// $data[] = array(
			// 	++$no,
			// 	$row->Schedule_Date,
			// 	$row->Schedule_Day,
			// 	$row->Schedule_Type,
			// 	$row->Location_Name,
			// 	$row->Link_Meeting,
			// 	$row->Status,
			// 	'<a href="' . site_url('meeting/schedule/editData/' . $row->Schedule_ID) . '" class="btn btn-primary btn-icon btn-circle btn-sm"><i class="fa fa-pencil"></i></a>
			// 	<a href="' . site_url('meeting/update_meeting/' . $row->Schedule_ID) . '" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-book"></i></a>',

			// );
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->schedule_meeting_model->count_filtered_anggota($nik),
			"recordsFiltered" => $this->schedule_meeting_model->count_filtered_anggota($nik),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function save2()
	{
		// $post = $this->input->post();
		// echo "<pre>";
		// print_r($post);
		// echo "</pre>";
		// echo "<hr>";
		$schedule_type = $this->input->post('schedule_type');
		$tema = $this->input->post('tema');

		$anggotas = $this->input->post('anggota');
		$opsionals = $this->input->post('opsional');

		// $this->load->library('form_validation');
		if ($schedule_type == "online") {
			$link_meeting = $this->input->post('link_meeting');
			$training_date = $this->input->post('training_date');
			$training_days = $this->getDay($training_date);
			$training_time = $this->input->post('training_time');

			if ($link_meeting == '' || $training_date == '' || $training_time == '') {
				$this->session->set_flashdata('message', 'Mohon isi semua form schedule.');
				redirect('meeting/schedule/add_new');
			}

			if ($anggotas == '') {
				$this->session->set_flashdata('error', 'Mohon isi anggota.');
				redirect('meeting/schedule/add_new');
			}


			$request = array(
				'Schedule_Type' => $schedule_type,
				'Tema'          => $tema,
				'Schedule_Day' => $training_days,
				'Schedule_Date' => $training_date,
				'Schedule_Time' => $training_time,
				'Created_By' => $this->session->userdata('realname'),
				'Created_By_ID' => $this->session->userdata('sl_code'),
				'Created_Date' => date('Y-m-d H:i:s'),
				'Status' => "Open",
				'Link_Meeting' => $link_meeting,
			);
		} else {
			$lokasi = $this->input->post('lokasi');
			$training_date = $this->input->post('training_date');
			$training_days = $this->getDay($training_date);
			$training_time = $this->input->post('training_time');

			if ($lokasi == '' || $training_date == '' || $training_time == '') {
				$this->session->set_flashdata('error', 'Mohon isi semua form schedule.');
				redirect('meeting/schedule/add_new');
			}

			if ($anggotas == '') {
				$this->session->set_flashdata('error', 'Mohon isi anggota.');
				redirect('meeting/schedule/add_new');
			}

			// $elokasi = explode("-", $lokasi);
			// $idlokasi = $elokasi[0];
			// $namelokasi = $elokasi[1];

			$request = array(
				'Schedule_Type' => $schedule_type,
				'Tema'          => $tema,
				// 'Location_ID' => $idlokasi,
				'Location_Name' => $lokasi,
				// 'Location_Name' => $namelokasi,
				'Schedule_Day' => $training_days,
				'Schedule_Date' => $training_date,
				'Schedule_Time' => $training_time,
				'Created_By' => $this->session->userdata('realname'),
				'Created_By_ID' => $this->session->userdata('sl_code'),
				'Created_Date' => date('Y-m-d H:i:s'),
				'Status' => "Open",
			);
		}

		// cekvar($request);
		// die();

		$this->db->trans_start();
		$this->schedule_meeting_model->insert_schedule($request);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		// $deptValue = [];
		$anggota=array();
		foreach ($anggotas as $key => $a) {
			// cekvar($opsional);
			
			$splitData = explode("-", $a);
			$nikAnggota = $splitData[0];
			$namaAnggota = $splitData[1];

			// array_push($deptValue, $namaAnggota);
			$anggota = array(
				'Schedule_ID'	=> $id,
				'NIK' => $nikAnggota,
				'Name' => $namaAnggota,
				// 'opsional' => $opsional,
				'created_date' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('realname'),
				'created_by_id' => $this->session->userdata('sl_code'),
			);


			$anggota['opsional'] = array_key_exists($nikAnggota,$opsionals)?$opsionals[$nikAnggota]:"";
			
			// echo "<pre>";
			// echo array_key_exi$opsionals[$nikAnggota]."===";
			// echo "</pre><br>";

			$this->db->trans_start();
			$this->schedule_meeting_model->insert_data_participant($anggota);
			$this->db->trans_complete();
		}

		
		// cekvar($anggota);

		// foreach ($anggotas as $a) {
		// 	$splitData = explode("-", $a);
		// 	$nikAnggota = $splitData[0];
		// 	$namaAnggota = $splitData[1];

		// 	// array_push($deptValue, $namaAnggota);
		// 	$anggota = array(
		// 		'Schedule_ID'	=> $id,
		// 		'NIK' => $nikAnggota,
		// 		'Name' => $namaAnggota,
		// 		'created_date' => date('Y-m-d H:i:s'),
		// 		'created_by' => $this->session->userdata('realname'),
		// 		'created_by_id' => $this->session->userdata('sl_code'),
		// 	);

		// 	$this->db->trans_start();
		// 	$this->schedule_meeting_model->insert_data_participant($anggota);
		// 	$this->db->trans_complete();
		// }

		$this->session->set_flashdata('message', 'Data berhasil disimpan.');
		redirect('meeting/schedule');
	}

	function editData($Schedule_ID)
	{
		$data['title'] = 'Edit Schedule';
		// $nik = $this->session->userdata('nik');
		$nik = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');
		$data['position'] = $position;
		$positionDsr = ['DSR', 'SPG', 'SPB', 'Mobile Sales'];
		// // $data['trainer'] = $this->schedule_model->getTrainer();
		$data['schedule'] = $this->schedule_meeting_model->getScheduleId($Schedule_ID);
		// $data['query_anggota'] = $this->schedule_meeting_model->getAnggota($nik);

		if ($position == 'BSH') {
			$data['query_anggota_rsm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'RSM', 'ACTIVE');
			// cekdb();
			$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'ASM', 'ACTIVE');
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('BSH_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('BSH_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		} elseif ($position == 'RSM') {
			$data['query_anggota_asm'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'ASM', 'ACTIVE');
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('RSM_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('RSM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		} elseif ($position == 'ASM') {
			$data['query_anggota_spv'] = $this->schedule_meeting_model->getAnggota('ASM_Code', $nik, 'SPV', 'ACTIVE');
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('ASM_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
			// cekvar($data['query_anggota_spv']->result());
		} elseif ($position == 'SPV') {
			$data['query_anggota_dsr'] = $this->schedule_meeting_model->getDsr('SPV_Code', $nik, 'ACTIVE', 'Position', $positionDsr);
		}

		$data['query_lokasi'] = $this->schedule_meeting_model->getLokasi();
		$data['participants'] = $this->schedule_meeting_model->getParticipantId($Schedule_ID);

		$this->template->load('template', 'meeting/schedule/edit', $data);
	}


	function update_schedule($Schedule_ID)
	{
		// cekvar($Schedule_ID);

		$schedule_type = $this->input->post('schedule_type');
		$anggotas = $this->input->post('anggota');
		$tema = $this->input->post('tema');

		// $this->load->library('form_validation');
		if ($schedule_type == "online") {


			$link_meeting = $this->input->post('link_meeting');
			$training_date = $this->input->post('training_date');
			$training_days = $this->getDay($training_date);
			$training_time = $this->input->post('training_time');

			if ($link_meeting == '' || $training_date == '' || $training_time == '') {
				$this->session->set_flashdata('error', 'Mohon isi semua form schedule.');
				redirect('meeting/schedule/editData/' . $Schedule_ID);
			}

			$request = array(
				'Location_ID' => '',
				'Location_Name' => '',
				'Tema'          => $tema,
				'Schedule_Type' => $schedule_type,
				'Schedule_Day' => $training_days,
				'Schedule_Date' => $training_date,
				'Schedule_Time' => $training_time,
				// 'Created_By' => $this->session->userdata('realname'),
				// 'Created_Date' => date('Y-m-d'),
				'Status' => "Open",
				'Link_Meeting' => $link_meeting,
			);
		} else {
			$lokasi = $this->input->post('lokasi');
			$training_date = $this->input->post('training_date');
			$training_days = $this->getDay($training_date);
			$training_time = $this->input->post('training_time');

			if ($lokasi == '' || $training_date == '' || $training_time == '') {
				$this->session->set_flashdata('error', 'Mohon isi semua form schedule.');
				redirect('meeting/schedule/editData/' . $Schedule_ID);
			}

			$elokasi = explode("-", $lokasi);
			$idlokasi = $elokasi[0];
			$namelokasi = $elokasi[1];

			$request = array(
				'Schedule_Type' => $schedule_type,
				'Tema'          => $tema,
				// 'Location_ID' => $idlokasi,
				'Location_Name' => $lokasi,
				// 'Location_Name' => $namelokasi,
				'Schedule_Day' => $training_days,
				'Schedule_Date' => $training_date,
				'Schedule_Time' => $training_time,
				// 'Created_By' => $this->session->userdata('realname'),
				// 'Created_Date' => date('Y-m-d'),
				'Link_Meeting' => '',
				'Status' => "Open",
			);
		}

		// cekvar($request);
		// die();

		$this->schedule_meeting_model->update_schedule($request, $Schedule_ID);
		$this->schedule_meeting_model->deleteParticipant($Schedule_ID);

		if ($anggotas == '') {
			$this->session->set_flashdata('error', 'Mohon isi anggota.');
			redirect('meeting/schedule/editData/' . $Schedule_ID);
		}

		foreach ($anggotas as $a) {
			$splitData = explode("-", $a);
			$nikAnggota = $splitData[0];
			$namaAnggota = $splitData[1];

			$anggota = array(
				'Schedule_ID'	=> $Schedule_ID,
				'NIK' => $nikAnggota,
				'Name' => $namaAnggota,
				'created_date' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('realname'),
				'created_by_id' => $this->session->userdata('sl_code'),
			);

			$this->db->trans_start();
			$this->schedule_meeting_model->insert_data_participant($anggota);
			$this->db->trans_complete();
		}

		$this->session->set_flashdata('message', 'Data berhasil diupdate.');
		redirect('meeting/schedule');


		// $trainer_code = $this->input->post('trainer');
		// $trainer_name_ = $this->schedule_model->getNameTrainer($trainer_code);
		// $tn = $trainer_name_->row();
		// $update_schedule = array(
		// 	'Trainer_Code'		=>$trainer_code,
		// 	'Trainer_Name'		=>$tn->name,
		// 	'Status'			=>$this->input->post('status')
		// );
		// $this->schedule_model->update_jadwal($update_schedule, $Schedule_ID);

		// $update_module = array(
		// 	'Module_ID'	=>$this->input->post('module')
		// );
		// $this->schedule_model->update_module($update_module, $Schedule_ID);

		// $this->session->set_flashdata('message', 'Data berhasil disimpan.');
		// redirect('schedule/list_schedule');
	}

	function save($training_date)
	{
		$training_days = $this->getDay($training_date);
		$trainer_code = $this->input->post('trainer_code');
		$trainer_name_ = $this->schedule_meeting_model->getNameTrainer($trainer_code)->row();
		$trainer_name = $trainer_name_->name;

		$request = array(
			'Room_ID'		=> $this->input->post('room'),
			'Training_Day'	=> $training_days,
			'Training_Date'	=> $training_date,
			'Training_Time'	=> $this->input->post('training_time'),
			'Trainer_Code'	=> $trainer_code,
			'Trainer_Name'	=> $trainer_name,
			'Status'		=> 'ACTIVE'
		);
		$this->db->trans_start();
		$this->schedule_meeting_model->insert_schedule($request);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		//save module
		$modul = $this->input->post('module');
		foreach ($modul as $key => $value) {
			$moduls = array(
				'Module_ID'		=> $value,
				'Schedule_ID'	=> $id
			);
			$this->schedule_meeting_model->insert_data_module($moduls);
		}

		$this->session->set_flashdata('message', 'Data berhasil disimpan.');
		redirect('schedule/detail_schedule/' . $training_date);
	}

	function getDay($date)
	{
		$day = date('D', strtotime($date));

		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);

		return $dayList[$day];
	}

	function add_fix_schedule()
	{
		$data['sql'] = $this->schedule_model->getFixSchedule();
		//Load View
		$this->template->load('template', 'schedule/fix_schedule', $data);
	}



	function validation_online()
	{
		//$this->form_validation->set_rules('Join_Date', 'Employee Type', 'required');
		$this->form_validation->set_rules('link_meeting', 'Link Meeting', 'required');
		$this->form_validation->set_rules('training_date', 'Date', 'required');
		$this->form_validation->set_rules('training_time', 'Time', 'required');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	}

	function validation_offline()
	{
		//$this->form_validation->set_rules('Join_Date', 'Employee Type', 'required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
		$this->form_validation->set_rules('training_date', 'Date', 'required');
		$this->form_validation->set_rules('training_time', 'Time', 'required');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	}
}
