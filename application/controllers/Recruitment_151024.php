<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'third_party/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Recruitment extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('auth', 'template', 'unit_test', 'form_validation'));
		$this->load->model('Recruitment_model', 'rm');
		$this->position = $this->session->userdata('position');
	}


	function index()
	{
		if (in_array($this->position, ['SPV', 'ASM', 'RSM', 'BSH'])) {

			$this->template->set('title', 'Data Pelamar');
			$this->template->load('template', 'recruitment/index');

			// 	$result = true;
			// } catch (Exception $e) {
			// 	$result = false;
			// }


			// $this->unit->run($result, "is_true", "Unit Testing filter");
			// echo $this->unit->report();
		} else {
			redirect('');
		}
	}


	function get_data()
	{

		// $this->unit->run($query, "is_array", "Unit Testing Show Data CCMS");
		// echo $this->unit->report();

		$data = array();
		$no = $this->input->post('start');
		$query = $this->rm->get_datatables();

		// cekvar($query);	
		// $positionnn = $this->session->userdata('position');
		$positionnn = $this->session->userdata('sl_code');


		foreach ($query as $row) {
			$status = ($row->Hit_Code = 13) ? $row->Status . ' ' . $row->position_sales : $row->Activity;
			$noteReturn = ($row->Reason == null) ? '' : '<b>Note Return : </b><label class="btn btn-xs btn-warning">' . $row->Reason . '</label>';
			$data[] = array(
				++$no,
				"
				<b>ID Pelamar : </b> $row->Recruitment_ID </br>
				<b>Nama Pelamar : </b> $row->Name </br>
				<b>Area : </b> $row->Branch </br>
				<b>Produk : </b> $row->Product </br>
				<b>Posisi : </b> $row->Position </br>
				<b>Level : </b> $row->Level </br>
				<b>Upliner (Nama - NIK) : </b> $row->SM_Name - $row->SM_Code</br>
				<b>Status : </b> <label class='btn btn-xs btn-info'>$status</label></br>
				$noteReturn
				",
			);
		}


		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->rm->count_filtered(),
			"recordsFiltered" => $this->rm->count_filtered(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function add()
	{
		$position = $this->session->userdata('position');

		if (in_array($position, ['SPV', 'ASM', 'RSM', 'BSH'])) {
			$code = $position . "_Code";
			$nik  = $this->session->userdata('sl_code');
			$realname = $this->session->userdata('realname');
			$product = $this->session->userdata('product');

			// $this->unit->run($this->rm->listSpv($code, $nik), 'is_array', 'UNIT TESTING LIST DATA UPLINER');
			// echo $this->unit->report();
			// die;

			$this->validation();
			if ($this->form_validation->run() == false) {
				$data = [
					'area' 		=> $this->rm->kebutuhan()->data->area,
					'produk' 	=> $this->rm->kebutuhan()->data->produk,
					'posisi' 	=> $this->rm->kebutuhan()->data->posisi,
					'level' 	=> $this->rm->kebutuhan()->data->level,
					'list_spv' 	=> $this->rm->listSpv($code, $nik),
					'position' 	=> $position,
					'nik' 		=> $nik,
					'realname' 	=> $realname,
					'product' 	=> $product
				];

				$this->template->set('title', 'Data Pelamar Detail');
				$this->template->load('template', 'recruitment/edit', $data);
			} else {

				$id2 		= $this->input->post('id');
				$area 		= $this->input->post('area'); //Area_Applying
				$produk 	= $this->input->post('produk'); //Product
				$posisi 	= $this->input->post('posisi'); //Position
				$level 		= $this->input->post('level'); //Level
				$sm_code 	= $this->input->post('spv_code');
				$sm_name 	= $this->input->post('spv_name');
				$asm_code 	= $this->input->post('asm_code');
				$asm_name 	= $this->input->post('asm_name');
				$rsm_code 	= $this->input->post('rsm_code');
				$rsm_name 	= $this->input->post('rsm_name');
				$bsh_code 	= $this->input->post('bsh_code');
				$bsh_name 	= $this->input->post('bsh_name');

				$cekDuplikatInput = $this->rm->cekDuplikat($id2);

				$cek = is_object($cekDuplikatInput->data) ? 1 : 0;
				if ($cek == 1) {
					$this->session->set_flashdata('message', 'Data Gagal Disimpan, ID pelamar sudah pernah di input oleh ' . $cekDuplikatInput->data->Name);
					redirect('recruitment');
				} else {
					$getSMCode = $this->rm->smCode($nik);
					$smCode = $getSMCode->data->DSR_Code;
					$smName = $getSMCode->data->Name;
					$smPosition = $getSMCode->data->Position;

					$nik_r = $this->session->userdata('sl_code');
					$position_r = $this->session->userdata('position');

					// kondisi jika BSH : 13, status mssales = APPROVE
					if ($position_r == 'BSH') {

						$hit_code = 9;
						$status = 'APPROVE';
						$data = [
							'id'			=> $id2,
							'area' 			=> $area,
							'produk'		=> $produk,
							'posisi'		=> $posisi,
							'level'			=> $level,
							'sm_code'		=> $sm_code,
							'sm_name'		=> $sm_name,
							'asm_code'		=> $asm_code,
							'asm_name'		=> $asm_name,
							'rsm_code'		=> $rsm_code,
							'rsm_name'		=> $rsm_name,
							'bsh_code'		=> $bsh_code,
							'bsh_name'		=> $bsh_name,
							'hit_code'		=> $hit_code, //bsh
							'updated_by'	=> $this->session->userdata('realname'),

						];
						$update = $this->rm->update($data);

						//api get data_employee

						$data_approval_msales = [
							'id'			=> $id2,
							'NIK' 			=> $smCode,
							'Name'			=> $smName,
							'Position'		=> $smPosition,
							'Updated_By'	=> $this->session->userdata('realname'),
							'nik_r'         => $nik_r,
							'position_r' 	=> $position_r,
							'status'		=> $status
						];
						$this->rm->insert_approval_msales($data_approval_msales);
					} else {
						$data = [
							'id'			=> $id2,
							'area' 			=> $area,
							'produk'		=> $produk,
							'posisi'		=> $posisi,
							'level'			=> $level,
							'sm_code'		=> $sm_code,
							'sm_name'		=> $sm_name,
							'asm_code'		=> $asm_code,
							'asm_name'		=> $asm_name,
							'rsm_code'		=> $rsm_code,
							'rsm_name'		=> $rsm_name,
							'bsh_code'		=> $bsh_code,
							'bsh_name'		=> $bsh_name,
							'updated_by'	=> $this->session->userdata('realname'),

						];
						$update = $this->rm->update($data);


						$dataUpliner = $this->rm->smCode($nik); //DSR_Code, Name, Position

						$smNIK = $dataUpliner->data->DSR_Code;
						$smName = $dataUpliner->data->Name;
						$smPosition = $dataUpliner->data->Position;

						$data_approval_msales = [
							// 'NIK' 			=> $smCode,
							'id'			=> $id2,
							'NIK' 			=> $smNIK,
							'Name'			=> $smName,
							'Position'		=> $smPosition,
							'Updated_By'	=> $this->session->userdata('realname'),
							'nik_r'         => $nik_r,
							'position_r' 	=> $position_r,
						];

						$this->rm->insert_approval_msales($data_approval_msales);
					}

					if ($update) {
						$this->session->set_flashdata('message', 'Berhasil Input Data Pelamar!');
						redirect('recruitment');
					} else {
						$this->session->set_flashdata('message', 'Gagal Input Data Pelamar!');
						redirect('recruitment');
					}
				}
			}
		} else {
			redirect('');
		}
	}

	function detail($id)
	{
		$position = $this->session->userdata('position');

		if (in_array($position, ['SPV', 'ASM', 'RSM', 'BSH'])) {
			$this->validation();
			if ($this->form_validation->run() == false) {
				$data = [
					'id_pelamar' => $id,
					'area' => $this->rm->kebutuhan($id)->data->area,
					'produk' => $this->rm->kebutuhan($id)->data->produk,
					'posisi' => $this->rm->kebutuhan($id)->data->posisi,
					'level' => $this->rm->kebutuhan($id)->data->level,
					'list_spv' => $this->rm->kebutuhan($id)->data->list_spv,
					'nama' => $this->rm->kebutuhan($id)->data->detail,
				];

				$this->template->set('title', 'Data Pelamar Detail');
				$this->template->load('template', 'recruitment/edit', $data);
			} else {

				// var_dump($data);
				// die;		
				$id2 = $this->input->post('id');
				$area = $this->input->post('area'); //Area_Applying
				$produk = $this->input->post('produk'); //Product
				$posisi = $this->input->post('posisi'); //Position
				$level = $this->input->post('level'); //Level
				$sm_code = $this->input->post('spv');
				$sm_name = $this->input->post('spv_name');
				$asm_code = $this->input->post('asm_code');
				$asm_name = $this->input->post('asm_name');
				$rsm_code = $this->input->post('rsm_code');
				$rsm_name = $this->input->post('rsm_name');
				$bsh_code = $this->input->post('bsh_code');
				$bsh_name = $this->input->post('bsh_name');

				$data = [
					'id'			=> $id2,
					'area' => $area,
					'produk'		=> $produk,
					'posisi'		=> $posisi,
					'level'			=> $level,
					'sm_code'		=> $sm_code,
					'sm_name'		=> $sm_name,
					'asm_code'		=> $asm_code,
					'asm_name'		=> $asm_name,
					'rsm_code'		=> $rsm_code,
					'rsm_name'		=> $rsm_name,
					'bsh_code'		=> $bsh_code,
					'bsh_name'		=> $bsh_name,
				];
				$update = $this->rm->update($data);


				if ($update) {
					$this->session->set_flashdata('message', 'Berhasil Update Data Pelamar!');
					redirect('recruitment');
					// $result = true;
				} else {
					$this->session->set_flashdata('message', 'Gagal Update Data Pelamar!');
					redirect('recruitment');
					// $result = false;
				}

				// $this->unit->run($result, 'is_true', 'Unit Testing Data Pelamar');
				// echo $this->unit->report();
			}
		} else {
			redirect('');
		}
	}

	function cekPelamar($id)
	{
		$cek = $this->rm->getDetailPelamar($id)->data;
		echo json_encode($cek);
	}

	function cekSPV($nik)
	{
		$position = $this->session->userdata('position');
		$nik_sess = $this->session->userdata('sl_code');
		$name_sess = $this->session->userdata('realname');

		$m = $this->rm->dataSalesCopy($nik)->data;
		$sm_code = $m->DSR_Code;
		$sm_name = $m->Name;

		// CODE
		$asm_code = $position == 'ASM' ? $nik_sess : $m->ASM_Code;
		$rsm_code = $position == 'RSM' ? $nik_sess : $m->RSM_Code;
		$bsh_code = $position == 'BSH' ? $nik_sess : $m->BSH_Code;

		// NAME
		$asm_name = $position == 'ASM' ? $name_sess : $m->ASM_Name;
		$rsm_name = $position == 'RSM' ? $name_sess : $m->RSM_Name;
		$bsh_name = $position == 'BSH' ? $name_sess : $m->BSH_Name;

		// $asm_name = $m->ASM_Name;
		// $rsm_code = $m->RSM_Code;
		// $rsm_name = $m->RSM_Name;
		// $bsh_code = $m->BSH_Code;


		$bsh_name = $m->BSH_Name;
		$data = [
			'SM_Code'		=> $sm_code,
			'SM_Name'		=> $sm_name,
			'ASM_Code'		=> $asm_code,
			'ASM_Name'		=> $asm_name,
			'RSM_Code'		=> $rsm_code,
			'RSM_Name'		=> $rsm_name,
			'BSH_Code'		=> $bsh_code,
			'BSH_Name'		=> $bsh_name,
		];

		echo json_encode($data);
	}

	private function validation()
	{
		$this->form_validation->set_rules('id', 'ID Pelamar', 'required', [
			'required' => 'ID Pelamar wajib diisi!'
		]);
		$this->form_validation->set_rules('namaPelamar', 'Nama Pelamar', 'required', [
			'required' => 'Nama Pelamar wajib diisi!'
		]);
		$this->form_validation->set_rules('area', 'Area', 'required', [
			'required' => 'Area wajib diisi!'
		]);
		$this->form_validation->set_rules('produk', 'Produk', 'required', [
			'required' => 'Produk wajib diisi!'
		]);
		$this->form_validation->set_rules('posisi', 'Posisi', 'required', [
			'required' => 'Posisi wajib diisi!'
		]);
		$this->form_validation->set_rules('level', 'Level', 'required', [
			'required' => 'Level wajib diisi!'
		]);
		$this->form_validation->set_rules('spv', 'Nama SPV', 'required', [
			'required' => 'Nama SPV wajib diisi!'
		]);
	}
}
