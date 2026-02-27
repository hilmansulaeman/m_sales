<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Application_input extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->model('incoming/pemol_model');
    }

    // =====================================================================
    //  PEMOL — Page + AJAX endpoints (semua dalam 1 controller)
    // =====================================================================

    function pemol()
    {
        $position = $this->session->userdata('position');

        // Set default date range jika belum ada di session
        if (!$this->session->userdata('date_from')) {
            $this->session->set_userdata('date_from', date('Y-m-01'));
        }
        if (!$this->session->userdata('date_to')) {
            $this->session->set_userdata('date_to', date('Y-m-d'));
        }

        if ($position == 'BSH') {
            $data['table_position'] = "RSM";
            $data['table_detail']   = "ASM";
        } elseif ($position == 'RSM') {
            $data['table_position'] = "ASM";
            $data['table_detail']   = "SPV";
        } elseif ($position == 'ASM') {
            $data['table_position'] = "SPV";
            $data['table_detail']   = "DSR";
        } elseif ($position == 'SPV') {
            $data['table_position'] = "DSR";
            $data['table_detail']   = "DSR";
        } else {
            $data['table_position'] = "DSR";
            $data['table_detail']   = "";
        }

        $data['date_from']     = $this->session->userdata('date_from');
        $data['date_to']       = $this->session->userdata('date_to');
        $data['user_position'] = $position;
        $data['is_leader']     = in_array($position, array('BSH', 'RSM', 'ASM', 'SPV'));

        $this->load->view('application_input/application_input_pemol', $data);
    }

    /** AJAX: ambil data tabel utama */
    function pemol_get_data()
    {
        $position    = $this->session->userdata('position');
        $nik         = $this->session->userdata('sl_code');
        $date_from   = $this->session->userdata('date_from');
        $date_to     = $this->session->userdata('date_to');

        $array_leader  = array('BSH', 'RSM', 'ASM', 'SPV');
        $array_leader2 = array('BSH', 'RSM', 'ASM');

        if ($position == 'BSH') {
            $where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM', 'SPV')";
        } elseif ($position == 'RSM') {
            $where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
        } elseif ($position == 'ASM') {
            $where = "SM_Code = '$nik' AND Position = 'SPV'";
        } elseif ($position == 'SPV') {
            $where = "SM_Code = '$nik'";
        } else {
            $where = "DSR_Code = '$nik'";
        }

        $query = $this->pemol_model->get_datatables($where);
        $data  = array();
        $no    = $this->input->post('start');

        foreach ($query as $row) {
            $sales = $row->DSR_Code;

            if (in_array($row->Position, $array_leader)) {
                $var = 't2.' . $row->Position . '_Code';
            } else {
                $var = 't2.DSR_Code';
            }

            $getPemol = $this->pemol_model->get_pemol($var, $sales, $date_from, $date_to);
            if ($getPemol) {
                $actual     = $getPemol->total;
                $mobile_bca = $getPemol->mobile_bca;
                $my_bca     = $getPemol->my_bca;
            } else {
                $actual = $mobile_bca = $my_bca = 0;
            }

            if (in_array($position, $array_leader2)) {
                $dsr_active = $getPemol->dsr_active ?? 0;
                $dsr_input  = $getPemol->dsr_input  ?? 0;
                $cols = array(
                    '<span class="badge bg-black">' . number_format($dsr_active) . '</span>',
                    '<span class="badge bg-red">'   . number_format($dsr_input)  . '</span>',
                    '<span class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
                    '<span class="badge bg-blue">'   . number_format($my_bca)     . '</span>',
                    '<span class="badge bg-green">'  . number_format($actual)     . '</span>',
                    '<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn-view">View</a>'
                );
            } elseif ($position == 'SPV') {
                $dsr_active = $getPemol->dsr_active ?? 0;
                $dsr_input  = $getPemol->dsr_input  ?? 0;
                $cols = array(
                    '<span class="badge bg-black">'  . number_format($dsr_active) . '</span>',
                    '<span class="badge bg-red">'    . number_format($dsr_input)  . '</span>',
                    '<span class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
                    '<span class="badge bg-blue">'   . number_format($my_bca)     . '</span>',
                    '<span class="badge bg-green">'  . number_format($actual)     . '</span>',
                    ''
                );
            } else {
                $cols = array(
                    '<span class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
                    '<span class="badge bg-blue">'   . number_format($my_bca)     . '</span>',
                    '<span class="badge bg-green">'  . number_format($actual)     . '</span>'
                );
            }

            $data[] = array_merge(array(
                ++$no,
                $row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
                $row->Branch
            ), $cols);
        }

        if ($position == 'ASM') {
            $dummy = $this->pemol_model->get_pemol_dummy($nik, $date_from, $date_to, 'spv');
            if ($dummy) {
                $data[] = array(
                    ++$no, 'DUMMY SPV', 'ALL',
                    '<span class="badge bg-black">'  . number_format($dummy->dsr_active)  . '</span>',
                    '<span class="badge bg-red">'    . number_format($dummy->dsr_input)   . '</span>',
                    '<span class="badge bg-yellow">' . number_format($dummy->mobile_bca)  . '</span>',
                    '<span class="badge bg-blue">'   . number_format($dummy->my_bca)      . '</span>',
                    '<span class="badge bg-green">'  . number_format($dummy->total)       . '</span>',
                    '<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\',\'SPV\',\'DUMMY SPV\')" class="btn-view">View</a>'
                );
            }
        }

        echo json_encode(array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->pemol_model->count_filtered($where),
            "recordsFiltered" => $this->pemol_model->count_filtered($where),
            "data"            => $data,
        ));
    }

    /** AJAX: simpan filter tanggal ke session */
    function pemol_filter()
    {
        $date_from = $this->input->post('date_from');
        $date_to   = $this->input->post('date_to');
        $range     = $this->_datediff($date_from, $date_to);

        $result = array('status' => TRUE, 'inputerror' => array(), 'error_string' => array());

        if ($range > 31) {
            $result['inputerror'][]  = 'date_to';
            $result['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
            $result['status']         = FALSE;
        }

        if ($result['status'] === FALSE) {
            echo json_encode($result);
            exit();
        }

        $this->session->set_userdata(array(
            'date_from' => $date_from,
            'date_to'   => $date_to,
        ));
        echo json_encode(array("status" => TRUE));
    }

    /** Set session untuk detail/downline lalu load view kosong */
    function pemol_detail($sales = '', $pos = '')
    {
        $this->session->set_userdata('sm_code',     $sales);
        $this->session->set_userdata('sm_position', $pos);
        echo json_encode(array("status" => TRUE));
    }

    /** AJAX: data detail/downline */
    function pemol_get_data_spv()
    {
        $position  = $this->session->userdata('position');
        $nik       = $this->session->userdata('sm_code');
        $pos       = $this->session->userdata('sm_position');
        $date_from = $this->session->userdata('date_from');
        $date_to   = $this->session->userdata('date_to');

        $array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

        if ($pos == 'RSM') {
            $where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
        } elseif ($pos == 'ASM') {
            $where = "SM_Code = '$nik' AND Position = 'SPV'";
        } else {
            $where = "SM_Code = '$nik' AND Position IN('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
        }

        $query = $this->pemol_model->get_datatables($where);
        $data  = array();
        $no    = $this->input->post('start');

        foreach ($query as $row) {
            $sales  = $row->DSR_Code;
            $button = '';
            $var    = 't2.DSR_Code';

            if (in_array($position, $array_structure)) {
                if (in_array($row->Position, $array_structure)) {
                    $button = '<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn-view">View</a>';
                    $var    = 't2.' . $row->Position . '_Code';
                }
            }

            $getPemol   = $this->pemol_model->get_pemol($var, $sales, $date_from, $date_to);
            $actual     = $getPemol->total      ?? 0;
            $dsr_active = $getPemol->dsr_active ?? 0;
            $dsr_input  = $getPemol->dsr_input  ?? 0;
            $mobile_bca = $getPemol->mobile_bca ?? 0;
            $my_bca     = $getPemol->my_bca     ?? 0;

            $data[] = array(
                ++$no,
                $row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
                $row->Branch,
                '<span class="badge bg-black">'  . number_format($dsr_active) . '</span>',
                '<span class="badge bg-red">'    . number_format($dsr_input)  . '</span>',
                '<span class="badge bg-yellow">' . number_format($mobile_bca) . '</span>',
                '<span class="badge bg-blue">'   . number_format($my_bca)     . '</span>',
                '<span class="badge bg-green">'  . number_format($actual)     . '</span>',
                $button
            );
        }

        if ($pos == 'ASM') {
            $dummy = $this->pemol_model->get_pemol_dummy($nik, $date_from, $date_to, 'spv');
            if ($dummy) {
                $data[] = array(
                    ++$no, 'DUMMY SPV', 'ALL',
                    '<span class="badge bg-black">'  . number_format($dummy->dsr_active) . '</span>',
                    '<span class="badge bg-red">'    . number_format($dummy->dsr_input)  . '</span>',
                    '<span class="badge bg-yellow">' . number_format($dummy->mobile_bca) . '</span>',
                    '<span class="badge bg-blue">'   . number_format($dummy->my_bca)     . '</span>',
                    '<span class="badge bg-green">'  . number_format($dummy->total)      . '</span>',
                    '<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\',\'SPV\',\'DUMMY SPV\')" class="btn-view">View</a>'
                );
            }
        }

        echo json_encode(array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->pemol_model->count_filtered($where),
            "recordsFiltered" => $this->pemol_model->count_filtered($where),
            "data"            => $data,
        ));
    }

    /** Export Excel */
    function pemol_export()
    {
        $date_from = $this->session->userdata('date_from');
        $date_to   = $this->session->userdata('date_to');
        $query     = $this->pemol_model->getBreakdownPemolexport($date_from, $date_to);

        if (count($query) == 0) {
            echo "<script>alert('No data...!!!'); window.history.back();</script>";
            return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $headers     = array('Sales Code','Sales Name','SPV Code','SPV Name','ASM Code','ASM Name','RSM Code','RSM Name','BSH Code','BSH Name','Branch','Nomor Rekening','Type');
        $col         = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $numrow = 2;
        foreach ($query as $row) {
            $mask = $this->_maskingname($row->Account_Number);
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
            $sheet->setCellValue('L'.$numrow, $mask);
            $sheet->setCellValue('M'.$numrow, $row->Source);
            $numrow++;
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Input Pemol ' . $date_from . ' sd ' . $date_to . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    // =====================================================================
    //  HALAMAN LAINNYA
    // =====================================================================

    function merchant()
    {
        $this->load->view('application_input/application_input_merchant');
    }

    function cc_reguler()
    {
        $this->load->view('application_input/application_input_cc_reguler');
    }

    function cc_ms()
    {
        $this->load->view('application_input/application_input_cc_ms');
    }

    function corporate()
    {
        $this->load->view('application_input/application_input_corporate');
    }

    function sc()
    {
        $this->load->view('application_input/application_input_sc');
    }

    function pl()
    {
        $this->load->view('application_input/application_input_pl');
    }

    function cc_dsr()
    {
        $this->load->view('application_input/application_input_cc_dsr');
    }

    // =====================================================================
    //  PRIVATE HELPERS
    // =====================================================================

    private function _datediff($start, $end)
    {
        $days = date_diff(date_create($start), date_create($end));
        return $days->format('%R%a');
    }

    public function get_detail_content()
    {
        $this->load->model('incoming/merchant_model');

        $nik      = $this->input->post('nik');
        $position = $this->input->post('position');
        $tab      = $this->input->post('tab'); // data-input or app-processing
        $from     = $this->input->post('date_from');
        $to       = $this->input->post('date_to');
        $source   = $this->input->post('source') ?: 'all';

        $array_leader = array('BSH', 'RSM', 'ASM', 'SPV');

        // Logic Section dan Label
        if ($tab === 'data-input') {
            $section1 = 'IS'; // Input System
            $section2 = 'BS'; // Backlog Submit
            $label1   = "Total Input";
            $label2   = "Pending Submit";
        } else {
            $section1 = 'PR'; // Received App
            $section2 = 'PI'; // In-Process
            $label1   = "Received App";
            $label2   = "Inprocess";
        }

        // Fetch Data
        if (in_array($position, $array_leader)) {
            $lead_col   = 't2.' . $position . '_Code';
            $resTotal   = $this->merchant_model->getDataInputLocal($lead_col, $nik, $from, $to, $section1, $source);
            $resPending = $this->merchant_model->getDataInputLocal($lead_col, $nik, $from, $to, $section2, $source);
        } else {
            $resTotal   = $this->merchant_model->getDataInput($nik, $from, $to, $section1, $source);
            $resPending = $this->merchant_model->getDataInput($nik, $from, $to, $section2, $source);
        }

        $categories = [
            'edc'      => 'EDC',
            'qris'     => 'QRIS',
            'edc_qris' => 'EDC + QRIS'
        ];

        /* Render HTML Cards */
        $html = '<div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-2 duration-300">';
        
        foreach ($categories as $key => $label) {
            $val1 = number_format($resTotal->$key ?? 0);
            $val2 = number_format($resPending->$key ?? 0);
            
            $html .= '
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-blue-200 transition-all">
                <div class="bg-[#2463B4] py-3 text-center">
                    <span class="text-white font-bold tracking-wide text-sm">' . $label . '</span>
                </div>
                <div class="p-6 space-y-0">
                    <!-- Row 1: Total/Received -->
                    <div onclick="openBreakdown(\'' . $key . '\', \'' . $section1 . '\', \'' . $label . ' - ' . $label1 . '\')" class="flex justify-between items-center py-3 px-2 -mx-2 rounded-xl hover:bg-blue-50 cursor-pointer transition-colors group/row">
                        <span class="text-gray-500 font-medium">' . $label1 . '</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[#1E293B] font-bold text-lg">' . $val1 . '</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/row:text-blue-500 transition-colors"></i>
                        </div>
                    </div>
                    
                    <div class="h-[1px] bg-gray-100 my-1"></div>
                    
                    <!-- Row 2: Pending/Inprocess -->
                    <div onclick="openBreakdown(\'' . $key . '\', \'' . $section2 . '\', \'' . $label . ' - ' . $label2 . '\')" class="flex justify-between items-center py-3 px-2 -mx-2 rounded-xl hover:bg-blue-50 cursor-pointer transition-colors group/row">
                        <span class="text-gray-500 font-medium">' . $label2 . '</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[#1E293B] font-bold text-lg">' . $val2 . '</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover/row:text-blue-500 transition-colors"></i>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $html .= '</div>';

        echo $html;
    }

    public function get_breakdown_data()
    {
        error_reporting(0);
        // Use exact case for model path
        $this->load->model('incoming/merchant_model');

        $nik      = $this->input->post('nik');
        $position = $this->input->post('position');
        $status   = $this->input->post('status'); // edc, qris, edc_qris
        $part     = $this->input->post('part');   // IS, BS, PR, PI
        $from     = $this->input->post('date_from');
        $to       = $this->input->post('date_to');
        $source   = $this->input->post('source') ?: 'all';
        $search   = $this->input->post('search');
        $start    = (int)$this->input->post('start');
        $length   = (int)$this->input->post('length');

        $array_leader = array('BSH', 'RSM', 'ASM', 'SPV');

        // Fetch Data
        try {
            if (in_array($position, $array_leader)) {
                $lead_col = 't2.' . $position . '_Code';
                $res = $this->merchant_model->detBreakdownMerchantLeader($lead_col, $nik, strtoupper($status), $part, $from, $to, $source);
            } else {
                $res = $this->merchant_model->detBreakdownMerchantDSR($nik, strtoupper($status), $part, $from, $to, $source);
            }
        } catch (Exception $e) {
            $res = array();
        }

        // Standardize result as array
        if (!is_array($res)) {
            $res = array();
        }
        
        $data = array();
        $no = $start;
        
        // Filter search locally
        $filtered = array();
        foreach($res as $row) {
            if(!empty($search)) {
                $q = strtolower($search);
                $m_name = strtolower(is_object($row) ? ($row->Merchant_Name ?? '') : ($row['Merchant_Name'] ?? ''));
                $o_name = strtolower(is_object($row) ? ($row->Owner_Name ?? '') : ($row['Owner_Name'] ?? ''));
                $s_code = strtolower(is_object($row) ? ($row->Sales_Code ?? '') : ($row['Sales_Code'] ?? ''));
                $s_name = strtolower(is_object($row) ? ($row->Sales_Name ?? '') : ($row['Sales_Name'] ?? ''));
                
                if(strpos($m_name, $q) === false && strpos($o_name, $q) === false && 
                   strpos($s_code, $q) === false && strpos($s_name, $q) === false) {
                    continue;
                }
            }
            $filtered[] = $row;
        }

        $recordsTotal = count($res);
        $recordsFiltered = count($filtered);

        // Pagination
        $paginated = array_slice($filtered, $start, $length);

        foreach ($paginated as $row) {
            $data[] = array(
                ++$no,
                is_object($row) ? ($row->Merchant_Name ?? '-') : ($row['Merchant_Name'] ?? '-'),
                is_object($row) ? ($row->Owner_Name ?? '-') : ($row['Owner_Name'] ?? '-'),
                is_object($row) ? ($row->Sales_Code ?? '-') : ($row['Sales_Code'] ?? '-'),
                is_object($row) ? ($row->Sales_Name ?? '-') : ($row['Sales_Name'] ?? '-')
            );
        }

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(array(
            "draw"            => (int)$this->input->post('draw'),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data,
        ));
    }

    private function _maskingname($name)

    {
        $words = explode(" ", $name);
        if (count($words) > 1) {
            $result = $words[0] . " ";
            $len    = strlen($words[1]);
            if ($len > 2) {
                $result .= substr($words[1], 0, 2) . str_repeat("*", max(1, $len - 2));
            } else {
                $result .= $words[1];
            }
            return $result;
        }
        $len = strlen($name);
        if ($len > 3) {
            return substr($name, 0, 3) . str_repeat("*", $len - 3);
        }
        return $name . str_repeat("*", max(0, 6 - $len));
    }
}
