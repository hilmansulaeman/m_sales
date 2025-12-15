<?php defined('BASEPATH') or exit('No direct script access allowed');
class Recruitment_return extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('auth', 'template', 'unit_test', 'form_validation'));
        $this->load->model('recruitment_return_model', 'model');
        $this->position = $this->session->userdata('position');
    }

    function index()
    {
        if (in_array($this->position, ['ASM', 'RSM', 'BSH'])) {
            $this->template->set('title', 'Data Return');
            $this->template->load('template', 'recruitment_return/index');
        } else {
            redirect('');
        }
    }


    function get_data()
    {
        $data = array();
        $no = $this->input->post('start');
        $query = $this->model->get_datatables();

        foreach ($query  as $row) {
            $data[] = array(
                ++$no,
                $row->Recruitment_ID,
                $row->Name,
                $row->Product,
                $row->Branch,
                $row->Position,
                $row->Level,
                $row->Status,
                '<a href="' . site_url('recruitment_return/return_data/' . $row->Recruitment_ID) . '" class="btn btn-success btn-icon btn-circle"><i class="fa fa-eye"></i></a>'
            );
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->model->count_filtered(),
            "recordsFiltered" => $this->model->count_filtered(),
            "data" => $data,
        );

        //output format JSON
        echo json_encode($output);
    }

    function return_data($rec_id)
    {
        $cek = $this->model->get_by_id($rec_id);
        if (empty($cek)) {
            redirect('recruitment_return');
            return;
        }

        $data['image_url']      = $this->config->item('image_url_pelamar');
        $data['query_doc']      = $this->model->get_data_recruitment_documents($rec_id);
        $data['query']          = $this->model->get_by_id($rec_id);

        //load view
        $this->template->set('title', $this->config->item('site_name') . ' | Detail Data Return');
        $this->template->load('template', 'recruitment_return/view', $data);
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
            $getData      = $this->model->get_by_id($rec_id);
            $reason       = $this->input->post('reason');
            $rec_id       = $getData->Recruitment_ID;
            $sess_name    = $this->session->userdata('realname');

            $data_return = [
                'rec_id'    => $rec_id,
                // 'app_id'    => $app_id,
                'reason'    => $reason,
                'updated_by' => $sess_name,
            ];
            // cekvar($data_return);

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
