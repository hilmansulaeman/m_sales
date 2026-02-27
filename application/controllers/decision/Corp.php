<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 
 */
class Corp extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		//load library dan helper yg dibutuhkan
		$this->load->library('template');
		$this->load->helper(array('url', 'html'));
		$this->load->model('decision/corp_model');
	}

	function index()
	{
		$sales_code = $this->session->userdata('sl_code'); //DSR_Code
		$position 	= $this->session->userdata('position');
		$date_filter = $this->input->post('date');
		if (isset($date_filter)) {
			$this->session->set_userdata('groupDate', $date_filter);
		} else {
			$period = $this->corp_model->get_last_period();
			$group_date = $period->num_rows() == 0 ? date('Y-m') : $period->row()->group_date;
			$this->session->set_userdata('groupDate', $group_date);
		}
		$groupDate 	= $this->session->userdata('groupDate');

		if ($position == 'BSH') {
			$data['position'] = "RSM";
			$data['detail'] = "ASM";
		} else if ($position == 'RSM') {
			$data['position'] = "ASM";
			$data['detail'] = "SPV";
		} else if ($position == 'ASM') {
			$data['position'] = "SPV";
			$data['detail'] = "DSR";
		} else if ($position == 'SPV') {
			$data['position'] = "DSR";
			$data['detail'] = "DSR";
		} else {
			$data['position'] = "DSR";
		}

		if ($position == 'DSR') {
			$views = 'index_dsr';

			$data['breakdown_corp'] = $this->corp_model->breakdown_corp_dsr($sales_code, $groupDate);
			$data['sales_code'] = $sales_code;
		} else {
			$views = 'index';
		}
		//load view
		$this->template->set('title', 'Data Decision CORP');
		$this->template->load('template', 'decision/corp/' . $views, $data);
	}

	// function det_breakdown_corp($status, $tgl)
	// {
	// 	$sales_code = $this->session->userdata('sl_code');
	// 	$posisi = $this->session->userdata('position');
	// 	$varPos = "";
	// 	if($posisi == "DSR" or $posisi == "SPG" or $posisi == "SPB")
	// 	{
	// 		$varPos = "DSR_Code";
	// 	}elseif($posisi == "SPV")
	// 	{
	// 		$varPos = "SPV_Code";
	// 	}elseif($posisi == "ASM")
	// 	{
	// 		$varPos = "ASM_Code";
	// 	}elseif($posisi == "RSM")
	// 	{
	// 		$varPos = "RSM_Code";
	// 	}elseif($posisi == "BSH" OR $posisi == "ASH")
	// 	{
	// 		$varPos = "BSH_Code";
	// 	}
	// 	$data['query'] = $this->corp_model->getBreakdownCorp($sales_code, $varPos, $status, $tgl);

	// 	//load view
	// 	$this->load->view('decision/corp/breakdown_corp', $data);
	// }

	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
		} else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
			$groups = "ASM_Code";
		} else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
			$groups = "SPV_Code";
		} else {
			$where = "SPV_Code = '$nik'";
			$groups = "Sales_Code";
		}
		$query = $this->corp_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row) {
			if ($position == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$var = 'RSM_Code';
			} else if ($position == 'RSM') {
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$var = 'ASM_Code';
			} else if ($position == 'ASM') {
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$var = 'SPV_Code';
			} else {
				$sales_code = $row->sales_code;
				$sales_name = $row->sales_name;
				$var = 'Sales_Code';
			}

			$sales_position = substr($var, 0, -5);

			$getActualStatus = $this->corp_model->breakdown_corp($sales_code, $groupDate);

			$data[] = array(
				++$no,
				$sales_code . ', ' . $sales_name . ' (' . $sales_position . ')',
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $sales_code . '\',\'APPROVE\',\'' . $sales_name . '\')"><span title="Total Accept Approve" class="badge bg-green">' . $getActualStatus->approve_corp . '</span></a>',
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $sales_code . '\',\'CANCEL\',\'' . $sales_name . '\')"><span title="Total Accept Cancel" class="badge bg-red">' . $getActualStatus->cancel_corp . '</span></a>',
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $sales_code . '\',\'DECLINE\',\'' . $sales_name . '\')"><span title="Total Accept Decline" class="badge bg-black">' . $getActualStatus->decline_corp . '</span></a>',
				'<a href="javascript:void(0);" onclick="view_spv(\'' . $sales_code . '\',\'' . $sales_position . '\',\'' . $sales_name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->corp_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->corp_model->count_filtered($where, $groups, $groupDate),
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
		$this->load->view('decision/corp/detailSPV');
	}

	function detailActual($sales, $status)
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');

		$data['query'] = $this->corp_model->getBreakdownCorp($sales, $status, $groupDate);
		$data['status'] = $status;

		$this->load->view('decision/corp/breakdown_corp', $data);
	}

	function detailActualLink($sales, $status, $name)
	{
		$name = str_replace('-', ' ', $name);

		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');

		$data['query'] = $this->corp_model->getBreakdownCorp($sales, $status, $groupDate);
		$data['status'] = $status;

		$this->template->set('title', 'Detail Decision Corp');
		$this->template->load('template', 'decision/corp/breakdown_corp_link', $data);
	}

	function get_data_spv()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
		$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
		$user 		= $this->session->userdata('sl_code'); // id yang sedang login
		$groupDate 	= $this->session->userdata('groupDate');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		/*if($pos == 'BSH'){
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
		}*/
		if ($pos == 'RSM') {
			$where = "RSM_Code = '$nik' AND BSH_Code = '$user'";
			$groups = "ASM_Code";
		} else if ($pos == 'ASM') {
			$where = "ASM_Code = '$nik' AND (RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "SPV_Code";
		} else {
			$where = "SPV_Code = '$nik' AND (ASM_Code = '$user' OR RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "Sales_Code";
		}

		$query = $this->corp_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row) {
			if ($pos == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$upliner = $row->BSH_Code;
				$var  = 'RSM_Code';
				$var2 = 'BSH_Code';
			} else if ($pos == 'RSM') {
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$upliner = $row->RSM_Code;
				$var  = 'ASM_Code';
				$var2 = 'RSM_Code';
			} else if ($pos == 'ASM') {
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$upliner = $row->ASM_Code;
				$var  = 'SPV_Code';
				$var2 = 'ASM_Code';
			} else {
				$sales_code = $row->Sales_Code;
				$sales_name = $row->Sales_Name;
				$upliner = $row->SPV_Code;
				$var  = 'Sales_Code';
				$var2 = 'SPV_Code';
			}

			$sales_position = substr($var, 0, -5);

			$getActualStatus = $this->corp_model->breakdown_corp_detail($sales_code, $var2, $upliner, $groupDate);

			if (in_array($sales_position, $array_structure)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\'' . $sales_code . '\',\'' . $sales_position . '\',\'' . $sales_name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
				';
			} else {
				$buttons = '';
			}

			$data[] = array(
				++$no,
				$sales_code . ', ' . $sales_name . ' (' . $sales_position . ')',
				'<a href="' . site_url('decision/corp/detailActualLink/' . $sales_code . '/APPROVE/' . str_replace(' ', '-', $sales_name)) . '" target="_blank"><span title="Total Accept Approve" class="badge bg-green">' . $getActualStatus->approve_corp . '</span></a>',
				'<a href="' . site_url('decision/corp/detailActualLink/' . $sales_code . '/CANCEL/' . str_replace(' ', '-', $sales_name)) . '" target="_blank"><span title="Total Accept Cancel" class="badge bg-red">' . $getActualStatus->cancel_corp . '</span></a>',
				'<a href="' . site_url('decision/corp/detailActualLink/' . $sales_code . '/DECLINE/' . str_replace(' ', '-', $sales_name)) . '" target="_blank"><span title="Total Accept Decline" class="badge bg-black">' . $getActualStatus->decline_corp . '</span></a>',
				$buttons
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->corp_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->corp_model->count_filtered($where, $groups, $groupDate),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}
	
	function style_col()
	{
		return [
			'font' => ['bold' => true], // Set font nya jadi bold
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];
	}
	function style_row()
	{
		return [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];
	}

	function export()
	{

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style_col = $this->style_col();
		// $style_col2 = $this->style_col2();
		$style_row = $this->style_row();
		$sheet->setCellValue('A1', "Sales Code");
		$sheet->setCellValue('B1', "Sales Name");
		$sheet->setCellValue('C1', "SPV Code");
		$sheet->setCellValue('D1', "SPV Name");
		$sheet->setCellValue('E1', "ASM Code");
		$sheet->setCellValue('F1', "ASM Name");
		$sheet->setCellValue('G1', "RSM Code");
		$sheet->setCellValue('H1', "RSM NAme");
		$sheet->setCellValue('I1', "BSH Code");
		$sheet->setCellValue('J1', "BSH Name");
		$sheet->setCellValue('K1', "Branch");
		$sheet->setCellValue('L1', "Customer Name");
		$sheet->setCellValue('M1', "Status");
		$sheet->setCellValue('N1', "Week");
		//$sheet->setCellValue('O1', "Plafon");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);
		$sheet->getStyle('E1')->applyFromArray($style_col);
		$sheet->getStyle('F1')->applyFromArray($style_col);
		$sheet->getStyle('G1')->applyFromArray($style_col);
		$sheet->getStyle('H1')->applyFromArray($style_col);
		$sheet->getStyle('I1')->applyFromArray($style_col);
		$sheet->getStyle('J1')->applyFromArray($style_col);
		$sheet->getStyle('K1')->applyFromArray($style_col);
		$sheet->getStyle('L1')->applyFromArray($style_col);
		$sheet->getStyle('M1')->applyFromArray($style_col);
		$sheet->getStyle('N1')->applyFromArray($style_col);
		//$sheet->getStyle('O1')->applyFromArray($style_col);
		//ambil data
		$groupDate 	= $this->session->userdata('groupDate');

		$query = $this->corp_model->getBreakdownCorpsexport($groupDate);

		//validasi jumlah data
		if ($query->num_rows() == 0) { ?>
			<script type="text/javascript" language="javascript">
				alert("No data...!!!");
			</script>
		<?php
			echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "decision/pl'>";

			return false;
		} else {
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
			foreach ($query->result() as $data) { // Lakukan looping pada variabel sn
				$maskingname = $this->maskingname($data->customer_name);


				$sheet->setCellValue('A' . $numrow,  $data->sales_code);
				$sheet->setCellValue('B' . $numrow, $data->sales_name);
				$sheet->setCellValue('C' . $numrow, $data->SPV_Code);
				$sheet->setCellValue('D' . $numrow, $data->SPV_Name);
				$sheet->setCellValue('E' . $numrow, $data->ASM_Code);
				$sheet->setCellValue('F' . $numrow, $data->ASM_Name);
				$sheet->setCellValue('G' . $numrow, $data->RSM_Code);
				$sheet->setCellValue('H' . $numrow, $data->RSM_Name);
				$sheet->setCellValue('I' . $numrow, $data->BSH_Code);
				$sheet->setCellValue('J' . $numrow, $data->BSH_Name);
				$sheet->setCellValue('K' . $numrow, $data->branch);
				$sheet->setCellValue('L' . $numrow, $maskingname);
				$sheet->setCellValue('M' . $numrow, $data->status);
				$sheet->setCellValue('N' . $numrow, $data->week);
				// $sheet->setCellValue('O' . $numrow, $data->Facilities_Type2);

				$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
				// $sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
				// $no++; // Tambah 1 setiap kali looping
				$numrow++; // Tambah 1 setiap kali looping
			}
			// Set width kolom
			$sheet->getColumnDimension('A')->setWidth(30);
			$sheet->getColumnDimension('B')->setWidth(30);
			$sheet->getColumnDimension('C')->setWidth(30);
			$sheet->getColumnDimension('D')->setWidth(30);
			$sheet->getColumnDimension('E')->setWidth(30);
			$sheet->getColumnDimension('F')->setWidth(30);
			$sheet->getColumnDimension('G')->setWidth(30);
			$sheet->getColumnDimension('H')->setWidth(30);
			$sheet->getColumnDimension('I')->setWidth(30);
			$sheet->getColumnDimension('J')->setWidth(30);
			$sheet->getColumnDimension('K')->setWidth(30);
			$sheet->getColumnDimension('L')->setWidth(30);
			$sheet->getColumnDimension('M')->setWidth(30);
			$sheet->getColumnDimension('N')->setWidth(20);
			// $sheet->getColumnDimension('O')->setWidth(20);



			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
			// Set orientasi kertas jadi LANDSCAPE
			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			// Set judul file excel nya
			$sheet->setTitle("Laporan");
			// Proses file excel
			ob_end_clean();
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=Detail Achievement Corp.xlsx");
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}

	private function maskingname($name)
	{
		$ex_name = explode(" ", $name);
		$jml_kata = count($ex_name);
		if ($jml_kata > 1) {
			// > 1 kata
			$ex_name = explode(" ", $name);
			$replace_name = '';
			for ($i = 0; $i < count($ex_name); $i++) {
				$jml_char = strlen($ex_name[$i]);
				if ($i == 0) {
					$replace_name .= $ex_name[$i] . " ";
				} elseif ($i == 1) {
					//$replace_name = substr($ex_name[$i], 0, 3);
					if ($jml_char > 6) {
						$left_string = substr($ex_name[$i], 0, 2);
						$jml_string = $jml_char - 2;
						$replace_name .= $left_string . "" . str_repeat("*", $jml_string) . " ";
					} else {
						$jml_string = 6 - 2;
						if ($jml_char > 2) {
							$left_string = substr($ex_name[$i], 0, 2);
							$repeater_mask = str_repeat("*", $jml_string);
							$replace_name .= $left_string . "" . $repeater_mask . " ";
						} else {
							$replace_name .= $ex_name[$i] . " ";
						}
					}
				} elseif ($i >= 2) {
					$repeater_mask = str_repeat("*", $jml_char);
					$replace_name .= $repeater_mask;
				}
			}
			return $replace_name;
		} else {
			// 1 kata
			$jml_char = strlen($name);
			$default_count_mask = 6;
			if ($jml_char > 6) {
				$left_string = substr($name, 0, 3);
				$jml_string = $jml_char - 3;
				$repeater_mask = str_repeat("*", $jml_string);
				$replace_name = $left_string . "" . $repeater_mask;
			} else {
				if ($jml_char > 3) {
					$left_string = substr($name, 0, 3);
					$jml_string = $default_count_mask - 3;
					$repeater_mask = str_repeat("*", $jml_string);
					$replace_name = $left_string . "" . $repeater_mask;
				} else {
					$jml_string = 6 - $jml_char;
					$replace_name = $name . "" . str_repeat("*", $jml_string);
				}
			}
			return $replace_name;
		}
	}
}
