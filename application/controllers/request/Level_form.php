<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Level_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('request/level_form_model');

        // $this->db2 = $this->load->database('hrd', TRUE);
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        $data['category'] = $this->db->get_where('ref_category_structure', array('Category_Form' => 'LEVEL'));

        //load view
        $this->template->set('title', 'Data Form Level');
        $this->template->load('template', 'request/level_form/index', $data);
    }

    function get_data_level()
    {
        $query = $this->level_form_model->get_datatables();
        $count_data = $this->level_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            $action = '<a href="' . site_url() . "request/level_form/detail/" . $request_id . "/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            // $cek1 = $this->db->query("SELECT  
            //                         COUNT(Request_ID) AS total,
            //                         SUM(IF(Hit_Code IN('1009','1006','1007'),1,0)) AS approved,
            //                         SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
            //                         SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            //                         FROM internal_sms.data_request_user
            //                         WHERE Request_ID = '$request_id'
            //                         ");
            // $cek = $cek1->row();

            $this->db->select("
                COUNT(Request_ID) AS total,
                SUM(IF(Hit_Code IN('1009','1006','1007'),1,0)) AS approved,
                SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            ");
            $this->db->from("internal_sms.data_request_user");
            $this->db->where("Request_ID", $request_id);
            $cek1 = $this->db->get();

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
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->level_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add()
    {
        $sales_code = $this->session->userdata('sl_code');
        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $category_id = $this->uri->segment('4');
        $getCategory = $this->db->get_where('ref_category_structure', array('Category_ID' => $category_id))->row();
        $data['category'] = $getCategory->Category;

        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position_, $list) and $category_id == '5') {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'spv_where', 'add');
        } elseif (in_array($position_, $list) and $category_id == '9') {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'cc_where', 'add');
        } else {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'sales_where', 'add');
        }

        //load view
        $this->template->set('title', 'Tambah Form ' . $getCategory->Category);
        $this->template->load('template', 'request/level_form/add', $data);
    }

    function save_request_level($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request 

        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form

        $row_form = $this->db->get_where('internal_sms.ref_form', array('Slug' => $slug_form))->row();
        $form_id = $row_form->Form_ID;

        $efective_date = $this->input->post('efective_date');
        $explode = explode('|', $this->input->post('data_employee_id'));

        $employee_id = $explode[0];
        $regnoID    = $explode[1];
        $sales_code = $explode[2];
        $sales_name = $explode[3];
        $position   = $explode[4];
        $level      = $explode[5];
        $product    = $explode[6];

        // echo $explode[3];die();

        $reason     = $this->input->post('reason');
        $note = $this->input->post('reason').' - '.$this->input->post('efective_date');

        $category_id = $this->input->post('category_id');
        $category = $this->input->post('category');

        if ($uri3 == 'add') {
            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array(
                'Form_ID'       => $form_id,
                'Efective_Date' => $efective_date,
                'Category'      => strtoupper($category),
                'Created_By'    => $this->session->userdata('sl_code'),
                'Created_Name'  => $this->session->userdata('realname')
            );
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        } else {
            $request_id = $uri4;
        }

        // $query_cek = $this->db->query("SELECT a.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Category = '$category' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1002','1004','1007','1010') ");
        // $cek = $query_cek->num_rows();

        $arr = array('1002','1004','1007','1010');

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

            redirect('request/level_form/detail/' . $request_id . '/' . $category_id);
        } else {

            if ($category == 'PROMOSI' || $category == 'LEVEL CC DIAMOND / BRANCH') {

                //get RSM Code
                // $rowcek = $this->level_form_model->getData_api($sales_code, '', 'get_structure');
                // $approval_code = $rowcek->RSM_Code;
                // $approval_name = $rowcek->RSM_Name;
                // $approval_position = 'RSM';
                // var_dump($approval_code);
                // die;
                //get BSH Code

                // if ($approval_code == '0') {
                //     $approval_code = $rowcek->BSH_Code;
                //     $approval_name = $rowcek->BSH_Name;
                //     $approval_position = 'BSH';
                // }

                $approval_code = $explode[7];
                $approval_name = $explode[8];
                $approval_position = 'RSM';

                if ($approval_code == '0') {
                    $approval_code = $explode[9];
                    $approval_name = $explode[10];
                    $approval_position = 'BSH';
                }

                if($category == 'PROMOSI') {
                    $note = $reason.' - '.$efective_date;
                }
                else {
                    $note = $this->input->post('new_level').' - '.$reason.' - '.$efective_date;
                }

                $request_user = array(
                    //'Request_User_ID'              => ini auto increment
                    'Regno_ID'                  => $regnoID,
                    'Sales_Code'                => $sales_code,
                    'Sales_Name'                => $sales_name,
                    'Position'                  => $position,
                    'Level'                     => $level,
                    'Product'                   => $product,
                    // 'Approval'                  => $approval_code,
                    // 'Approval_Name'             => $approval_name,
                    'Approval_Status'           => '0',
                    'Status_Date'               => date('Y-m-d'),
                    'Reason'                    => $reason,
                    'Note'                      => $note,
                    'Hit_Code'                  => '1000',
                    'Request_ID'                => $request_id
                );
                $this->db->insert('internal_sms.data_request_user', $request_user);

                $request_user_id = $this->db->insert_id();

                $data_request_approval = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Sales_Code'        => $approval_code,
                    'Sales_Name'        => $approval_name,
                    'Position'          => $approval_position,
                    'Status'            => '0',
                    'Created_By'        => $this->session->userdata('username')
                );
                $this->db->insert('internal_sms.data_request_approval', $data_request_approval);
            } else {
                $request_user = array(
                    //'Request_User_ID'              => ini auto increment
                    'Regno_ID'                  => $regnoID,
                    'Sales_Code'                => $sales_code,
                    'Sales_Name'                => $sales_name,
                    'Position'                  => $position,
                    'Level'                     => $level,
                    'Product'                   => $product,
                    'Approval_Status'           => '0',
                    'Status_Date'               => date('Y-m-d'),
                    'Reason'                    => $reason,
                    'Note'                      => $reason.' - '.$efective_date,
                    'Hit_Code'                  => '1000',
                    'Request_ID'                => $request_id
                );
                $this->db->insert('internal_sms.data_request_user', $request_user);
            }

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/level_form/detail/' . $request_id . '/' . $category_id);
        }
    }

    function detail($request_id, $category_id)
    {
        $sales_code = $this->session->userdata('sl_code');

        if ($this->idor->detail_request($request_id) == 0) return redirect('update_error');
        
        $data['category_id'] = $category_id;
        $getCategory = $this->db->get_where('ref_category_structure', array('Category_ID' => $category_id))->row();
        $data['category'] = $getCategory->Category;

        $slug_form = $this->uri->segment(2);
        $row = $this->db->get_where("ref_form", array("Slug" => "$slug_form"))->row();
        $form_id = $row->Form_ID;

        $position = $this->session->userdata('position');

        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position, $list) and $category_id == '5') {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'spv_where', 'add');
        } elseif (in_array($position, $list) and $category_id == '9') {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'cc_where', 'add');
        } else {
            $data['sales'] = $this->level_form_model->getData_api($sales_code, 'sales_where', 'add');
        }

        $data['request_user'] = $this->level_form_model->get_level_detail($request_id, $form_id);
        $data['db'] = $this->level_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/level_form/detail', $data);
    }

    function delete_detail($request_id, $request_user_id, $category_id)
    {

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/level_form/detail/' . $request_id . '/' . $category_id);
    }

    function send($request_id)
    {
        $data_request = array(
            'Is_Send' => '1',
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('data_request', $data_request);

        $querykategori = $this->db->get_where('internal_sms.data_request', array('Request_ID' => $request_id))->row();
        
        //tidak ada API, langsung ke menu approval
        if ($querykategori->Category == 'PROMOSI' || $querykategori->Category == 'LEVEL CC DIAMOND / BRANCH') {
            $data_update = array(
                'Hit_Code'  => '1008'
            );
            $this->db->where('Request_ID', $request_id);
            $this->db->where('Hit_Code', '1000');
            $this->db->update('internal_sms.data_request_user', $data_update);

            $request_user_id = $this->input->post('request_user_id');
            $data_log = array();
            foreach ($request_user_id as $form => $val) {
                $data_log[] = array(
                    'Request_ID'            => $request_id,
                    'Request_User_ID'       => $_POST['request_user_id'][$form],
                    'Hit_Code'              => '1008',
                    'Description'           => 'New Request From ASM',
                    'Updated_By'            => $this->session->userdata('realname'),
                    'Updated_Date'          => date('Y-m-d h:i:s')
                );
            }
            $this->db->insert_batch('internal_sms.data_process_log', $data_log);

            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

            redirect('request/level_form');
        }
        // langsung ke hrd, terdapat API
        else {
            $data_update = array(
                'Hit_Code'  => '1009',
                'Approval_Status' => '1'
            );

            $this->db->where('Request_ID', $request_id);
            $this->db->update('internal_sms.data_request_user', $data_update);

            $request_user_id = $this->input->post('request_user_id');
            $data = array();
            foreach ($request_user_id as $item => $val) {
                $dsr_code = $this->input->post('sales_code')[$item];

                $getEmployeeID = $this->level_form_model->getData_api($dsr_code, '', 'getDetailSales');
                $employee_id = $getEmployeeID->Employee_ID;

                $note = $this->input->post('reason')[$item].' - '.$this->input->post('effective_date')[$item];
                
                $data[] = array(
                    'regnoID'           => $this->input->post('regnoID')[$item],
                    'request_user_id'   => $this->input->post('request_user_id')[$item],
                    'employee_id'       => $employee_id,
                    'dsr_code'          => $this->input->post('sales_code')[$item],
                    'name'              => $this->input->post('name')[$item],
                    'reason'            => $this->input->post('reason')[$item],
                    'note'              => $this->input->post('note')[$item],
                    'category'          => strtoupper(($querykategori->Category)),
                    'created_by'        => $this->session->userdata('realname')
                );
            }
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';die();

            $response = $this->level_form_model->insert_data_api($data);

            $data_log = array();
            foreach ($request_user_id as $form => $val) {
                $data_log[] = array(
                    'Request_ID'            => $request_id,
                    'Request_User_ID'       => $_POST['request_user_id'][$form],
                    'Hit_Code'              => '1009',
                    'Description'           => 'New Request From ASM',
                    'Updated_By'            => $this->session->userdata('realname'),
                    'Updated_Date'          => date('Y-m-d h:i:s')
                );
            }
            $this->db->insert_batch('internal_sms.data_process_log', $data_log);

            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

            redirect('request/level_form');
        }
    }




    function getDate($val, $form_id) {
        $sales_code = $this->session->userdata('sl_code');
        $this->db->select('a.*, b.Category_ID');
        $this->db->from('data_request a');
        $this->db->join('ref_category_structure b', 'b.Category = a.Category', 'left');
        $this->db->where('Created_By', $sales_code)->where('a.Efective_Date', $val)->where('a.Category', 'LEVEL')->where('a.Form_ID', $form_id)->where('Is_Send', '0');;
        $query = $this->db->get();
        $row = $query->row();

        if ($query->num_rows() > 0) {
            echo json_encode(array('status' => true, 'request_id'=> $row->Request_ID, 'category_id' => $row->Category_ID));
        } else {
            echo json_encode(array('status' => false));
        }

    }


}
