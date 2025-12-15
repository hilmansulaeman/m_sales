<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pemol extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('incoming/pemol_model');
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
		$this->template->set('title','Summary Pemol');
		$this->template->load('template','incoming/pemol/index',$data);
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
        $query = $this->pemol_model->get_datatables($where);
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

			$getPemol = $this->pemol_model->get_pemol($var, $sales, $date_from, $date_to);
			if($getPemol){
				$actual = $getPemol->total;
				$mobile_bca = $getPemol->mobile_bca;
				$my_bca = $getPemol->my_bca;
			}else{
				$actual = 0;
				$mobile_bca = 0;
				$my_bca = 0;
			}
			
			if (in_array($position, $array_leader2)) {
			
				$dsr_active = $getPemol->dsr_active;
				$dsr_input = $getPemol->dsr_input;

				$cols = array(
				    '<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active).'</span>',
					'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input).'</span>',
					'<span title="Total Input" class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
					'<span title="Total Input" class="badge bg-blue">' . number_format($my_bca) . '</span>',
					'<span title="Total Input" class="badge bg-green">'.number_format($actual).'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			else{
				$cols = array(
					'<span title="Total Input" class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
					'<span title="Total Input" class="badge bg-blue">' . number_format($my_bca) . '</span>',
					'<span title="Total Input" class="badge bg-green">' . number_format($actual) . '</span>'
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
			$get_pemol_dummy_spv = $this->pemol_model->get_pemol_dummy($nik,$date_from,$date_to,'spv');
			$actual_dummy_spv = $get_pemol_dummy_spv->total;
			$dsr_active_dummy = $get_pemol_dummy_spv->dsr_active;
			$dsr_input_dummy = $get_pemol_dummy_spv->dsr_input;
			$mobile_bca_dummy = $get_pemol_dummy_spv->mobile_bca;
			$my_bca_dummy = $get_pemol_dummy_spv->my_bca;
			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active_dummy).'</span>',
				'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input_dummy).'</span>',
				'<span title="Total Input" class="badge bg-yellow">' . number_format($mobile_bca_dummy) . '</span>',
				'<span title="Total Input" class="badge bg-blue">' . number_format($my_bca_dummy) . '</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual_dummy_spv).'</span>',
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}
		
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->pemol_model->count_filtered($where),
            "recordsFiltered" => $this->pemol_model->count_filtered($where),
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
		$this->load->view('incoming/pemol/detail');
    }
	
	function get_data_spv()
    {
	    $position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');
		$user 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$dsr_position_arr = array('DSR', 'SPG', 'SPB', 'FO', 'Funding Officer', 'RO', 'Relationship Officer');
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
		
        $query = $this->pemol_model->get_datatables($where);
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

			$getPemol = $this->pemol_model->get_pemol($var,$sales,$date_from,$date_to);
			$actual = $getPemol->total;
			$dsr_active = $getPemol->dsr_active;
			$dsr_input = $getPemol->dsr_input;
			$mobile_bca = $getPemol->mobile_bca;
			$my_bca = $getPemol->my_bca;
			
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
				'<span title="Total Input" class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
				'<span title="Total Input" class="badge bg-blue">' . number_format($my_bca) . '</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual).'</span>',
				$button
			);
        }

		if($pos == 'ASM'){
			$get_pemol_dummy_spv = $this->pemol_model->get_pemol_dummy($nik,$date_from,$date_to,'spv');
			$actual_dummy_spv = $get_pemol_dummy_spv->total;
			$dsr_active_dummy = $get_pemol_dummy_spv->dsr_active;
			$dsr_input_dummy = $get_pemol_dummy_spv->dsr_input;
			$mobile_bca_dummy = $get_pemol_dummy_spv->mobile_bca;
			$my_bca_dummy = $get_pemol_dummy_spv->my_bca;
			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="DSR Active" class="badge bg-black">'.number_format($dsr_active_dummy).'</span>',
				'<span title="DSR Input" class="badge bg-red">'.number_format($dsr_input_dummy).'</span>',
				'<span title="Total Input" class="badge bg-yellow">' . number_format($mobile_bca_dummy) . '</span>',
				'<span title="Total Input" class="badge bg-blue">' . number_format($my_bca_dummy) . '</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actual_dummy_spv).'</span>',
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}
		
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->pemol_model->count_filtered($where),
            "recordsFiltered" => $this->pemol_model->count_filtered($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

	// EXPORT PAGE
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

	function style_col2()
	{
		return [
			'font' => [
				'bold' => true,
				'color' => ['rgb' => 'FFFFFF'],
			], // Set font nya jadi bold
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FF3da5ef',
				],
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
		$style_col2 = $this->style_col2();
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
		$sheet->setCellValue('K1', "BRANCH");
		$sheet->setCellValue('L1', "NOMOR REKENING");
		$sheet->setCellValue('M1', "Type");

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
		//ambil data
		$date_from = $this->session->userdata('date_from');
		$date_to 	 = $this->session->userdata('date_to');

		$query = $this->pemol_model->getBreakdownPemolexport($date_from, $date_to);

		//validasi jumlah data
		if (count($query) == 0) { ?>
			<script type="text/javascript" language="javascript">
				alert("No data...!!!");
			</script>
		<?php
			echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "incoming/pemol'>";

			return false;
		} else {
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
			foreach ($query as $data) { // Lakukan looping pada variabel sn
				$maskingname = $this->maskingname($data->Account_Number);

				$sheet->setCellValue('A' . $numrow, $data->Sales_Code);
				$sheet->setCellValue('B' . $numrow, $data->Sales_Name);
				$sheet->setCellValue('C' . $numrow, $data->SPV_Code);
				$sheet->setCellValue('D' . $numrow, $data->SPV_Name);
				$sheet->setCellValue('E' . $numrow, $data->ASM_Code);
				$sheet->setCellValue('F' . $numrow, $data->ASM_Name);
				$sheet->setCellValue('G' . $numrow, $data->RSM_Code);
				$sheet->setCellValue('H' . $numrow, $data->RSM_Name);
				$sheet->setCellValue('I' . $numrow, $data->BSH_Code);
				$sheet->setCellValue('J' . $numrow, $data->BSH_Name);
				$sheet->setCellValue('K' . $numrow, $data->Branch);
				$sheet->setCellValue('L' . $numrow, $maskingname);
				$sheet->setCellValue('M' . $numrow, $data->Source);

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



			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
			// Set orientasi kertas jadi LANDSCAPE
			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			// Set judul file excel nya
			$sheet->setTitle("Laporan");
			// Proses file excel
			ob_end_clean();
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=Data Incoming Pemol.xlsx");
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}

	// function unit_testing_export()
	// {
	// 	$date_from = $this->session->userdata('date_from');
	// 	$date_to 	 = $this->session->userdata('date_to');

	// 	$query = $this->pemol_model->getBreakdownPemolexport($date_from, $date_to);

	// 	if (count($query) == 0) {
	// 		$result = false;
	// 	} else {
	// 		$result = true;
	// 	}
	// 	$this->unit->run($result, 'is_true', 'get data untuk export data dsr incoming');

	// 	echo $this->unit->report();
	// }
	// END EXPORT PAGE
	
	//================================================= INTERNAL FUNCTION =============================================//
	//datedif
	private function datediff($start,$end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);
		
		$days = date_diff($date1,$date2);
		
		return $days->format('%R%a');
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