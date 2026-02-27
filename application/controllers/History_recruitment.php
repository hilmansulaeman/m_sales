<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class History_recruitment extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('auth', 'template', 'unit_test', 'form_validation'));
        $this->load->model('history_recruitment_model', 'model');
        $this->position = $this->session->userdata('position');
    }

    function index()
    {
        if (in_array($this->position, ['ASM', 'RSM', 'BSH'])) {
            $this->template->set('title', 'Recruitment History');
            $this->template->load('template', 'history_recruitment/index');
        } else {
            redirect('');
        }
    }

    function get_data()
    {
        $data  = array();
        $no = $this->input->post('start');
        $query = $this->model->get_datatables();

        foreach ($query as $row) {
            $dataRow = array(
                ++$no,
                '
                    <h5><strong>Recruitment ID: ' . $row->Recruitment_ID . '</strong></h5>
                    <span style="color:black;">
                        <b>Name</b>: ' . $row->Name . '<br>
                        <b>Product</b>: ' . $row->Product . '<br>
                        <b>Area</b>: ' . $row->Branch . '<br>
                        <b>Position</b>: ' . $row->Position . '<br>
                        <b>Level</b>: ' . $row->Level . '<br>
                        <b>SPV Name</b>: ' . $row->SM_Name . '<br>
                        <b>ASM Name</b>: ' . $row->ASM_Name . '<br>
                        <b>RSM Name</b>: ' . $row->RSM_Name . '<br>
                        <b>BSH Names</b>: ' . $row->BSH_Name . '<br>

                    </span>
                    &nbsp;',
            );


            $data[] = $dataRow;
        }

        $recordsTotal = $this->model->count_filtered();

        $recordsFiltered = $this->model->count_filtered();

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        // output dalam format JSON
        echo json_encode($output);
    }

    function export()
    {
        $data = $this->model->get_export();
        // cekvar($data);
        // die;
        if (empty($data)) {
?>
            <script type="text/javascript" language="javascript">
                alert("No data...!!!");
            </script>
<?php
            redirect('history_recruitment', 'refresh');

            return false;
        }

        // Inisialisasi Spreadsheet dari PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Recruitment ID');
        $sheet->setCellValue('C1', 'Name');
        $sheet->setCellValue('D1', 'Area');
        $sheet->setCellValue('E1', 'Position');
        $sheet->setCellValue('F1', 'Level');
        $sheet->setCellValue('G1', 'SPV Name');
        $sheet->setCellValue('H1', 'ASM Name');
        $sheet->setCellValue('I1', 'RSM Name');
        $sheet->setCellValue('J1', 'BSH Name');
        $sheet->setCellValue('K1', 'Status');
        $sheet->setCellValue('L1', 'Note Return');


        // Membuat style untuk header kolom
        $headerStyleArray = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFF00', // Warna kuning
                ],
            ],
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => '000080', // Warna teks biru
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Rata tengah
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Border tipis
                ],
            ],
        ];

        // Terapkan gaya ke header kolom
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyleArray);

        // Isi data
        $row_number = 2;
        $no = 1;
        foreach ($data as $row) {

            $hit_code = $row->Hit_Code;
            if ($hit_code == 9) {
                $status = "BUCKET HRD";
            } else if ($hit_code == 13) {
                $status = ($row->Hit_Code == 13) ?  $row->status_msales . ' ' . $row->position_msales : $row->Activity;
            } else if ($hit_code == 3) {
                $status = 'RETURN';
            } else if($hit_code > 13) {
                $status = "PROCESS HRD";
            }
            else {
                $status = "-";
            }

            $noteReturn = ($row->Reason == null) ? '' :  $row->Reason;

            $sheet->setCellValue('A' . $row_number, $no++);
            $sheet->setCellValue('B' . $row_number, $row->Recruitment_ID);
            $sheet->setCellValue('C' . $row_number, $row->Name);
            $sheet->setCellValue('D' . $row_number, $row->Branch);
            $sheet->setCellValue('E' . $row_number, $row->Position);
            $sheet->setCellValue('F' . $row_number, $row->Level);
            $sheet->setCellValue('G' . $row_number, $row->SM_Name);
            $sheet->setCellValue('H' . $row_number, $row->ASM_Name);
            $sheet->setCellValue('I' . $row_number, $row->RSM_Name);
            $sheet->setCellValue('J' . $row_number, $row->BSH_Name);
            $sheet->setCellValue('K' . $row_number, $status);
            $sheet->setCellValue('L' . $row_number, $noteReturn);

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(10);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(30);
            $sheet->getColumnDimension('I')->setWidth(30);
            $sheet->getColumnDimension('J')->setWidth(30);
            $sheet->getColumnDimension('K')->setWidth(20);
            $sheet->getColumnDimension('L')->setWidth(20);

            $row_number++;
        }

        // Set judul file
        $filename = "Report_History_" . date("Y-m-d_H-i-s") . ".xlsx";

        // Proses export
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
