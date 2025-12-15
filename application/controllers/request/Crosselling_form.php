<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Crosselling_form extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template', 'image_lib'));
        $this->load->model('request/crosselling_form_model');
    }

    public function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Form Crosselling');
        $this->template->load('template', 'request/crosselling_form/index');
    }

    public function get_data_crosselling()
    {
        $query = $this->crosselling_form_model->get_datatables();
        $count_data = $this->crosselling_form_model->count_all()->row();

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;

            $action = '<a href="' . site_url() . "request/crosselling_form/detail/" . $request_id . "/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            $this->db->select("
                COUNT(Request_ID) AS total,
                SUM(IF(Hit_Code IN('1006'),1,0)) AS completed,
                SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            ");
            $this->db->from("internal_sms.data_request_user");
            $this->db->where("Request_ID", $request_id);
            $cek1 = $this->db->get();

            $cek = $cek1->row();

            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $created_by,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->completed . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                '<span class="btn btn-xs btn-warning">' . $cek->cancel . '</span>',
                $action,
            );

            // var_dump($data); die;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->crosselling_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add_old()
    {
        $sales_code = $this->session->userdata('sl_code');
        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $getData = $this->db->get_where('ref_category_structure', array('Category' => 'CROSSELLING'))->row();

        $data['categoryid'] = $getData->Category_ID;
        $data['category'] = $getData->Category;

        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position_, $list)) {
            $data['sales'] = $this->crosselling_form_model->getData_api($sales_code, 'add');
        } else {
            $data['sales'] = $this->crosselling_form_model->getData_api('', 'add');
        }

        $this->template->set('title', 'Add Form Crosselling');
        $this->template->load('template', 'request/crosselling_form/add', $data);
    }

    public function add()
    {
        $reason = $this->input->post('reason');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('efective_date', 'Tanggal Efektif', 'trim|required', array(
            'required' => '{field} harus di isi!',
        ));
        $this->form_validation->set_rules('data_employee_id', 'DSR Code', 'trim|required', array(
            'required' => '{field} harus di isi!',
        ));
        $this->form_validation->set_rules('reason', 'Reason', 'trim|required|callback_check_string', array(
            'required' => '{field} harus di isi!',
        ));

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == false) {

            $sales_code = $this->session->userdata('sl_code');
            $position_ = $this->session->userdata('position');
            $position = $this->session->set_userdata('position', $position_);
    
            $getData = $this->db->get_where('ref_category_structure', array('Category' => 'CROSSELLING'))->row();
    
            $data['categoryid'] = $getData->Category_ID;
            $data['category'] = $getData->Category;
    
            $list = array('ASM', 'RSM', 'BSH');
    
            if (in_array($position_, $list)) {
                $data['sales'] = $this->crosselling_form_model->getData_api($sales_code, 'add');
            } else {
                $data['sales'] = $this->crosselling_form_model->getData_api('', 'add');
            }
    
            $this->template->set('title', 'Add Form Crosselling');
            $this->template->load('template', 'request/crosselling_form/add', $data);
        } else {

            //GET Request_ID dari table ref_form
            $slug_form = $this->uri->segment(2); //slug form
            $uri3 = $this->input->post('uri3');
            $uri4 = $this->input->post('uri4');
    
            $row_form = $this->db->get_where('internal_sms.ref_form', array('Slug' => $slug_form))->row();
            $form_id = $row_form->Form_ID;
    
            $efective_date = $this->input->post('efective_date');
            $explode = explode('|', $this->input->post('data_employee_id'));
    
            $employee_id = $explode[0];
            $regnoID = $explode[1];
            $sales_code = $explode[2];
            $sales_name = $explode[3];
            $position = $explode[4];
            $level = $explode[5];
            $product = $explode[6];
    
            $reason = $this->input->post('reason');
            // var_dump($product);
            // die;
    
            $category_id = $this->input->post('category_id');
            $category = $this->input->post('category');
    
            if ($uri3 == 'add') {
                //INSERT TABLE data_request jika uri->segment(3) nya add
                $request = array(
                    'Form_ID' => $form_id,
                    'Efective_Date' => $efective_date,
                    'Category' => strtoupper($category),
                    'Created_By' => $this->session->userdata('sl_code'),
                    'Created_Name' => $this->session->userdata('realname'),
                );
                $this->db->insert('internal_sms.data_request', $request);
                $request_id = $this->db->insert_id();
            } else {
                $request_id = $uri4;
            }
    
            $arr = array('1002', '1004', '1007', '1010');
    
            $this->db->select('a.*');
            $this->db->from('data_request_user a');
            $this->db->join('data_request b', 'b.Request_ID = a.Request_ID', 'inner');
            $this->db->where('a.Sales_Code', $sales_code);
            $this->db->where('b.Category', $category);
            $this->db->where('b.Form_ID', $form_id);
            $this->db->where_not_in('a.Hit_Code', $arr);
            $cek = $this->db->get()->num_rows();
    
            if ($cek > 0) {
                //MESSAGE
                $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
                // $this->db->where('Request_ID', $request_id);
                // $this->db->delete('data_request');
    
                redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
            } else {
    
                //get RSM Code
                $approval_code = $explode[7];
                $approval_name = $explode[8];
                $approval_position = 'RSM';
    
                if ($approval_code == '0') {
                    //get BSH Code
                    $approval_code = $explode[9];
                    $approval_name = $explode[10];
                    $approval_position = 'BSH';
                }
    
                $note = $reason . ' - ' . $efective_date;
    
                $request_user = array(
                    //'Request_User_ID'              => ini auto increment
                    'Regno_ID' => $regnoID,
                    'Sales_Code' => $sales_code,
                    'Sales_Name' => $sales_name,
                    'Position' => $position,
                    'Level' => $level,
                    'Product' => $product,
                    'Approval' => $approval_code,
                    'Approval_Name' => $approval_name,
                    'Approval_Status' => '0',
                    'Status_Date' => date('Y-m-d'),
                    'Reason' => $reason,
                    'Note' => $note,
                    'Hit_Code' => '1000',
                    'Request_ID' => $request_id,
                );
                $this->db->insert('internal_sms.data_request_user', $request_user);
    
                $request_user_id = $this->db->insert_id();
    
                $data_request_approval = array(
                    'Request_ID' => $request_id,
                    'Request_User_ID' => $request_user_id,
                    'Sales_Code' => $approval_code,
                    'Sales_Name' => $approval_name,
                    'Position' => $approval_position,
                    'Status' => '0',
                    'Created_By' => $this->session->userdata('username'),
                );
                $this->db->insert('internal_sms.data_request_approval', $data_request_approval);
    
                //MESSAGE
                $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");
    
                redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
            }
        }
    }

    public function save_request_crosselling($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request

        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form

        $row_form = $this->db->get_where('internal_sms.ref_form', array('Slug' => $slug_form))->row();
        $form_id = $row_form->Form_ID;

        $efective_date = $this->input->post('efective_date');
        $explode = explode('|', $this->input->post('data_employee_id'));

        $employee_id = $explode[0];
        $regnoID = $explode[1];
        $sales_code = $explode[2];
        $sales_name = $explode[3];
        $position = $explode[4];
        $level = $explode[5];
        $product = $explode[6];

        $reason = $this->input->post('reason');
        // var_dump($product);
        // die;

        $category_id = $this->input->post('category_id');
        $category = $this->input->post('category');

        if ($uri3 == 'add') {
            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array(
                'Form_ID' => $form_id,
                'Efective_Date' => $efective_date,
                'Category' => strtoupper($category),
                'Created_By' => $this->session->userdata('sl_code'),
                'Created_Name' => $this->session->userdata('realname'),
            );
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        } else {
            $request_id = $uri4;
        }

        $arr = array('1002', '1004', '1007', '1010');

        $this->db->select('a.*');
        $this->db->from('data_request_user a');
        $this->db->join('data_request b', 'b.Request_ID = a.Request_ID', 'inner');
        $this->db->where('a.Sales_Code', $sales_code);
        $this->db->where('b.Category', $category);
        $this->db->where('b.Form_ID', $form_id);
        $this->db->where_not_in('a.Hit_Code', $arr);
        $cek = $this->db->get()->num_rows();

        if ($cek > 0) {
            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
            // $this->db->where('Request_ID', $request_id);
            // $this->db->delete('data_request');

            redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
        } else {

            //get RSM Code
            $approval_code = $explode[7];
            $approval_name = $explode[8];
            $approval_position = 'RSM';

            if ($approval_code == '0') {
                //get BSH Code
                $approval_code = $explode[9];
                $approval_name = $explode[10];
                $approval_position = 'BSH';
            }

            $note = $reason . ' - ' . $efective_date;

            $request_user = array(
                //'Request_User_ID'              => ini auto increment
                'Regno_ID' => $regnoID,
                'Sales_Code' => $sales_code,
                'Sales_Name' => $sales_name,
                'Position' => $position,
                'Level' => $level,
                'Product' => $product,
                'Approval' => $approval_code,
                'Approval_Name' => $approval_name,
                'Approval_Status' => '0',
                'Status_Date' => date('Y-m-d'),
                'Reason' => $reason,
                'Note' => $note,
                'Hit_Code' => '1000',
                'Request_ID' => $request_id,
            );
            $this->db->insert('internal_sms.data_request_user', $request_user);

            $request_user_id = $this->db->insert_id();

            $data_request_approval = array(
                'Request_ID' => $request_id,
                'Request_User_ID' => $request_user_id,
                'Sales_Code' => $approval_code,
                'Sales_Name' => $approval_name,
                'Position' => $approval_position,
                'Status' => '0',
                'Created_By' => $this->session->userdata('username'),
            );
            $this->db->insert('internal_sms.data_request_approval', $data_request_approval);

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
        }
    }

    public function detail_old($request_id, $category_id)
    {
        $sales_code = $this->session->userdata('sl_code');

        if ($this->idor->detail_request($request_id) == 0) return redirect('update_error');

        $data['category_id'] = $category_id;
        $getCategory = $this->db->get_where('ref_category_structure', array('Category_ID' => $category_id))->row();
        $data['category'] = $getCategory->Category;

        $slug_form = $this->uri->segment(2);
        $query = $this->db->get_where("ref_form", array("Slug" => "$slug_form"));
        $row = $query->row();
        $form_id = $row->Form_ID;

        $position_ = $this->session->userdata('position');
        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position_, $list)) {
            $data['sales'] = $this->crosselling_form_model->getData_api($sales_code, 'add');
        } else {
            $data['sales'] = $this->crosselling_form_model->getData_api('', 'add');
        }

        $data['request_user'] = $this->crosselling_form_model->get_crosselling_detail($request_id, $form_id);
        $data['db'] = $this->crosselling_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/crosselling_form/detail', $data);
    }

    public function detail($request_id, $category_id)
    {
        $reason = $this->input->post('reason');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('data_employee_id', 'DSR Code', 'trim|required', array(
            'required' => '{field} harus di isi!',
        ));
        $this->form_validation->set_rules('reason', 'Reason', 'trim|required|callback_check_string', array(
            'required' => '{field} harus di isi!',
        ));

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == false) {

            $sales_code = $this->session->userdata('sl_code');

            if ($this->idor->detail_request($request_id) == 0) return redirect('update_error');

            $data['category_id'] = $category_id;
            $getCategory = $this->db->get_where('ref_category_structure', array('Category_ID' => $category_id))->row();
            $data['category'] = $getCategory->Category;

            $slug_form = $this->uri->segment(2);
            $query = $this->db->get_where("ref_form", array("Slug" => "$slug_form"));
            $row = $query->row();
            $form_id = $row->Form_ID;

            $position_ = $this->session->userdata('position');
            $list = array('ASM', 'RSM', 'BSH');

            if (in_array($position_, $list)) {
                $data['sales'] = $this->crosselling_form_model->getData_api($sales_code, 'add');
            } else {
                $data['sales'] = $this->crosselling_form_model->getData_api('', 'add');
            }

            $data['request_user'] = $this->crosselling_form_model->get_crosselling_detail($request_id, $form_id);
            $data['db'] = $this->crosselling_form_model->get_by($request_id)->row();

            //load view
            $this->template->set('title', 'Data Request Detail');
            $this->template->load('template', 'request/crosselling_form/detail', $data);

        } else {

            //GET Request_ID dari table ref_form
            $slug_form = $this->uri->segment(2); //slug form
            $uri3 = $this->input->post('uri3');
            $uri4 = $this->input->post('uri4');
    
            $row_form = $this->db->get_where('internal_sms.ref_form', array('Slug' => $slug_form))->row();
            $form_id = $row_form->Form_ID;
    
            $efective_date = $this->input->post('efective_date');
            $explode = explode('|', $this->input->post('data_employee_id'));
    
            $employee_id = $explode[0];
            $regnoID = $explode[1];
            $sales_code = $explode[2];
            $sales_name = $explode[3];
            $position = $explode[4];
            $level = $explode[5];
            $product = $explode[6];
    
            $reason = $this->input->post('reason');
            // var_dump($product);
            // die;
    
            $category_id = $this->input->post('category_id');
            $category = $this->input->post('category');
    
            if ($uri3 == 'add') {
                //INSERT TABLE data_request jika uri->segment(3) nya add
                $request = array(
                    'Form_ID' => $form_id,
                    'Efective_Date' => $efective_date,
                    'Category' => strtoupper($category),
                    'Created_By' => $this->session->userdata('sl_code'),
                    'Created_Name' => $this->session->userdata('realname'),
                );
                $this->db->insert('internal_sms.data_request', $request);
                $request_id = $this->db->insert_id();
            } else {
                $request_id = $uri4;
            }
    
            $arr = array('1002', '1004', '1007', '1010');
    
            $this->db->select('a.*');
            $this->db->from('data_request_user a');
            $this->db->join('data_request b', 'b.Request_ID = a.Request_ID', 'inner');
            $this->db->where('a.Sales_Code', $sales_code);
            $this->db->where('b.Category', $category);
            $this->db->where('b.Form_ID', $form_id);
            $this->db->where_not_in('a.Hit_Code', $arr);
            $cek = $this->db->get()->num_rows();
    
            if ($cek > 0) {
                //MESSAGE
                $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
                // $this->db->where('Request_ID', $request_id);
                // $this->db->delete('data_request');
    
                redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
            } else {
    
                //get RSM Code
                $approval_code = $explode[7];
                $approval_name = $explode[8];
                $approval_position = 'RSM';
    
                if ($approval_code == '0') {
                    //get BSH Code
                    $approval_code = $explode[9];
                    $approval_name = $explode[10];
                    $approval_position = 'BSH';
                }
    
                $note = $reason . ' - ' . $efective_date;
    
                $request_user = array(
                    //'Request_User_ID'              => ini auto increment
                    'Regno_ID' => $regnoID,
                    'Sales_Code' => $sales_code,
                    'Sales_Name' => $sales_name,
                    'Position' => $position,
                    'Level' => $level,
                    'Product' => $product,
                    'Approval' => $approval_code,
                    'Approval_Name' => $approval_name,
                    'Approval_Status' => '0',
                    'Status_Date' => date('Y-m-d'),
                    'Reason' => $reason,
                    'Note' => $note,
                    'Hit_Code' => '1000',
                    'Request_ID' => $request_id,
                );
                $this->db->insert('internal_sms.data_request_user', $request_user);
    
                $request_user_id = $this->db->insert_id();
    
                $data_request_approval = array(
                    'Request_ID' => $request_id,
                    'Request_User_ID' => $request_user_id,
                    'Sales_Code' => $approval_code,
                    'Sales_Name' => $approval_name,
                    'Position' => $approval_position,
                    'Status' => '0',
                    'Created_By' => $this->session->userdata('username'),
                );
                $this->db->insert('internal_sms.data_request_approval', $data_request_approval);
    
                //MESSAGE
                $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");
    
                redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
            }
        }
    }

    public function delete_detail($request_id, $request_user_id, $category_id)
    {
        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/crosselling_form/detail/' . $request_id . '/' . $category_id);
    }

    public function send($request_id)
    {

        $data_request = array(
            'Is_Send' => '1',
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('data_request', $data_request);

        $data_update = array(
            'Hit_Code' => '1008',
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->where('Hit_Code', '1000');
        $this->db->update('internal_sms.data_request_user', $data_update);

        $request_user_id = $this->input->post('request_user_id');

        $data_log = array();
        foreach ($request_user_id as $form => $val) {
            $data_log[] = array(
                'Request_ID' => $request_id,
                'Request_User_ID' => $_POST['request_user_id'][$form],
                'Hit_Code' => '1008',
                'Description' => 'New Request From ASM',
                'Updated_By' => $this->session->userdata('realname'),
                'Updated_Date' => date('Y-m-d h:i:s'),
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

        redirect('request/crosselling_form');
    }

    public function getDate($val)
    {
        $sales_code = $this->session->userdata('sl_code');
        $this->db->select('a.*, b.Category_ID');
        $this->db->from('data_request a');
        $this->db->join('ref_category_structure b', 'b.Category = a.Category', 'left');
        $this->db->where('Created_By', $sales_code)->where('a.Efective_Date', $val)->where('a.Category', 'CROSSELLING')->where('Is_Send', '0');
        $query = $this->db->get();
        $row = $query->row();

        if ($query->num_rows() > 0) {
            echo json_encode(array('status' => true, 'request_id' => $row->Request_ID, 'category_id' => $row->Category_ID));
        } else {
            echo json_encode(array('status' => false));
        }

    }

}