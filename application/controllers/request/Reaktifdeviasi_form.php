<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reaktif_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('request/reaktifdeviasi_form_model');
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);
        $data['kategori'] = $this->reaktifdeviasi_form_model->get_kategori_reaktifdeviasi();


        //load view
        $this->template->set('title', 'Data Form ReaktifDeviasi');
        $this->template->load('template', 'request/reaktifdeviasi/index', $data);
    }

    function kategori()
    {
        $category = $this->input->post('kategori');
        // var_dump($category == 'DEVIASI'); die;
        if ($category == 'DEVIASI') {
            redirect('request/deviasi_form/add');
        } else {
            redirect('request/reaktif_form/add');
        }
    }

    function get_data_reaktifdeviasi()
    {
        $query = $this->reaktifdeviasi_form_model->get_datatables();
        $count_data = $this->reaktifdeviasi_form_model->count_all()->row();
        $slug_form = $this->uri->segment(2);

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            $form = '';
            // var_dump($row->Category == 'REAKTIF'); die;
            if ($row->Category == 'REAKTIF') {
                $form = 'reaktif_form';
            } else {
                $form = 'deviasi_form';
            }

            $action = '<a href="' . site_url() . "request/" . $form . "/detail/" . $request_id . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            $cek1 = $this->db->query("SELECT  
                                    COUNT(Request_ID) AS total,
                                    SUM(IF(Hit_Code IN('1005','1006','1007'),1,0)) AS approved,
                                    SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                                    SUM(IF(Hit_Code = '1010',1,0)) AS cancel
                                    FROM internal_sms.data_request_user
                                    WHERE Request_ID = '$request_id'
                                    ");
            $cek = $cek1->row();

            $data[] = array(
                ++$no,
                $row->Category,
                $row->Efective_Date,
                $row->Created_Date,
                $created_by,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                '<span class="btn btn-xs btn-warning">' . $cek->cancel . '</span>',
                $action
            );
            
            // var_dump($data); die;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->reaktifdeviasi_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }



    function detail($request_id)
    {

        $slug_form = $this->uri->segment(2);
        $query = $this->db->query("SELECT * FROM ref_form WHERE Slug = '$slug_form'");
        $row = $query->row();
        $form_id = $row->Form_ID;

        $position = $this->session->userdata('position');

        $list = array('SPV', 'ASM', 'RSM', 'BSH');
        
        

        if (in_array($position, $list)) {
            $data['sales'] = $this->deviasi_form_model->get_all_sales_where();
        } else {
            $data['sales'] = $this->deviasi_form_model->get_all_sales();
        }

        $data['request_user'] = $this->demosi_form_model->get_demosi_detail($request_id, $form_id);
        $data['db'] = $this->demosi_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/reaktif_form/detail', $data);
    }

    function delete_detail($request_id, $request_user_id)
    {
        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/reaktif_form/detail/' . $request_id);
    }
}
