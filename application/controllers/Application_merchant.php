<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Application_merchant extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model('incoming/merchant_model');
    }

    // =====================================================================
    //  MERCHANT — Page + AJAX endpoints
    // =====================================================================

    function index()
    {
        $position = $this->session->userdata('position');

        // Set default date range & source jika belum ada di session
        if (!$this->session->userdata('date_from')) {
            $this->session->set_userdata('date_from', date('Y-m-01'));
        }
        if (!$this->session->userdata('date_to')) {
            $this->session->set_userdata('date_to', date('Y-m-d'));
        }
        if (!$this->session->userdata('source')) {
            $this->session->set_userdata('source', 'all');
        }

        // Tentukan label kolom berdasarkan posisi (Leader / DSR)
        if ($position == 'BSH') {
            $data['table_position'] = "RSM";
        } elseif ($position == 'RSM') {
            $data['table_position'] = "ASM";
        } elseif ($position == 'ASM') {
            $data['table_position'] = "SPV";
        } elseif ($position == 'SPV') {
            $data['table_position'] = "DSR";
        } else {
            $data['table_position'] = "DSR";
        }

        $data['date_from']     = $this->session->userdata('date_from');
        $data['date_to']       = $this->session->userdata('date_to');
        $data['source']        = $this->session->userdata('source');
        $data['user_position'] = $position;
        $data['is_leader']     = in_array($position, array('BSH', 'RSM', 'ASM', 'SPV'));

        $this->load->view('application_input/application_input_merchant', $data);
    }

    /** AJAX: ambil data tabel utama */
    function get_data()
    {
        // Ambil filter dari POST (jika dikirim dari loadData) atau Session
        $date_from = $this->input->post('date_from') ?: $this->session->userdata('date_from');
        $date_to   = $this->input->post('date_to') ?: $this->session->userdata('date_to');
        $source    = $this->input->post('source') ?: $this->session->userdata('source');

        // Update session agar sinkron
        if ($this->input->post('date_from')) {
            $this->session->set_userdata(array(
                'date_from' => $date_from,
                'date_to'   => $date_to,
                'source'    => $source
            ));
        }

        $position    = $this->session->userdata('position');
        $nik         = $this->session->userdata('sl_code');

        $dsr_position = "('DSR','SPG','SPB')";
        $array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
        $array_dsr    = array('DSR', 'SPG', 'SPB');

        if ($position == 'BSH') {
            $where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM', 'SPV')";
        } elseif ($position == 'RSM') {
            $where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
        } elseif ($position == 'ASM') {
            $where = "SM_Code = '$nik' AND Position = 'SPV'";
        } elseif ($position == 'SPV') {
            $where = "SM_Code = '$nik' AND Position IN $dsr_position";
        } else {
            $where = "DSR_Code = '$nik'";
        }

        $query = $this->merchant_model->get_datatables($where);
        
        // --- OPTIMASI: Bulk Fetch ---
        $sales_list = array();
        foreach($query as $r) {
            $sales_list[] = $r->DSR_Code;
        }

        // Tentukan column penarik data berdasarkan level (t2.SPV_Code, dll)
        // Kita ambil sample dari baris pertama untuk menentukan lead_column
        $lead_col = 't2.DSR_Code'; 
        if(!empty($query)) {
            $first = $query[0];
            if (in_array($first->Position, $array_leader)) {
                $lead_col = 't2.' . $first->Position . '_Code';
            }
        }

        $bulk_data = array();
        if (!empty($sales_list)) {
            $bulk_data = $this->merchant_model->fetchBulkMerchantSummary($lead_col, $sales_list, $date_from, $date_to, $source);
        }
        // --- END OPTIMASI ---

        $data = array();
        $no = $this->input->post('start');

        foreach ($query as $row) {
            $sales = $row->DSR_Code;
            $summary = (isset($bulk_data[$sales])) ? $bulk_data[$sales] : array();

            $data[] = array(
                ++$no,
                $row->Name . ' (' . $row->Position . ')',
                $row->DSR_Code,
                $row->Branch,
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-emerald-500 min-w-[30px] inline-block text-center">' . number_format($summary['total_dsr'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-gray-900 min-w-[30px] inline-block text-center">' . number_format($summary['total_input'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-sky-500 min-w-[30px] inline-block text-center">'  . number_format($summary['total_received'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-amber-500 min-w-[30px] inline-block text-center">' . number_format($summary['inprocess'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-red-500 min-w-[30px] inline-block text-center">'    . number_format($summary['rts'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-emerald-600 min-w-[30px] inline-block text-center">'  . number_format($summary['send'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-amber-500 min-w-[30px] inline-block text-center">' . number_format($summary['pending'] ?? 0) . '</span>',
                '<span class="px-2 py-1 rounded-full text-[11px] font-bold text-white bg-red-600 min-w-[30px] inline-block text-center">'    . number_format($summary['cancel'] ?? 0) . '</span>',
                '<button onclick="showActionMenu(event, this)" data-nik="'.$row->DSR_Code.'" data-name="'.$row->Name.'" data-position="'.$row->Position.'" class="p-1 hover:bg-gray-100 rounded-lg text-[#1E5BA8] transition-colors"><i data-lucide="more-horizontal" class="w-5 h-5"></i></button>'
            );
        }

        // Dummy SPV logic for ASM
        if ($position == 'ASM' && !empty($query)) {
            $dummyIS = $this->merchant_model->getDataInputDummy($nik, $date_from, $date_to, 'spv', 'IS', $source);
            $dummyPR = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PR', $source);
            $dummyPI = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PI', $source);
            $dummyPRTS = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PRTS', $source);
            $dummyPS = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PS', $source);
            $dummyPC = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PC', $source);
            $dummyPPS = $this->merchant_model->getAppProcessingDummy($nik, $date_from, $date_to, 'spv', 'PPS', $source);

            $data[] = array(
                ++$no,
                'DUMMY SPV (Aggregate)',
                $nik,
                'ALL',
                '<span class="badge bg-green">' . number_format($dummyIS->total_dsr ?? 0) . '</span>',
                '<span class="badge bg-black">' . number_format($dummyIS->total ?? 0) . '</span>',
                '<span class="badge bg-info">'  . number_format($dummyPR->total ?? 0) . '</span>',
                '<span class="badge bg-yellow">' . number_format($dummyPI->total ?? 0) . '</span>',
                '<span class="badge bg-red">'    . number_format($dummyPRTS->total ?? 0) . '</span>',
                '<span class="badge bg-green">'  . number_format($dummyPS->total ?? 0) . '</span>',
                '<span class="badge bg-yellow">' . number_format($dummyPPS->total ?? 0) . '</span>',
                '<span class="badge bg-red">'    . number_format($dummyPC->total ?? 0) . '</span>',
                ''
            );
        }

        echo json_encode(array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->merchant_model->count_filtered($where),
            "recordsFiltered" => $this->merchant_model->count_filtered($where),
            "data"            => $data,
        ));
    }

    /** AJAX: simpan filter ke session */
    function filter()
    {
        $date_from = $this->input->post('date_from');
        $date_to   = $this->input->post('date_to');
        $source    = $this->input->post('source');

        $range = $this->_datediff($date_from, $date_to);

        if ($range > 31) {
            echo json_encode(array(
                "status" => FALSE,
                "inputerror" => array("date_to"),
                "error_string" => array("Maaf, range tanggal maksimal 31 hari")
            ));
            exit();
        }

        $this->session->set_userdata(array(
            'date_from' => $date_from,
            'date_to'   => $date_to,
            'source'    => $source
        ));
        echo json_encode(array("status" => TRUE));
    }

    /** Export Excel */
    function export()
    {
        $date_from = $this->session->userdata('date_from');
        $date_to   = $this->session->userdata('date_to');
        $source    = $this->session->userdata('source');
        
        $query = $this->merchant_model->getBreakdownMerchantexport($date_from, $date_to, $source);

        if (empty($query)) {
            echo "<script>alert('No data...!!!'); window.history.back();</script>";
            return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = array(
            "Sales Code", "Sales Name", "SPV Code", "SPV Name", 
            "ASM Code", "ASM Name", "RSM Code", "RSM Name", 
            "BSH Code", "BSH Name", "Branch", "Merchant Name", 
            "Jenis Approval", "Kode Officer", "Group Fasilitas", 
            "Product Type", "Product Status"
        );

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $numrow = 2;
        foreach ($query as $row) {
            $sheet->setCellValue('A'.$numrow, $row->Sales_Code);
            $sheet->setCellValue('B'.$numrow, $row->Sales_Name);
            $sheet->setCellValue('C'.$numrow, $row->SPV_Code);
            $sheet->setCellValue('D'.$numrow, $row->SPV_Name);
            $sheet->setCellValue('E'.$numrow, $row->ASM_Code);
            $sheet->setCellValue('F'.$numrow, $row->ASM_Name);
            $sheet->setCellValue('G'.$numrow, $row->RSM_Code);
            $sheet->setCellValue('H'.$numrow, $row->RSM_Name);
            $sheet->setCellValue('I'.$numrow, $row->BSH_Code);
            $sheet->setCellValue('J'.$numrow, $row->BSH_Name);
            $sheet->setCellValue('K'.$numrow, $row->Branch);
            $sheet->setCellValue('L'.$numrow, $row->Merchant_Name);
            $sheet->setCellValue('M'.$numrow, $row->Approval_Type);
            $sheet->setCellValue('N'.$numrow, $row->Officer_Code);
            $sheet->setCellValue('O'.$numrow, $row->Facility_Group);
            $sheet->setCellValue('P'.$numrow, $row->Product_Type);
            $sheet->setCellValue('Q'.$numrow, $row->Product_Status);
            $numrow++;
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Merchant_Export_'.$date_from.'_to_'.$date_to.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /** AJAX: Ambil data downline untuk modal */
    public function get_downline_data()
    {
        $nik_leader = $this->input->post('nik');
        $pos_leader = $this->input->post('position');
        
        $date_from  = $this->input->post('date_from');
        $date_to    = $this->input->post('date_to');
        $source     = $this->input->post('source');
        $search     = $this->input->post('search');

        // Tentukan level downline
        $child_pos = 'DSR';
        if ($pos_leader == 'BSH') $child_pos = 'RSM';
        elseif ($pos_leader == 'RSM') $child_pos = 'ASM';
        elseif ($pos_leader == 'ASM') $child_pos = 'SPV';
        elseif ($pos_leader == 'SPV') $child_pos = 'DSR';

        // Replicate logic from Merchant.php (using API/Model)
        $dsr_position = "('DSR','SPG','SPB')";
        if ($pos_leader == 'BSH') {
            $where = "SM_Code = '$nik_leader' AND Position IN('RSM', 'ASM', 'SPV')";
        } elseif ($pos_leader == 'RSM') {
            $where = "SM_Code = '$nik_leader' AND Position IN('ASM', 'SPV')";
        } elseif ($pos_leader == 'ASM') {
            $where = "SM_Code = '$nik_leader' AND Position = 'SPV'";
        } else {
            $where = "SM_Code = '$nik_leader' AND Position IN $dsr_position";
        }

        // Bridge search parameter for model if sent as string instead of array
        if (!isset($_POST['search']['value'])) {
            $_POST['search'] = array('value' => $search);
        }

        $query = $this->merchant_model->get_datatables($where);
        $recordsFiltered = $this->merchant_model->count_filtered($where);

        // Optimasi: Bulk fetch data DSR/Input
        $sales_list = array();
        foreach($query as $r) $sales_list[] = $r->DSR_Code;
        
        $bulk_data = array();
        if (!empty($sales_list)) {
            $lead_col = 't2.' . $child_pos . '_Code';
            if($child_pos == 'DSR') $lead_col = 't2.DSR_Code';
            $bulk_data = $this->merchant_model->fetchBulkMerchantSummary($lead_col, $sales_list, $date_from, $date_to, $source);
        }

        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row) {
            $sales = $row->DSR_Code;
            $summary = (isset($bulk_data[$sales])) ? $bulk_data[$sales] : array();

            $data[] = array(
                ++$no,
                $row->Name,
                $row->DSR_Code,
                $row->Branch,
                '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold text-white bg-emerald-500 min-w-[30px] inline-block text-center">' . number_format($summary['total_dsr'] ?? 0) . '</span>',
                '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold text-white bg-gray-900 min-w-[30px] inline-block text-center">' . number_format($summary['total_input'] ?? 0) . '</span>',
                '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold text-sky-600 bg-sky-50 min-w-[30px] inline-block text-center">' . number_format($summary['total_received'] ?? 0) . '</span>',
                '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold text-amber-600 bg-amber-50 min-w-[30px] inline-block text-center">' . number_format($summary['inprocess'] ?? 0) . '</span>',
                number_format(($summary['total_received'] ?? 0) + ($summary['inprocess'] ?? 0)),
                '<button onclick="loadModalDataDeep(event, this)" data-nik="'.$row->DSR_Code.'" data-name="'.$row->Name.'" data-position="'.$row->Position.'" class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors" title="View Downline"><i data-lucide="eye" class="w-4 h-4"></i></button>'
            );
        }

        echo json_encode(array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $recordsFiltered,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data,
        ));
    }

    private function _datediff($start, $end)
    {
        $days = date_diff(date_create($start), date_create($end));
        return $days->format('%R%a');
    }
}
