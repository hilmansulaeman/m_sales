<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pemol extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('decision/pemol_model');
	}
	
	function index()
    {
		$username = $this->session->userdata('sl_code'); // DSR_CODE
	    $position = $this->session->userdata('position');
		$period = $this->pemol_model->get_last_period();
		$group_date = $period->num_rows() == 0 ? date('Y-m') : $period->row()->Group_Date;
	    $this->session->set_userdata('groupDate', $group_date);
		$groupDate 	= $this->session->userdata('groupDate');
		// $this->session->set_userdata('date_to', date('Y-m'));
		
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
			$data['getPemol'] = $this->pemol_model->get_pemol_dsr($username,$groupDate);
		}else{
			$views = 'index';
		}
		//load view
		$this->template->set('title','Decision Pemol');
		$this->template->load('template','decision/pemol/'.$views,$data);
    }
	
	function get_data()
    {
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');
		// $date_to 	= $this->session->userdata('date_to');
		// $dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		if($position == 'BSH'){
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
		}
		else if($position == 'RSM'){
			$where = "RSM_Code = '$nik'";
			$groups = "ASM_Code";
		}
		else if($position == 'ASM'){
			$where = "ASM_Code = '$nik'";
			// $where = "SM_Code = '$nik' AND Position IN $asm_position";
			$groups = "SPV_Code";
		}
		else
		{
			$where = "SPV_Code = '$nik'";
			$groups = "Sales_Code";
		}
        $query = $this->pemol_model->get_datatables($where, $groups, $groupDate);
        $data = array();
        $no = $_POST['start'];
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

			$getPemol = $this->pemol_model->get_pemol($var,$sales_code,$groupDate);
			$actualOA  = 0 + $getPemol->oa;
			$actualSN  = 0 + $getPemol->sn;
			$actualSK  = 0 + $getPemol->sk;
			$actualSD  = 0 + $getPemol->sd;
			$actualKTB = 0 + $getPemol->ktb;
			$actualTotal = $actualOA+$actualSN+$actualSK+$actualSD+$actualKTB;
			//$target = 120;
			//$gap = $actual - $target;
			// $disallow_position = array('DSR');
			
			$data[] = array(
				++$no,
				$sales_code.', '.$sales_name.' ('.$sales_position.')',
				'<span title="Actual" class="badge bg-black">'.number_format($actualOA).'</span>',
				'<span title="Actual" class="badge bg-red">'.number_format($actualSN).'</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actualSK).'</span>',
				'<span title="Actual" class="badge bg-blue">'.number_format($actualSD).'</span>',
				'<span title="Actual" class="badge bg-yellow">'.number_format($actualKTB).'</span>',
				'<span title="Actual" class="badge bg-grey">'.number_format($actualTotal).'</span>',
				// '<a href="javascript:void(0);" onclick="view_spv(\''.$sales_name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				'<a href="javascript:void(0);" onclick="view_spv(\''.$sales_code.'\',\''.$sales_position.'\',\''.$sales_name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
        }
		
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->pemol_model->count_filtered($where, $groups, $groupDate),
            "recordsFiltered" => $this->pemol_model->count_filtered($where, $groups, $groupDate),
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
	
	function detail($sales, $pos)
    {
	    $this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('decision/pemol/detail');
    }
	
	function get_data_spv()
    {
	    $position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code');
		$pos 		= $this->session->userdata('sm_position');
		$user 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');
		// $date_to 	= $this->session->userdata('date_to');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
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
		
        $query = $this->pemol_model->get_datatables($where, $groups, $groupDate);
        $data = array();
        $no = $_POST['start'];
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

			$getPemol = $this->pemol_model->get_pemol_detail($var,$sales_code,$var2,$upliner,$groupDate);
			$actualOA  = $getPemol->oa;
			$actualSN  = $getPemol->sn;
			$actualSK  = $getPemol->sk;
			$actualSD  = $getPemol->sd;
			$actualKTB = $getPemol->ktb;
			$actualTotal = $actualOA+$actualSN+$actualSK+$actualSD+$actualKTB;

			if (in_array($sales_position, $array_structure)) {
				$buttons = '<a href="javascript:void(0);" onclick="view_spv(\''.$sales_code.'\',\''.$sales_position.'\',\''.$sales_name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
			}else{
				$buttons = '';
			}

			//$target = 120;
			//$gap = $actual - $target;
			$data[] = array(
				++$no,
				$sales_code.', '.$sales_name.' ('.$sales_position.')',
				'<span title="Actual" class="badge bg-black">'.number_format($actualOA).'</span>',
				'<span title="Actual" class="badge bg-red">'.number_format($actualSN).'</span>',
				'<span title="Actual" class="badge bg-green">'.number_format($actualSK).'</span>',
				'<span title="Actual" class="badge bg-blue">'.number_format($actualSD).'</span>',
				'<span title="Actual" class="badge bg-yellow">'.number_format($actualKTB).'</span>',
				'<span title="Actual" class="badge bg-grey">'.number_format($actualTotal).'</span>',
				$buttons
			);
        }
		
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->pemol_model->count_filtered($where, $groups, $groupDate),
            "recordsFiltered" => $this->pemol_model->count_filtered($where, $groups, $groupDate),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

	function export()
	{
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style_col = $this->style_col();
		$style_row = $this->style_row();


		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style_col = $this->style_col();
		$style_row = $this->style_row();
		$sheet->setCellValue('A1', "SALES CODE");
		$sheet->setCellValue('B1', "SALES NAME");
		$sheet->setCellValue('C1', "SPV CODE");
		$sheet->setCellValue('D1', "SPV NAME");
		$sheet->setCellValue('E1', "ASM CODE");
		$sheet->setCellValue('F1', "ASM NAME");
		$sheet->setCellValue('G1', "RSM CODE");
		$sheet->setCellValue('H1', "RSM NAME");
		$sheet->setCellValue('I1', "BSH CODE");
		$sheet->setCellValue('J1', "BSH NAME");
		$sheet->setCellValue('K1', "BRANCH");
		$sheet->setCellValue('L1', "NO REKENING");
		$sheet->setCellValue('M1', "TGL INPUT");
		$sheet->setCellValue('N1', "TGL OPEN REKENING");
		$sheet->setCellValue('O1', "TGL SETOR");
		$sheet->setCellValue('P1', "SETORAN AWAL");
		$sheet->setCellValue('Q1', "KODE PROMO");
		$sheet->setCellValue('R1', "STATUS");

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
		$sheet->getStyle('O1')->applyFromArray($style_col);
		$sheet->getStyle('P1')->applyFromArray($style_col);
		$sheet->getStyle('Q1')->applyFromArray($style_col);
		$sheet->getStyle('R1')->applyFromArray($style_col);
		//ambil data
		$groupDate 	= $this->session->userdata('groupDate');

		$query = $this->pemol_model->get_pemol_export($groupDate);
		
		//validasi jumlah data
		if ($query->num_rows() == 0) { ?>
			<script type="text/javascript" language="javascript">
				alert("No data...!!!");
			</script>
<?php
			echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "decision/pemol'>";

			return false;
		} else {
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
			foreach ($query->result() as $data) { // Lakukan looping pada variabel sn
				$maskingname = $this->maskingname($data->Nomor_Rekening);
				$tgl_oa = date("d/m/Y", strtotime($data->Tgl_Open_Account));
				
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
				$sheet->setCellValue('M' . $numrow, $data->Tgl_input);
				$sheet->getStyle('N' . $numrow)
					->getNumberFormat()
					->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
				$sheet->setCellValue('N' . $numrow, $tgl_oa);
				$sheet->setCellValue('O' . $numrow, $data->Tgl_Setor);
				$sheet->setCellValue('P' . $numrow, $data->Setoran_awal);
				$sheet->setCellValue('Q' . $numrow, $data->Kode_promo);
				$sheet->setCellValue('R' . $numrow, $data->Status);

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
				$sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('R' . $numrow)->applyFromArray($style_row);
				$no++; // Tambah 1 setiap kali looping
				$numrow++; // Tambah 1 setiap kali looping
			}
			// Set width kolom
			$sheet->getColumnDimension('A')->setWidth(10);
			$sheet->getColumnDimension('B')->setWidth(50);
			$sheet->getColumnDimension('C')->setWidth(10);
			$sheet->getColumnDimension('D')->setWidth(50);
			$sheet->getColumnDimension('E')->setWidth(10);
			$sheet->getColumnDimension('F')->setWidth(50);
			$sheet->getColumnDimension('G')->setWidth(10);
			$sheet->getColumnDimension('H')->setWidth(50);
			$sheet->getColumnDimension('I')->setWidth(20);
			$sheet->getColumnDimension('J')->setWidth(30);
			$sheet->getColumnDimension('K')->setWidth(30);
			$sheet->getColumnDimension('L')->setWidth(30);
			$sheet->getColumnDimension('M')->setWidth(30);
			$sheet->getColumnDimension('N')->setWidth(30);
			$sheet->getColumnDimension('O')->setWidth(30);
			$sheet->getColumnDimension('P')->setWidth(30);
			$sheet->getColumnDimension('Q')->setWidth(30);
			$sheet->getColumnDimension('R')->setWidth(30);


			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
			// Set orientasi kertas jadi LANDSCAPE
			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			// Set judul file excel nya
			$sheet->setTitle("Laporan");
			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=Data_Achievement_Pemol.xlsx");
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}
	
	/*private function maskingname($name)
	{
		return substr_replace($name, str_repeat('*', strlen($name)-4), 0, -4);
	}*/

	private function maskingname($name)
	{
		// 1 kata
		$jml_char = strlen($name);
		$default_count_mask = 10;
		$replace_name = '';
		if ($jml_char >= 10) {
			$right_string = substr($name, -4);
			$jml_string = $jml_char - 4;
			$repeater_mask = str_repeat("*", $jml_string);
			$replace_name .= $repeater_mask.$right_string;
		} else {
			$right_string = substr($name, -4);
			$jml_string = $default_count_mask - 4;
			$repeater_mask = str_repeat("*", $jml_string);
			$replace_name .= $repeater_mask.$right_string;
		}
		return $replace_name;
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
	
	function style_col()
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
					'argb' => 'FF0e456d',
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

}