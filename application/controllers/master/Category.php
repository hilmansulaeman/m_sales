<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template', 'form_validation'));
        $this->load->model('master/category_model');
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Kategori');
        $this->template->load('template', 'master/category/index');
    }

    function get_data_category()
    {
        $query = $this->category_model->get_datatables();
        $count_data = $this->category_model->count_all()->row();

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $onclick = "return confirm('Anda yakin akan menghapus data ini??')";
            
            $action = '<a href="' . site_url() . "master/category/hapus/" . $row->Category_ID . '"  onclick="'.$onclick.'"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Hapus Data"></i></span></a>
            <a href="' . site_url() . "master/category/edit/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-pencil" title="Edit Data"></i></span></a>';

            $data[] = array(
                ++$no,
                $row->Category,
                $row->Category_Form,
                $row->Approval_Max,
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->category_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add()
    {
        $this->form_validation->set_rules('category', 'category', 'required');
        $this->form_validation->set_rules('category_form', 'category_form', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori_form'] = $this->category_model->get_data_category_form();

            $this->template->set('title', 'Add Category');
            $this->template->load('template', 'master/category/add', $data);
        } else {
            $category         = $this->input->post('category');
            $category_form    = $this->input->post('category_form');
            $approval_max     = $this->input->post('approvalmax');

            $request = array(
                'Category'          => strtoupper($category),
                'Category_Form'     => $category_form,
                'Approval_Max'      => $approval_max
            );
            $this->db->insert('internal_sms.ref_category_structure', $request);
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambah!</b></span>");
            redirect('master/category/index');
        }
    }

    function edit($category_id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category', 'category', 'required');
        $this->form_validation->set_rules('category_form', 'category_form', 'required');



        if ($this->form_validation->run() == FALSE) {
            //SPV, PRODUK, DLL
            $data['category'] = $this->db->query("SELECT *
                                              FROM ref_category_structure
                                             ")->result();
            //get data by id
            $data['db'] = $this->category_model->edit($category_id)->row();

            //RESTRUCT, DLL
            $data['category_form'] = $this->db->query("SELECT *
                                            FROM ref_category_form
                                        ")->result();
            $this->template->set('title', 'Add Category ');
            $this->template->load('template', 'master/category/edit', $data);
        } else {
            $category         = $this->input->post('category');
            $category_form    = $this->input->post('category_form');

            $request = array(

                'category'         => strtoupper($category),
                'Category_form'     => strtoupper($category_form),

            );
            $this->db->where('Category_id', $category_id);
            $this->db->update('db_hrd.ref_category_structure', $request);
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil diubah!</b></span>");
            // $this->db->insert('db_hrd.ref_category_structure', $request);
            // $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambah!</b></span>");
            redirect('master/category');
        }
    }


    function hapus($category_id)
    {
        $this->db->where('Category_ID', $category_id);
        $this->db->delete('internal_sms.ref_category_structure');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('master/category/index');
    }
}
