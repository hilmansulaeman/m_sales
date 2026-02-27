<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category_Form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template', 'form_validation'));
        $this->load->model('master/Category_Form_model');
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Kategori Form');
        $this->template->load('template', 'master/category_form/index');
    }

    function get_data_category()
    {
        $query = $this->Category_Form_model->get_datatables();
        $count_data = $this->Category_Form_model->count_all()->row();

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $onclick = "return confirm('Anda yakin akan menghapus data ini??')";
            
            $action = '<a href="' . site_url() . "master/Category_Form/hapus/" . $row->Category_Form_ID . '"  onclick="'.$onclick.'"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Hapus Data"></i></span></a>
            <a href="' . site_url() . "master/Category_Form/edit/" . $row->Category_Form_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-pencil" title="Edit Data"></i></span></a>';

            $data[] = array(
                ++$no,
                $row->Category_Form,
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->Category_Form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add()
    {
        $this->form_validation->set_rules('category_form', 'category_form', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->template->set('title', 'Add Category');
            $this->template->load('template', 'master/category_form/add');
        } else {

            $category_form    = $this->input->post('category_form');

            $request = array(

                'Category_Form'     => strtoupper($category_form)

            );
            $this->db->insert('internal_sms.ref_category_form', $request);
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambah!</b></span>");
            redirect('master/Category_Form/index');
        }
    }

    function edit($category_id)
    {

        $this->form_validation->set_rules('category_form', 'category_form', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori'] = $this->Category_Form_model->edit($category_id);
            $this->template->set('title', 'Add Category');
            $this->template->load('template', 'master/category_form/edit', $data);
        } else {
            $category_form    = $this->input->post('category_form');

            $request = array(

                'Category_Form'    =>  strtoupper($category_form)
            );
            $this->db->where('Category_Form_ID', $category_id);
            $this->db->update('internal_sms.ref_category_form', $request);
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil diubah!</b></span>");
            redirect('master/Category_Form/index');
        }
    }


    function hapus($category_id)
    {
        $this->db->where('Category_Form_ID', $category_id);
        $this->db->delete('internal_sms.ref_category_form');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('master/Category_Form/index');
    }
}
