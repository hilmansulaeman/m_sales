<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reaktif_form extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template', 'image_lib'));
        $this->load->model('request/reaktif_form_model');
    }

    public function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Form Reaktif');
        $this->template->load('template', 'request/reaktif_form/index');
    }

    public function add()
    {
        $sales_code = $this->session->userdata('sl_code');
        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $queryreaktif = $this->db->get_where('ref_category_structure', array('Category' => 'REAKTIF'))->row();

        $data['categoryid'] = $queryreaktif->Category_ID;
        $data['category'] = $queryreaktif->Category;

        $list = array('ASM', 'RSM', 'BSH');

        $today = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('-1 month', strtotime($today))); //OKTOBER
        $startDate = date('Y-m-d', strtotime('-2 month', strtotime($today))); //SEPTEMBER
        // var_dump($startDate, $endDate);
        // die;

        if (in_array($position_, $list)) {
            $data['sales'] = $this->reaktif_form_model->getData_api($sales_code, $endDate, $startDate, 'bySalesCode');
        } else {
            $data['sales'] = $this->reaktif_form_model->getData_api('', $endDate, $startDate, 'bySalesCode');
        }

        $this->template->set('title', 'Add Form Reaktif');
        $this->template->load('template', 'request/reaktif_form/add', $data);
    }

    public function get_data_reaktif()
    {
        $query = $this->reaktif_form_model->get_datatables();
        $count_data = $this->reaktif_form_model->count_all()->row();

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;

            $action = '<a href="' . site_url() . "request/reaktif_form/detail/" . $request_id . "/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            // $cek1 = $this->db->query("SELECT
            //                         COUNT(Request_ID) AS total,
            //                         SUM(IF(Hit_Code IN('1005','1006','1007'),1,0)) AS approved,
            //                         SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
            //                         SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            //                         FROM internal_sms.data_request_user
            //                         WHERE Request_ID = '$request_id'
            //                         ");
            $this->db->select("
                COUNT(Request_ID) AS total,
                SUM(IF(Hit_Code = '1006',1,0)) AS completed,
                SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                SUM(IF(Hit_Code = '1010',1,0)) AS cancel,
                SUM(IF(Hit_Code = '1011',1,0)) AS returned
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
                '<a href="' . site_url() . "request/reaktif_form/detail_view/completed/" . $request_id . '" ><span class="btn btn-xs btn-success">' . $cek->completed . '</span></a>',
                '<a href="' . site_url() . "request/reaktif_form/detail_view/returned/" . $request_id . '" ><span class="btn btn-xs btn-warning">' . $cek->returned . '</span></a>',
                '<a href="' . site_url() . "request/reaktif_form/detail_view/rejected/" . $request_id . '" ><span class="btn btn-xs btn-danger">' . $cek->rejected . '</span></a>',
                '<a href="' . site_url() . "request/reaktif_form/detail_view/cancel/" . $request_id . '" ><span class="btn btn-xs btn-danger">' . $cek->cancel . '</span></a>',
                $action,
            );

            // var_dump($data); die;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->reaktif_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function detail($request_id, $category_id)
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

        $today = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('-1 month', strtotime($today))); //OKTOBER
        $startDate = date('Y-m-d', strtotime('-2 month', strtotime($today))); //SEPTEMBER
        // var_dump($startDate, $endDate);
        // die;

        if (in_array($position_, $list)) {
            $data['sales'] = $this->reaktif_form_model->getData_api($sales_code, $endDate, $startDate, 'bySalesCode');
        } else {
            $data['sales'] = $this->reaktif_form_model->getData_api('', $endDate, $startDate, 'bySalesCode');
        }

        $data['request_user'] = $this->reaktif_form_model->get_reaktif_detail($request_id, $form_id);
        $data['db'] = $this->reaktif_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/reaktif_form/detail', $data);
    }

    public function detail_view($category, $request_id)
    {
        $data['category'] = $category;
        $data['request_user'] = $this->reaktif_form_model->getBy_hitCode($category, $request_id);

        //load view
        $this->template->set('title', 'Detail');
        $this->template->load('template', 'request/reaktif_form/detail_view', $data);
    }

    public function upload_doc()
    {
        $response['status'] = false;
        $response['info_msg'] = "Mohon pastikan ada file yang diupload";
        // echo 'Request_ID : '.$this->input->post('request_id');
        // echo '<br>';
        // echo 'Request_User_ID : '.$this->input->post('request_user_id');
        // die();

        // echo ($post);
        if ($this->input->is_ajax_request()) {
            $post = $_POST;
            // $files = $this->upload->data();
            $files = $_FILES;

            $request_id = $post['request_id'];
            $request_user_id = $post['request_user_id'];
            $kategori = "returned";
            $upl_file = $files['upl_file'];

            // $response['data'] = $post;
            // $response['files'] = $files;
            $config['upload_path'] = './upload/trx_doc/';
            $config['max_size'] = 5 * 1024;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = $this->set_file_name_($request_user_id, $kategori, $upl_file);

            $this->load->library('upload', $config, 'pro_upl');
            $this->pro_upl->initialize($config);

            if ($this->pro_upl->do_upload('upl_file')) {
                $file = $this->pro_upl->data();
                $response['upl_file'] = $file;

                // $file['file_name'] = str_replace(" ", "_", $file['file_name']);
                // $post['file'] = $file['file_name'];
                // unset($post['file_buku']);

                $image_info = getimagesize($file['full_path']);
                $image_width = $image_info[0];
                $image_height = $image_info[1];
                $new_width = $image_width * (60 / 100);
                $new_height = $image_height * (60 / 100);

                $resize_conf = array(
                    // it's something like "/full/path/to/the/image.jpg" maybe
                    'source_image' => $file['full_path'],
                    // and it's "/full/path/to/the/" + "thumb_" + "image.jpg
                    // or you can use 'create_thumbs' => true option instead
                    'new_image' => './upload/trx_doc/' . $file['file_name'],
                    'width' => $new_width,
                    'height' => $new_height,
                );

                $this->image_lib->initialize($resize_conf);

                if ($this->image_lib->resize()) {

                    $data_update = array(
                        "File" => $config['file_name'],
                        "Status_Date" => date('Y-m-d'),
                        "Hit_Code" => 1008,
                    );
                    $this->db->where('Request_User_ID', $request_user_id);
                    $this->db->update('internal_sms.data_request_user', $data_update);

                    $data_insert = array(
                        // 'Request_ID' => $request_id,
                        // 'Request_User_ID' => $Request_User_ID,
                        "Hit_Code" => 1008,
                        "Description" => "Send To GM",
                        // 'Updated_By' => $this->session->userdata('realname'),
                        // 'Updated_Date' => date('Y-m-d h:i:s'),
                    );
                    $this->db->insert('internal_sms.data_process_log', $data_insert);

                                $this->db->order_by("Request_Approval_ID","Desc");
                    $row = $this->db->get_where('data_request_approval', array('Request_User_ID' => $request_user_id));

                    $exec =$row->row();
                    // $response["sales_code"]=$exec
                    $dsr_code = $exec->Sales_Code;
                    $getGM = $this->reaktif_form_model->getData_api($dsr_code, '', '', 'getUpliner');

                    $data_request_approval = array(
                        'Request_ID' => $request_id,
                        'Request_User_ID' => $request_user_id,
                        'Sales_Code' => $getGM->DSR_Code,
                        'Sales_Name' => $getGM->Name,
                        'Position' => $getGM->Position,
                        'Status' => '0',
                        'Created_By' => $this->session->userdata('username'),
                    );
                    $this->db->insert('internal_sms.data_request_approval', $data_request_approval);

                    $response['status'] = true;
                } else {

                    $response['info_msg'] = $this->image_lib->display_errors();
                }
            } else {
                $response['info_msg'] = $this->pro_upl->display_errors();
            }

            echo json_encode($response);
        } else {
            exit("Access Denied");
        }

    }

    private function set_file_name_($id1, $foto, $upl_file)
    {

        if (!empty($upl_file['name'])) {
            $file = $upl_file['name'];
            $gabung = str_replace(" ", "_", "$file");
            $pisah = explode(".", $gabung);
            $nama = $pisah[0];
            $temp = md5(time() . $nama);
            $ext = end($pisah);
        } else {
            $temp = '';
            $ext = '';
        }
        return $id1 . '_' . $foto . '_' . $temp . '.' . $ext;
    }

    public function delete_detail($request_id, $request_user_id, $category_id)
    {
        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/reaktif_form/detail/' . $request_id . '/' . $category_id);
    }

    public function save_request_reaktif($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request

        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form
        // echo $slug_form;die();

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

        // $query_cek = $this->db->query("SELECT a.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Category = '$category' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1002','1004','1007','1010') ");
        // $cek = $query_cek->num_rows();

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

            redirect('request/reaktif_form/detail/' . $request_id . '/' . $category_id);
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

            redirect('request/reaktif_form/detail/' . $request_id . '/' . $category_id);
        }
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

        redirect('request/reaktif_form');
    }

    public function send_old($request_id)
    {
        $querykategori = $this->db->get_where('internal_sms.data_request', array('Request_ID' => $request_id))->row();

        $data_update = array(
            'Hit_Code' => '1009',
        );
        // $this->db->where('Request_ID', $request_id);
        // $this->db->update('internal_sms.data_request_user', $data_update);

        $request_user_id = $this->input->post('request_user_id');

        $data = array();
        foreach ($request_user_id as $item => $val) {
            $dsr_code = $this->input->post('sales_code')[$item];

            $getEmployeeID = $this->reaktif_form_model->getData_api($dsr_code, '', '', 'getDetailSales');
            $employee_id = $getEmployeeID->Employee_ID;

            echo '<pre>';
            print_r($getEmployeeID);
            echo '</pre>';die();

            $data[] = array(
                'request_user_id' => $this->input->post('request_user_id')[$item],
                'employee_id' => $employee_id,
                'dsr_code' => $this->input->post('sales_code')[$item],
                'name' => $this->input->post('name')[$item],
                'reason' => $this->input->post('reason')[$item],
                'category' => strtoupper(($querykategori->Category)),
                'created_by' => $this->session->userdata('realname'),
            );
        }
        $response = $this->reaktif_form_model->insert_data_api($data);

        $data_log = array();
        foreach ($request_user_id as $form => $val) {
            $data_log[] = array(
                'Request_ID' => $request_id,
                'Request_User_ID' => $_POST['request_user_id'][$form],
                'Hit_Code' => '1009',
                'Description' => 'New Request From ASM',
                'Updated_By' => $this->session->userdata('realname'),
                'Updated_Date' => date('Y-m-d h:i:s'),
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

        redirect('request/reaktif_form');
    }

    public function getDate($val)
    {
        $sales_code = $this->session->userdata('sl_code');
        $this->db->select('a.*, b.Category_ID');
        $this->db->from('data_request a');
        $this->db->join('ref_category_structure b', 'b.Category = a.Category', 'left');
        $this->db->where('Created_By', $sales_code)->where('a.Efective_Date', $val)->where('a.Category', 'REAKTIF')->where('Is_Send', '0');
        $query = $this->db->get();
        $row = $query->row();

        if ($query->num_rows() > 0) {
            echo json_encode(array('status' => true, 'request_id' => $row->Request_ID, 'category_id' => $row->Category_ID));
        } else {
            echo json_encode(array('status' => false));
        }

    }

}