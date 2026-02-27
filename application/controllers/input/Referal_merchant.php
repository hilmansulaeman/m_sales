<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Referal_merchant extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template', 'form_validation'));
        $this->load->model('input/Referal_merchant_model', 'model');
    }
    

    public function index()
    {
        $data['title'] = 'Referal Merchant';
        $this->template->load('template', 'input/referal_merchant/index', $data);    
    }

    public function getDataTable()
    {
        $apiResponse = $this->model->getDataTable();

        if (empty($apiResponse->data)) {
            echo json_encode([
                "draw" => intval($this->input->post('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
            return;
        }

        $data = array();
        $no = $this->input->post('start');

        foreach($apiResponse->data as $row){

        $phone_raw = html_escape($row->mobile_phone_number);
        $phone_masked = substr($phone_raw, 0, 4) . '***';

        // Buat tampilan nomor dengan tombol mata
        $phone_display = '
            <span class="phone-container">
                <span class="phone-text">' . $phone_masked . '</span>
                <a href="javascript:void(0)" 
                class="text-info ml-1" 
                onclick="togglePhone(this, \''.$phone_raw.'\', \''.$phone_masked.'\')">
                    <i class="fa fa-eye"></i>
                </a>
            </span>';
            
            $data[] = array(
                ++$no,
                html_escape($row->merchant_name),
                html_escape($row->pic_merchant),
                $phone_display,
                html_escape($row->address),
                html_escape($row->sales_code),
                html_escape($row->sales_name),
                
                '<a href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$row->id."'".')" class="btn btn-sm btn-info">
                    <i class="fa fa-edit"></i>
                </a>'
            );
        }

        $output = array(
            "draw" => intval($this->input->post('draw')),
            // AMBIL DARI API, JANGAN PAKAI count()
            "recordsTotal" => $apiResponse->recordsTotal, 
            "recordsFiltered" => $apiResponse->recordsFiltered, 
            "data" => $data
        );

        echo json_encode($output);
    }

   function add()
    {
        $data = array(
            'merchant_name'       => trim($this->input->post('merchant_name')),
            'pic_merchant'        => trim($this->input->post('pic_merchant')),
            'mobile_phone_number' => trim($this->input->post('mobile_phone_number')),
            'address'             => trim($this->input->post('address')),
            'created_by'          => $this->session->userdata('realname'),
            'sales_code'          => $this->session->userdata('sl_code'),
            'sales_name'          => $this->session->userdata('realname'),
        );
        $apiResponse = $this->model->insert($data);

        if (!isset($apiResponse['status'])) {
            echo json_encode([
                "status"  => false,
                "message" => "Invalid API response"
            ]);
            return;
        }

        echo json_encode([
            "status"  => $apiResponse['status'],
            "message" => $apiResponse['message']
        ]);
    }

    function get_by_id($id)
    {
        if (empty($id)) {
            echo json_encode(["status" => false, "message" => "ID is required"]);
            return;
        }

        $geById = $this->model->get_by_id($id);
        
        header('Content-Type: application/json');
        echo json_encode($geById);
    }

    public function update() {
    $id = $this->input->post('id');
    $data = [
        'merchant_name'       => htmlspecialchars(strip_tags($this->input->post('merchant_name')), ENT_QUOTES, 'UTF-8'),
        'pic_merchant'        => htmlspecialchars(strip_tags($this->input->post('pic_merchant')), ENT_QUOTES, 'UTF-8'),      
        'mobile_phone_number' => htmlspecialchars(strip_tags($this->input->post('mobile_phone_number')), ENT_QUOTES, 'UTF-8'),
        'address'             => htmlspecialchars(strip_tags($this->input->post('address')), ENT_QUOTES, 'UTF-8'),
        'updated_by'          => $this->session->userdata('name'),
        'updated_date'        => date('Y-m-d H:i:s')
    ];

    $apiResponse = $this->model->update($id, $data);

    if (!isset($apiResponse['status'])) {
            echo json_encode([
                "status"  => false,
                "message" => "Invalid API response"
            ]);
            return;
        }

        echo json_encode([
            "status"  => $apiResponse['status'],
            "message" => $apiResponse['message']
        ]);
    }

}