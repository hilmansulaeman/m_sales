<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('auth', 'template'));
        $this->load->model('announcement/announcement_model');
    }

    function index()
    {

        $data['init_page'] = 'TableManageResponsive';
        $start_date = date('Y-m-01 00:00:01');
        $end_date = date('Y-m-d') . ' 23:59:59';
        $this->session->set_userdata('start_date', $start_date);
        $this->session->set_userdata('end_date', $end_date);
        //load view
        $this->template->set('title', $this->config->item('site_name') . ' | Announcement');
        $this->template->load('template', 'announcement/sales/index', $data);
    }

    function get_data_announcement()
    {
        $date1 = $this->session->userdata('start_date');
        $date2 = $this->session->userdata('end_date');
        $by    = $this->session->userdata('realname');
        $query = $this->announcement_model->get_datatables($date1, $date2, $by);
        $count_data = $this->announcement_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $onclick = "return confirm('Anda yakin akan menghapus pengumuman ini??')";
            $is_publish = $row->Is_Publish;
            if ($is_publish == '1') {
                $publish = 'YES';

                $action = '<a href="' . site_url() . "announcement/sales/delete/" . $row->Announcement_ID . '" onclick="' . $onclick . '"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Delete Data"></i></span></a>
				';
            } else {
                $publish = 'NO';

                $action = '<a href="' . site_url() . "announcement/sales/edit/" . $row->Announcement_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i></span></a>
				<a href="' . site_url() . "announcement/sales/delete/" . $row->Announcement_ID . '" onclick="' . $onclick . '"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Delete Data"></i></span></a>
				';
            }

            $data[] = array(
                ++$no,
                $row->Category,
                $row->Announcement_Description,
                $publish,
                $row->Created_Date,
                $row->Created_By,
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->announcement_model->count_filtered($date1, $date2,  $by),
            "recordsFiltered" => $this->announcement_model->count_filtered($date1, $date2,  $by),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function filter_data()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $range = $this->datediff($start_date, $end_date);
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($range > 31) {
            $data['inputerror'][] = 'end_date';
            $data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
        $session_data = array(
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date') . ' 23:59:59'
        );
        $this->session->set_userdata($session_data);
        echo json_encode(array("status" => TRUE));
    }


    function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('idEmp[]', 'Daftar Karyawan', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);

        // $this->form_validation->set_rules('email_subject', 'Subject Email', 'trim|required', [
        //     'required' => '{field} harus di isi!'
        // ]);
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('announcement_description', 'Deskripsi', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            // cekvar($this->session->userdata('sl_code'));
            $data['list_employee'] = $this->announcement_model->get_employee($this->session->userdata('sl_code'));
            // cekdb($data['list_employee']);
            $data['category'] = $this->db->query('SELECT * FROM `ref_announcement_category`');

            $this->template->set('title', 'Add Announcement');
            $this->template->load('template', 'announcement/sales/add', $data);
        } else {

            $id_employee = $this->input->post('idEmp');
            // $subject_email = $this->input->post('email_subject');
            $category     = $this->input->post('category');
            $path = 'upload/announcement';


            $announcement_description     = $this->input->post('announcement_description');
            // $is_publish_     = $this->input->post('is_publish');
            $simpan     = $this->input->post('simpan');
            $is_publish = '1';
            // if ($is_publish_ == '1') {
            //     $is_publish = '1';
            // } else {
            //     $is_publish = '0';
            // }

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $config['upload_path'] = './' . $path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|PNG';
            $config['max_size'] = '10000';
            $this->load->library('upload', $config);

            $this->upload->initialize($config);
            $announce_id = $this->db->insert_id();
            if (!$this->upload->do_upload('file')) {

                foreach ($id_employee as $id => $i) {


                    $data_insert = array(
                        // 'Announcement_ID'           => $announce_id,
                        'Employee_ID'               => $i,
                        // 'Subject_Email'             => $subject_email,
                        'Category'                  => $category,
                        'Announcement_Description'  => $announcement_description,
                        'Card'                      => '',
                        'Is_Publish'                => $is_publish,
                        'Created_Date'              => date('Y-m-d H:i:s'),
                        'Created_By'                => $this->session->userdata('realname')
                    );
                    $this->announcement_model->insert($data_insert);

                    if ($simpan == 'kirimdansave' && $is_publish == "1") {

                        $this->kirimemaibyid($i, $category, '', $subject_email, $announcement_description);
                    }
                }

                $this->session->set_flashdata('message', "<b>Pengumuman berhasil disubmit!</b>");


                redirect('announcement/sales');
            } else {


                $upload_data = $this->upload->data();
                $foto = $upload_data['file_name'];

                foreach ($id_employee as $id => $i) {

                    $data_insert = array(
                        'Employee_ID'               => $i,
                        'Subject_Email'             => $subject_email,
                        'Category'                  => $category,
                        'Announcement_Description'  => $announcement_description,
                        'Card'                      => $foto,
                        'Is_Publish'                => $is_publish,
                        'Created_Date'              => date('Y-m-d H:i:s'),
                        'Created_By'                => $this->session->userdata('realname')
                    );

                    $this->announcement_model->insert($data_insert);

                    if ($simpan == 'kirimdansave' && $is_publish == "1") {

                        $this->kirimemaibyid($i, $category, $foto, $subject_email, $announcement_description);
                    }
                }





                $this->session->set_flashdata('message', "<b>Pengumuman berhasil disubmit!</b>");

                //Direct ke view
                redirect('announcement/sales');
            }
        }
    }


    function kirimemaibyid($list_employee, $cat, $Card, $subjek, $kalimat)
    {

        $dataemp = $this->announcement_model->getdataemployeeid($list_employee)->row();

        $temukan = array('$Name', '$NIK');
        $ganti_dengan = array($dataemp->Name, $dataemp->NIK);
        $kalimat_baru = str_replace($temukan, $ganti_dengan, $kalimat);
        $data['category'] = $cat;
        $data['card'] = $Card;
        $data['deskripsi'] = $kalimat_baru;
        $message = $this->load->view('announcement/sales/send_email_view', $data, true);
        $this->send_email($dataemp->Email, $subjek, $message);
    }

    function edit($id)
    {


        $this->load->library('form_validation');
        $this->form_validation->set_rules('list_employee', 'Daftar Karyawan', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('email_subject', 'Subject Email', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('announcement_description', 'Deskripsi', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);

        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $data['category'] = $this->db->query('SELECT * FROM `db_hrd`.`ref_announcement_category`');
            $data['db'] = $this->announcement_model->edit($id);

            $data['list_employee'] = $this->announcement_model->get_employee();

            $this->template->set('title', 'Edit Announcement');
            $this->template->load('template', 'announcement/edit', $data);
        } else {
            $list_employee = $this->input->post('list_employee');
            $subject_email = $this->input->post('email_subject');
            $category     = $this->input->post('category');
            $is_publish_     = $this->input->post('is_publish');
            if ($is_publish_ == '1') {
                $is_publish = '1';
            } else {
                $is_publish = '0';
            }
            $announcement_description     = $this->input->post('announcement_description');
            $simpan     = $this->input->post('simpan');

            $config['upload_path'] = './upload/announcement';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|PNG';
            $config['max_size'] = '10000';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);


            if (!$this->upload->do_upload('file')) {

                $data_edit = array(
                    'Employee_ID'               => $list_employee,
                    'Subject_Email'             => $subject_email,
                    'Category'                    => $category,
                    'Announcement_Description'    => $announcement_description,
                    'Card'                      => '',
                    'Is_Publish'                => $is_publish,
                    'Created_By'                => $this->session->userdata('name')
                );
                $this->db->where('Announcement_ID', $id);
                $this->db->update('`db_hrd`.`data_announcement`', $data_edit);

                if ($simpan == 'kirimdansave' && $is_publish == "1") {
                    if ($list_employee == "0") {
                        $this->kirimemailkesmua($category, '', $subject_email, $announcement_description);
                    } else {
                        $this->kirimemaibyid($list_employee, $category, '', $subject_email, $announcement_description);
                    }
                }

                $this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-4 alert alert-info alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Pengumuman berhasil diupdated!</b>
				</button>
				</span>");


                redirect('announcement');
            } else {
                $upload_data = $this->upload->data();
                $foto = $upload_data['file_name'];

                $data_edit = array(
                    'Employee_ID'               => $list_employee,
                    'Subject_Email'             => $subject_email,
                    'Category'                    => $category,
                    'Announcement_Description'    => $announcement_description,
                    'Card'                      => $foto,
                    'Is_Publish'                => $is_publish,
                    'Created_By'                => $this->session->userdata('name')
                );
                $this->db->where('Announcement_ID', $id);
                $this->db->update('`db_hrd`.`data_announcement`', $data_edit);



                if ($simpan == 'kirimdansave' && $is_publish == "1") {
                    if ($list_employee == "0") {
                        $this->kirimemailkesmua($category, $foto, $subject_email, $announcement_description);
                    } else {
                        $this->kirimemaibyid($list_employee, $category, $foto, $subject_email, $announcement_description);
                    }
                }



                $this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-4 alert alert-info alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Pengumuman berhasil diupdated!</b>
				</button>
				</span>");

                //Direct ke view
                redirect('announcement');
            }
        }
    }



    function delete($announcement_id)
    {
        $this->db->where('Announcement_ID', $announcement_id);
        $this->db->delete('`data_announcement`');

        // redirect to index
        $this->session->set_flashdata('message', "<b>Data berhasil dihapus!</b>");
        redirect('announcement/sales');
    }



    //datedif
    private function datediff($start, $end)
    {
        $date1 = date_create($start);
        $date2 = date_create($end);

        $days = date_diff($date1, $date2);

        return $days->format('%R%a');
    }

    //for send email notif 
    private function send_email($to, $subj, $message)
    {
        $this->load->library('email');
        //$this->config->load('setting');
        // $config['mailtype'] = "html";
        // $this->email->initialize($config);
        $this->email->set_mailtype("html");

        $this->email->from('support@ptdika.com', 'PT Danamas Insan Kreasi Andalan');
        $this->email->to($to);
        // $this->email->bcc('mukhamad.winanto@ptdika.com');
        $this->email->subject($subj);
        // $this->email->attach($attachment);
        $this->email->message($message);
        $this->email->send();
        $this->email->clear(true);
        /*if($this->email->send()){
			echo "Email berhasil dikirim";
		}
		else{
			$this->email->print_debugger();
		}*/
    }
}
