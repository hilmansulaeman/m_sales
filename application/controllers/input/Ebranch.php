<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ebranch extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('input/ebranch_model');
    }

    public function index()
    {
        $uri4 = $this->uri->segment(4);
        $this->session->set_userdata('uri4', $uri4);

        //load view
        $this->template->set('title', 'Data Ebranch');
        $this->template->load('template', 'input/ebranch/index');
    }

    public function get_data_merchant()
    {
        $query = $this->ebranch_model->_get_data_query();

        $data = array();
        $no = $this->input->post('start');
        foreach ($query as $row) {
            $hit_code = $row->Hit_Code;
            $edit = array('102', '107');
            if (in_array($hit_code, $edit)) {
                $action = '<a href="' . site_url() . "input/edc/edit/" . $row->RegnoId . '" ><span class="btn btn-xs btn-warning"><i class="fa fa-md fa-edit" title="Edit Data"></i></span></a>';
            } else {
                $action = '&nbsp;';
            }

            $data[] = array(
                ++$no,
                $row->Product_Type,
                $row->MID_Type,
                $row->Owner_Name,
                $row->Merchant_Name,
                $row->Account_Number,
                $row->Mobile_Phone_Number,
                $action,
            );
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->ebranch_model->_count_data(),
            "recordsFiltered" => $this->ebranch_model->_count_data(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $no_referensi = strtoupper(str_replace(' ', '', $this->input->post('no_referensi')));

        $this->form_validation->set_rules('Customer_Name', 'Nama Nasabah', 'trim|required|callback_check_string', [
            'required' => '{field} harus di isi!',
        ]);
        $this->form_validation->set_rules('Source_Code', 'Source Code', 'trim|required', [
            'required' => '{field} harus di isi!',
        ]);
        $this->form_validation->set_rules('Project', 'Event', 'trim|required', [
            'required' => '{field} harus di isi!',
        ]);
        // $this->form_validation->set_rules('Source_Code', 'Source Code', 'trim|required|callback_cek_source_code', [
        //     'required' => '{field} harus di isi!',
        // ]);
        $this->form_validation->set_rules('no_referensi', 'No Referensi', 'trim|required|min_length[10]|max_length[10]|regex_match[/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/]|callback_cek_noref|callback_check_string', [
            'required'    =>   '{field} harus di isi!',
            'min_length'  =>   '{field} harus 10 digit!',
            'max_length'  =>   '{field} harus 10 digit!',
            'regex_match' =>   '{field} harus angka dan huruf!',
        ]);
        $this->form_validation->set_rules('kode_referensi', 'Kode Referensi', 'trim|required', [
            'required' => '{field} harus di isi!',
        ]);

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == false) {
            $data['ref_referensi'] = $this->ebranch_model->get_kode_referensi();
            $data['event']         = $this->ebranch_model->get_event();
            $this->template->set('title', 'Input Data');

            // echo "<pre>";

            // print_r($data);
            // echo "</pre>";die();

            $this->template->load('template', 'input/ebranch/add', $data);
        } else {
            $username = $this->session->userdata('sl_code');
            $product = $this->session->userdata('product');
            $tanggal_hari_ini = date('Y-m-d');
            // $tanggal_hari_ini = "2025-05-29";
            // cek ada tidak data event nya
            $check_data_event = $this->ebranch_model->check_limit_data_event($username, $product, $tanggal_hari_ini);
            // kalo ada 
            if ($check_data_event->num_rows() > 0) {

                $data_event = $check_data_event->row();

                // check apakah data cc hari ini ada
                $check_cc_data = $this->ebranch_model->check_cc_exist($username, $tanggal_hari_ini);

                // kalo ada
                if (count($check_cc_data) > 0) {
                    // kondisi apakah data cc hari ini melebihi limit dari data event
                    if (count($check_cc_data) >= $data_event->limit) {

                        //  Input harian untuk produk Credit Card (CC) adalah 10 aplikasi per sales per hari.
                        // redirect kalo melebihi kuota
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <b>Maksimal input harian untuk produk Credit Card (CC) adalah ' . $data_event->limit . ' aplikasi per sales per hari</b>
                            </div>');

                        //Direct ke view
                        redirect('input/ebranch/add', 'refresh');
                    } else {

                        // kondisi kalo data cc masih bisa di input
                        $filename = $_FILES['file']['name'];
                        if (isset($filename)) {
                            $path    = $_FILES['file']['tmp_name'];
                            $type    = $_FILES['file']['type'];
                            $baseimg = file_get_contents($path);
                            $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);
                            // $base64 = base64_encode(file_get_contents($path));
                            // $base64="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAABHCAIAAAAlcOiqAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFwSURBVGhD7dAxksQwCETRuf+lvQ4+VTArCxormIAX0gJ3+XP9qmmmm2a6aaabZrpppptmummmm2a6ZrPPCtkh2jkqZHj9TvUK31Sw2VXa51M69lvyZT7SxRVdssn5FV4Ypiu8EO3WOPwP8QMeRWSKxx1ORmQZXkdkZUIzghp2HIKy9QLHHAIFmw5BTakZUx37hmnN4jVnHAId+w5BQd6MaRdXDNOC76cccAi6uOIQZJJmTN/hlmGamWYO00x4x6phegIXDdOtaRYx3ZpmEdOtaRYx3ZpmEdOt70esGqbvcMswzUwzh2kmaXYj6OKKQ5BZvOOAYdrFFcO0IG92I9Cx7xAUrJ9yxjDVse8QFJSa3QgUbDoENY+vOeYQ1LDjEJQJzW5kGV5HZGW7BU5GZM94F5Epkh0Or/DCMF3hhaj5D+q4oitt8hEd+y3VZT6lYLNL2+ebGV6/07xChYjskMPnDppmummmm2a6aaabZqrr+gOHLQrw39Tf2gAAAABJRU5ErkJggg==";
                            // cekvar($base64);

                            $kode_referensi = $this->input->post('kode_referensi');
                            $explode        = explode('-', $kode_referensi);

                            $data = array(
                                'Customer_Name' =>   $this->input->post('Customer_Name'),
                                'Source_Code'   =>   $this->input->post('Source_Code'),
                                'no_referensi'  =>   $no_referensi,
                                'is_ebranch'    =>   $explode[0],
                                'Sales_Code'    =>   $this->input->post('Sales_Code'),
                                'Sales_Name'    =>   $this->input->post('Sales_Name'),
                                'Branch'        =>   $this->input->post('Branch'),
                                'Project'       =>   $this->input->post('Project'),
                                'Status'        =>   $explode[1],
                                'file'          =>   $base64,
                                'filename'      =>   $filename,
                                // 'Created_by' => $this->input->post('Created_by'),
                                'Created_by' => '',
                            );

                            $this->ebranch_model->insert($data);
                            $id = $this->session->userdata('RegnoId');
                            // $id = $this->db->insert_id();

                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
                                </div>');

                            //Direct ke view
                            redirect('input/ebranch/add', 'refresh');
                        }
                    }
                } else {
                    // kondisi kalo data cc masih bisa di input
                    $filename = $_FILES['file']['name'];
                    if (isset($filename)) {
                        $path    = $_FILES['file']['tmp_name'];
                        $type    = $_FILES['file']['type'];
                        $baseimg = file_get_contents($path);
                        $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);
                        // $base64 = base64_encode(file_get_contents($path));
                        // $base64="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAABHCAIAAAAlcOiqAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFwSURBVGhD7dAxksQwCETRuf+lvQ4+VTArCxormIAX0gJ3+XP9qmmmm2a6aaabZrpppptmummmm2a6ZrPPCtkh2jkqZHj9TvUK31Sw2VXa51M69lvyZT7SxRVdssn5FV4Ypiu8EO3WOPwP8QMeRWSKxx1ORmQZXkdkZUIzghp2HIKy9QLHHAIFmw5BTakZUx37hmnN4jVnHAId+w5BQd6MaRdXDNOC76cccAi6uOIQZJJmTN/hlmGamWYO00x4x6phegIXDdOtaRYx3ZpmEdOtaRYx3ZpmEdOt70esGqbvcMswzUwzh2kmaXYj6OKKQ5BZvOOAYdrFFcO0IG92I9Cx7xAUrJ9yxjDVse8QFJSa3QgUbDoENY+vOeYQ1LDjEJQJzW5kGV5HZGW7BU5GZM94F5Epkh0Or/DCMF3hhaj5D+q4oitt8hEd+y3VZT6lYLNL2+ebGV6/07xChYjskMPnDppmummmm2a6aaabZqrr+gOHLQrw39Tf2gAAAABJRU5ErkJggg==";
                        // cekvar($base64);

                        $kode_referensi = $this->input->post('kode_referensi');
                        $explode        = explode('-', $kode_referensi);

                        $data = array(
                            'Customer_Name' =>   $this->input->post('Customer_Name'),
                            'Source_Code'   =>   $this->input->post('Source_Code'),
                            'no_referensi'  =>   $no_referensi,
                            'is_ebranch'    =>   $explode[0],
                            'Sales_Code'    =>   $this->input->post('Sales_Code'),
                            'Sales_Name'    =>   $this->input->post('Sales_Name'),
                            'Branch'        =>   $this->input->post('Branch'),
                            'Project'       =>   $this->input->post('Project'),
                            'Status'        =>   $explode[1],
                            'file'          =>   $base64,
                            'filename'      =>   $filename,
                            // 'Created_by' => $this->input->post('Created_by'),
                            'Created_by' => '',
                        );

                        $this->ebranch_model->insert($data);
                        $id = $this->session->userdata('RegnoId');
                        // $id = $this->db->insert_id();

                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
                            </div>');

                        //Direct ke view
                        redirect('input/ebranch/add', 'refresh');
                    }
                }
                // kalo ga ada check ke data limit
            } else {

                // check data limit
                $get_data_limit = $this->ebranch_model->check_limit($product);

                if ($get_data_limit->num_rows() > 0) {
                    $data_limit = $get_data_limit->row();

                    // check data cc nya
                    $check_cc_data = $this->ebranch_model->check_cc_exist($username, $tanggal_hari_ini);

                    if (count($check_cc_data) > 0) {

                        // check apakah data cc hari ini sudah melebihi limit 
                        if (count($check_cc_data) >= $data_limit->limit) {

                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <b>Maksimal input harian untuk produk Credit Card (CC) adalah ' . $data_limit->limit . ' aplikasi per sales per hari</b>
                            </div>');

                            //Direct ke view
                            redirect('input/ebranch/add', 'refresh');
                        } else {
                            $filename = $_FILES['file']['name'];
                            if (isset($filename)) {
                                $path    = $_FILES['file']['tmp_name'];
                                $type    = $_FILES['file']['type'];
                                $baseimg = file_get_contents($path);
                                $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);
                                // $base64 = base64_encode(file_get_contents($path));
                                // $base64="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAABHCAIAAAAlcOiqAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFwSURBVGhD7dAxksQwCETRuf+lvQ4+VTArCxormIAX0gJ3+XP9qmmmm2a6aaabZrpppptmummmm2a6ZrPPCtkh2jkqZHj9TvUK31Sw2VXa51M69lvyZT7SxRVdssn5FV4Ypiu8EO3WOPwP8QMeRWSKxx1ORmQZXkdkZUIzghp2HIKy9QLHHAIFmw5BTakZUx37hmnN4jVnHAId+w5BQd6MaRdXDNOC76cccAi6uOIQZJJmTN/hlmGamWYO00x4x6phegIXDdOtaRYx3ZpmEdOtaRYx3ZpmEdOt70esGqbvcMswzUwzh2kmaXYj6OKKQ5BZvOOAYdrFFcO0IG92I9Cx7xAUrJ9yxjDVse8QFJSa3QgUbDoENY+vOeYQ1LDjEJQJzW5kGV5HZGW7BU5GZM94F5Epkh0Or/DCMF3hhaj5D+q4oitt8hEd+y3VZT6lYLNL2+ebGV6/07xChYjskMPnDppmummmm2a6aaabZqrr+gOHLQrw39Tf2gAAAABJRU5ErkJggg==";
                                // cekvar($base64);

                                $kode_referensi = $this->input->post('kode_referensi');
                                $explode        = explode('-', $kode_referensi);

                                $data = array(
                                    'Customer_Name' =>   $this->input->post('Customer_Name'),
                                    'Source_Code'   =>   $this->input->post('Source_Code'),
                                    'no_referensi'  =>   $no_referensi,
                                    'is_ebranch'    =>   $explode[0],
                                    'Sales_Code'    =>   $this->input->post('Sales_Code'),
                                    'Sales_Name'    =>   $this->input->post('Sales_Name'),
                                    'Branch'        =>   $this->input->post('Branch'),
                                    'Project'       =>   $this->input->post('Project'),
                                    'Status'        =>   $explode[1],
                                    'file'          =>   $base64,
                                    'filename'      =>   $filename,
                                    // 'Created_by' => $this->input->post('Created_by'),
                                    'Created_by' => '',
                                );

                                $this->ebranch_model->insert($data);
                                $id = $this->session->userdata('RegnoId');
                                // $id = $this->db->insert_id();

                                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
                                    </div>');

                                //Direct ke view
                                redirect('input/ebranch/add', 'refresh');
                            }
                        }
                    } else {
                        $filename = $_FILES['file']['name'];
                        if (isset($filename)) {
                            $path    = $_FILES['file']['tmp_name'];
                            $type    = $_FILES['file']['type'];
                            $baseimg = file_get_contents($path);
                            $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);
                            // $base64 = base64_encode(file_get_contents($path));
                            // $base64="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAABHCAIAAAAlcOiqAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFwSURBVGhD7dAxksQwCETRuf+lvQ4+VTArCxormIAX0gJ3+XP9qmmmm2a6aaabZrpppptmummmm2a6ZrPPCtkh2jkqZHj9TvUK31Sw2VXa51M69lvyZT7SxRVdssn5FV4Ypiu8EO3WOPwP8QMeRWSKxx1ORmQZXkdkZUIzghp2HIKy9QLHHAIFmw5BTakZUx37hmnN4jVnHAId+w5BQd6MaRdXDNOC76cccAi6uOIQZJJmTN/hlmGamWYO00x4x6phegIXDdOtaRYx3ZpmEdOtaRYx3ZpmEdOt70esGqbvcMswzUwzh2kmaXYj6OKKQ5BZvOOAYdrFFcO0IG92I9Cx7xAUrJ9yxjDVse8QFJSa3QgUbDoENY+vOeYQ1LDjEJQJzW5kGV5HZGW7BU5GZM94F5Epkh0Or/DCMF3hhaj5D+q4oitt8hEd+y3VZT6lYLNL2+ebGV6/07xChYjskMPnDppmummmm2a6aaabZqrr+gOHLQrw39Tf2gAAAABJRU5ErkJggg==";
                            // cekvar($base64);

                            $kode_referensi = $this->input->post('kode_referensi');
                            $explode        = explode('-', $kode_referensi);

                            $data = array(
                                'Customer_Name' =>   $this->input->post('Customer_Name'),
                                'Source_Code'   =>   $this->input->post('Source_Code'),
                                'no_referensi'  =>   $no_referensi,
                                'is_ebranch'    =>   $explode[0],
                                'Sales_Code'    =>   $this->input->post('Sales_Code'),
                                'Sales_Name'    =>   $this->input->post('Sales_Name'),
                                'Branch'        =>   $this->input->post('Branch'),
                                'Project'       =>   $this->input->post('Project'),
                                'Status'        =>   $explode[1],
                                'file'          =>   $base64,
                                'filename'      =>   $filename,
                                // 'Created_by' => $this->input->post('Created_by'),
                                'Created_by' => '',
                            );

                            $this->ebranch_model->insert($data);
                            $id = $this->session->userdata('RegnoId');
                            // $id = $this->db->insert_id();

                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
                                </div>');

                            //Direct ke view
                            redirect('input/ebranch/add', 'refresh');
                        }
                    }
                } else {
                    $filename = $_FILES['file']['name'];
                    if (isset($filename)) {
                        $path    = $_FILES['file']['tmp_name'];
                        $type    = $_FILES['file']['type'];
                        $baseimg = file_get_contents($path);
                        $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);
                        // $base64 = base64_encode(file_get_contents($path));
                        // $base64="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAABHCAIAAAAlcOiqAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFwSURBVGhD7dAxksQwCETRuf+lvQ4+VTArCxormIAX0gJ3+XP9qmmmm2a6aaabZrpppptmummmm2a6ZrPPCtkh2jkqZHj9TvUK31Sw2VXa51M69lvyZT7SxRVdssn5FV4Ypiu8EO3WOPwP8QMeRWSKxx1ORmQZXkdkZUIzghp2HIKy9QLHHAIFmw5BTakZUx37hmnN4jVnHAId+w5BQd6MaRdXDNOC76cccAi6uOIQZJJmTN/hlmGamWYO00x4x6phegIXDdOtaRYx3ZpmEdOtaRYx3ZpmEdOt70esGqbvcMswzUwzh2kmaXYj6OKKQ5BZvOOAYdrFFcO0IG92I9Cx7xAUrJ9yxjDVse8QFJSa3QgUbDoENY+vOeYQ1LDjEJQJzW5kGV5HZGW7BU5GZM94F5Epkh0Or/DCMF3hhaj5D+q4oitt8hEd+y3VZT6lYLNL2+ebGV6/07xChYjskMPnDppmummmm2a6aaabZqrr+gOHLQrw39Tf2gAAAABJRU5ErkJggg==";
                        // cekvar($base64);

                        $kode_referensi = $this->input->post('kode_referensi');
                        $explode        = explode('-', $kode_referensi);

                        $data = array(
                            'Customer_Name' =>   $this->input->post('Customer_Name'),
                            'Source_Code'   =>   $this->input->post('Source_Code'),
                            'no_referensi'  =>   $no_referensi,
                            'is_ebranch'    =>   $explode[0],
                            'Sales_Code'    =>   $this->input->post('Sales_Code'),
                            'Sales_Name'    =>   $this->input->post('Sales_Name'),
                            'Branch'        =>   $this->input->post('Branch'),
                            'Project'       =>   $this->input->post('Project'),
                            'Status'        =>   $explode[1],
                            'file'          =>   $base64,
                            'filename'      =>   $filename,
                            // 'Created_by' => $this->input->post('Created_by'),
                            'Created_by' => '',
                        );

                        $this->ebranch_model->insert($data);
                        $id = $this->session->userdata('RegnoId');
                        // $id = $this->db->insert_id();

                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
                            </div>');

                        //Direct ke view
                        redirect('input/ebranch/add', 'refresh');
                    }

                    // $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                    // <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    //     <b>Limit belum diinput</b>
                    // </div>');

                    // //Direct ke view
                    // redirect('input/ebranch/add', 'refresh');
                }
            }
        }
    }

    public function add2()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('Customer_Name', 'Nama Nasabah', 'trim|required', [
            'required' => '{field} harus di isi!',
        ]);
        $this->form_validation->set_rules('Source_Code', 'Source Code', 'trim|required|callback_cek_source_code', [
            'required' => '{field} harus di isi!',
        ]);
        $this->form_validation->set_rules('no_referensi', 'No Referensi', 'trim|required|callback_cek_noref_new', [
            'required' => '{field} harus di isi!',
        ]);

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == false) {
            $this->template->set('title', 'Input Data');
            $this->template->load('template', 'input/ebranch/add');
        } else {
            $data = array(
                'Customer_Name' => $this->input->post('Customer_Name'),
                'Source_Code' => $this->input->post('Source_Code'),
                'no_referensi' => $this->input->post('no_referensi'),
                'Sales_Code' => $this->input->post('Sales_Code'),
                'Sales_Name' => $this->input->post('Sales_Name'),
                'Branch' => $this->input->post('Branch'),
                'Created_by' => $this->input->post('Created_by'),
            );
            $insert = $this->ebranch_model->insert_new($data);

            if ($insert->status == true) {
                $id = $insert->RegnoId;
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<b>Data berhasil disimpan, ID Aplikasi : <span style="color:red">' . $id . '</span>, Mohon untuk dicatat di kirbal!</b>
					</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<b>' . $insert->status . '</b>
					</div>');
            }

            //Direct ke view
            redirect('input/ebranch/add_new', 'refresh');
        }
    }

    public function get_source_code($id = null)
    {
        if (!empty($id)) {
            $query = $this->ebranch_model->get_source_code($id);
            // Is there a row with this id?
            if ($query != '') {
                $data[] = $query;
                echo json_encode($data);
            }
        }
    }

    public function get_event()
    {
        $idx = $this->input->post('idx');
        $query = "";

        if (!empty($idx)):
            $query = $this->ebranch_model->get_project($idx);
            // Is there a row with this id?
            // if ($query != '') {
            //     $data[] = $query;
            // }

        endif;

        echo json_encode($query);
    }

    //================================ INTERNAL FUNCTION ===================================//

    public function cek_noref($str)
    {
        $query = $this->ebranch_model->cek_noref($str);

        if ($query > 0) {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('cek_noref', 'No Referensi sudah terdaftar!');
            return false;
        } else {
            // Everything is good, don't return an error.
            return true;
        }
    }

    public function cek_noref_new($str)
    {
        $query = $this->ebranch_model->cek_noref_new($str);

        if ($query > 0) {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('cek_noref_new', 'No Referensi sudah terdaftar!');
            return false;
        } else {
            // Everything is good, don't return an error.
            return true;
        }
    }

    public function cek_source_code($str)
    {
        $query = $this->ebranch_model->get_source_code($str);

        if ($query == '') {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('cek_source_code', 'Source Code tidak ditemukan!');
            return false;
        } else {
            // Everything is good, don't return an error.
            return true;
        }
    }

}

/* End of file Ebranch.php */
/* Location: ./application/controllers/input/Ebranch.php */
