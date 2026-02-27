<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Id_card extends MY_Controller
{
    private $model = 'id_card_model';
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('url','file','form'));
		$this->load->library(array('template'));
		$this->load->model($this->model);
	}

    public function request($flag){

        if($flag == 'index'){
            $tl = 'Data Request ID Card';
        } else {
            $tl = 'Data Return ID Card';
        }

        $data = [
			'flag' => $flag,
            'title' => $tl,
			'tab' => $this->load->view('id_card/tab', array('flag' => $flag), TRUE)
		];

        $this->template->set('title','ID Card | Dika');
        $this->template->load('template','id_card/request',$data);
    }

    public function approval(){
        $this->template->set('title','ID Card | Dika');
        $this->template->load('template','id_card/approve');
    }

    function history($detail = '', $id = ''){
        if(empty($detail)){
            $this->template->set('title','ID Card | Dika');
            $this->template->load('template','id_card/history');
        } else {
            $data = array(
                'id' => $id,
                'nik' => $this->session->userdata("username"),
                'nama' => $this->session->userdata("realname"),
                'unit' => $this->session->userdata("unit"),
                'divisi' => $this->session->userdata("division"),
                'departement' => $this->session->userdata("departement"),
            );
            $this->template->set('title','ID Card | Dika');
            $this->template->load('template','id_card/detail',$data);
        }
    }

    function add(){ 
        
        $nik = $this->session->userdata("username");
        $nama = $this->session->userdata("realname");
        $unit = $this->session->userdata("unit");
        $divisi = $this->session->userdata("division");
        $departement = $this->session->userdata("departement");

        $get_sales = $this->{$this->model}->get_sales_api($nik);

        $data = array(
            'nik' => $nik,
            'nama' => $nama,
            'unit' => $unit,
            'divisi' => $divisi,
            'departement' => $departement,
            'get_sales' => $get_sales,
        );

        $this->template->set('title','Input ID Card | Dika');
        $this->template->load('template','id_card/add',$data);
    }

    function add_request(){
        $this->load->library('form_validation');
        $this->validate();
        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'sales_data' => form_error('sales_data'),
                'nama'    => form_error('nama'),
                'nik'    => form_error('nik'),
                'unit'   => form_error('unit'),
                'divisi'        => form_error('divisi'),
                'departement'          => form_error('departement'),
                'request_reason'          => form_error('request_reason'),
                'note_idcard'          => form_error('note_idcard'),
                'category_idcard[]'      => form_error('category_idcard[]')
            ];

            if (empty($_FILES['upload_foto']['name'])) {
                $errors['upload_foto'] = '<span style="color:#FF0000">Foto wajib diupload</span>';
            }

            echo json_encode([
                'status' => false,
                'error' => $errors
            ]);
            return;
        } else {
            $filename = $_FILES['upload_foto']['name'];
            if(isset($filename) && !empty($filename)){
                
                $path    = $_FILES['upload_foto']['tmp_name'];
                $type    = $_FILES['upload_foto']['type'];
                $baseimg = file_get_contents($path);
                $base64  = 'data:' . $type . ';base64,' . base64_encode($baseimg);

                $k_type = $this->input->post('category_idcard');
                if (!empty($k_type)) {
                    $category_idcard = implode(',', $k_type);
                } else {
                    $category_idcard = null;
                }

                $nik_req = $this->session->userdata("username");
                $nama_req = $this->session->userdata("realname");
                $position_req = $this->session->userdata("position");

                $data = array(
                    'nik' => $this->input->post('nik1', TRUE),
                    'nama' => $this->input->post('nama1', TRUE),
                    'unit' => $this->input->post('unit1', TRUE),
                    'divisi' => $this->input->post('divisi1', TRUE),
                    'departement' => $this->input->post('departement',TRUE),
                    'request_reason' => $this->input->post('request_reason', TRUE),
                    'note_idcard' => $this->input->post('note_idcard', TRUE),
                    'created_by' => $nik_req,
                    'created_name' => $nama_req,
                    'created_position' => $position_req,
                    'category_idcard' => $category_idcard,
                    'upload_foto' => $base64,
                    'filename' => $filename,
                    'sm_code' => $this->session->userdata("sm_code"),
                    'sm_name' => $this->session->userdata("sm_name"),  
                );

                // print_r($data);
                // die();

                $save = $this->{$this->model}->insert_api($data);
                if($save->status == true){
                    echo json_encode([
                        'status' => true,
                        'error' => null,
                        'message' => 'Data berhasil disimpan'
                    ]);
                } else {
                    if (isset($response['message'])) {
                        // echo $response['message'];
                        echo json_encode([
                            'error' => $save->error,
                            'status' => false,
                            'message' => $save->message
                        ]);
                    } else {
                        echo json_encode([
                            'error' => $save->error,
                            'status' => false,
                            'message' => 'Data gagal disimpan!'
                        ]);
                    }
                }
            } else {
                $errors = [
                    'upload_foto'   => '<span style="color:#FF0000">Foto wajib diupload</span>'
                ];
                echo json_encode([
                    'status' => false,
                    'errors' => $errors
                ]);
            }
        }
    }

    function get_employee($code){
        $data_sales = $this->{$this->model}->get_sales_api($code);
        echo json_encode($data_sales);
    }

    function validate(){
        $this->form_validation->set_rules('sales_data', 'Data Sales', 'trim|required|callback_check_string',array('required' => '%s wajib diisi'));
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('nik', 'NIK', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('unit', 'Unit', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('divisi', 'Divisi', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('departement', 'Departement', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('request_reason', 'Alasan Permintaan ID Card', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('note_idcard', 'Kebutusan ID Card', 'trim|required|callback_check_string');
		$this->form_validation->set_rules('category_idcard[]', 'Kebutuhan ID Card', 'trim|callback_validate_category_idcard');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
        $this->form_validation->set_message(
        'required',
        '%s wajib diisi.'
        );
    }

    function validate_category_idcard(){

        $category_idcard = $this->input->post('category_idcard');

        if (!is_array($category_idcard)) {
            $this->form_validation->set_message(
                'validate_category_idcard',
                'Minimal pilih 1'
            );
            return false;
        }
    }

    function get_data_request(){
        $hit_code = '1';
        $sales_code = $this->session->userdata('username');
        $name = $this->session->userdata("realname");
        $data = [];
        $no = $_POST['start'];
        $api = $this->{$this->model}->get_datatables_api($hit_code, $name);

        foreach ($api as $row) {

            $btn_edit = '<a href="'.site_url('admin/data_supplement/edit/'.$row->id).'" class="btn btn-primary btn-xs" title="Edit Data"><i class="fa fa-edit"></i></a>';
            $btn_detail = '<a href="'.site_url('admin/data_supplement/view/'.$row->id).'" class="btn btn-success btn-xs" title="Lihat Detail"><i class="fa fa-eye"></i></a>';

            $btn = $btn_edit.'|'.$btn_detail;

            $data[] = array(
				++$no,
				$row->Sales_Name,
				$row->Status,
                $btn,
			);
        }

        $output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => count($api),
			"recordsFiltered" => count($api),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);

    }
}
        // $departement = $this->post('departement',TRUE);
        // $divisi = $this->post('divisi', TRUE);
        // $unit = $this->post('unit', TRUE);
        // $nik = $this->post('nik', TRUE);
        // $nama = $this->post('nama', TRUE);
        // $request_reason = $this->post('request_reason', TRUE);
        // $category_idcard = $this->post('category_idcard', TRUE);
        // $created_by = $this->post('created_by', TRUE);
        // $created_name = $this->post('created_name', TRUE);
        // $created_position = $this->post('created_position', TRUE);
        // $images = $this->post('file');
        // $nmfile1 = $this->post('filename'); //with extention
        // $sm_code = $this->post('sm_code', TRUE);
        // $sm_name = $this->post('sm_name', TRUE);