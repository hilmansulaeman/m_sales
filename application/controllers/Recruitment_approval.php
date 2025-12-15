<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_approval extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('auth', 'template', 'unit_test', 'form_validation'));
        $this->load->model('recruitment_approval_model', 'model');
        $this->position = $this->session->userdata('position');
    }

    function index()
    {
        if (in_array($this->position, ['SPV', 'ASM', 'RSM', 'BSH'])) {

            $this->template->set('title', 'Data Approval');
            $this->template->load('template', 'recruitment_approval/index');
        } else {
            redirect('');
        }
    }

    function get_data()
    {
        $data = array();
        $no = $this->input->post('start');
        $query = $this->model->get_datatables();
        // cekvar($query);


        foreach ($query as $row) {
            $dataRow = array(
                ++$no,
                '
                <h5><strong>Request ID: ' . $row->Recruitment_ID . '</strong></h5>
                <span style="color:black;">
                    <b>Name</b>: ' . $row->Name . '<br>
                    <b>Product</b>: ' . $row->Product . '<br>
                    <b>Area</b>: ' . $row->Branch . '<br>
                    <b>Position</b>: ' . $row->Position . '<br>
                    <b>Level</b>: ' . $row->Level . '
                
                </span>
                &nbsp;',
            );

            if ($row->SM_Code == $row->ASM_Code) {
                $spv_name = 'DUMMY';
            } else {
                $spv_name = $row->SM_Name;
            }

            if ($this->session->userdata('position') == "ASM") {
                // $dataRow[] = $row->SM_Name;
                $dataRow[] = $spv_name;
            }

            if ($this->session->userdata('position') == "RSM") {
                // $dataRow[] = $row->SM_Name;
                $dataRow[] = $spv_name;
                $dataRow[] = $row->ASM_Name;
            }

            if ($this->session->userdata('position') == "BSH") {
                // $dataRow[] = $row->SM_Name;
                $dataRow[] = $spv_name;
                $dataRow[] = $row->ASM_Name;
                $dataRow[] = $row->RSM_Name;
            }

            $dataRow[] = $row->Status;
            $dataRow[] = '<a href="' . site_url('recruitment_approval/approve_data/' . $row->Recruitment_ID) . '" class="btn btn-success btn-icon btn-circle"><i class="fa fa-eye"></i></a>';

            $data[] = $dataRow;
        }


        $recordsTotal = $this->model->count_filtered();
        // var_dump($recordsTotal);
        // die;
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


    function approve_data($rec_id)
    {

        $cek = $this->model->get_by_id($rec_id);
        if (empty($cek)) {
            redirect('recruitment_approval');
            return;
        }

        $data['image_url']      = $this->config->item('image_url_pelamar');
        $data['query_doc']      = $this->model->get_data_recruitment_documents($rec_id);
        $data['query']          = $this->model->get_by_id($rec_id);

        //load view
        $this->template->set('title', $this->config->item('site_name') . ' | Detail Data Approval');
        $this->template->load('template', 'recruitment_approval/view', $data);
    }

    public function submit($rec_id)
    {
        $nik = $this->session->userdata('sl_code');
        $sess_name     = $this->session->userdata('realname');
        $sess_position = $this->session->userdata('position');



        $getData = $this->model->smCode($nik); //DSR_Code, Name, Position

        $smNIK = $getData->data->DSR_Code;
        $smName = $getData->data->Name;
        $smPosition = $getData->data->Position;


        // $data_update = [
        //     'rec_id'        => $rec_id,
        //     'smNIK'         => $smNIK,
        //     'smName'        => $smName,
        //     'smPosition'    => $smPosition,
        //     'updated_by'    => $sess_name,
        //     'position'      => $sess_position
        // ];
        $data_update = [
            'rec_id'      => $rec_id,
            'nik'         => $nik,
            'name'        => $sess_name,
            'position'    => $sess_position,
            'updated_by'  => $sess_name,
        ];
        // cekvar($data_update);
        $result = $this->model->insertUpdate($data_update);

        echo json_encode($result);
    }

    function return($rec_id)
    {

        $this->validation();
        if ($this->form_validation->run() == false) {
            // Kirim pesan error dalam bentuk JSON
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
        } else {
            $getData       = $this->model->get_by_id($rec_id);
            $reason       = $this->input->post('reason');
            $rec_id       = $getData->Recruitment_ID;
            $sess_name    = $this->session->userdata('realname');

            $data_return = [
                'rec_id'    => $rec_id,
                // 'app_id'    => $app_id,
                'reason'    => $reason,
                'updated_by' => $sess_name,
            ];

            $result = $this->model->returnData($data_return);

            echo json_encode($result);
        }
    }

    private function validation()
    {
        $this->form_validation->set_rules('reason', 'Reason', 'required', [
            'required' => 'Reason wajib diisi!'
        ]);
    }
}
