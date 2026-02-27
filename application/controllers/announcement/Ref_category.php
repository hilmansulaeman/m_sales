<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ref_category extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library(array('template'));
        $this->load->model('announcement/category_model');
    }

    function index()
    {
        //load view
        $this->template->set('title', $this->config->item('site_name') . ' | Category');
        $this->template->load('template', 'announcement/ref_category/index');
    }

    function get_data_ref_category()
    {
        $query = $this->category_model->get_datatables();
        $count_data = $this->category_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $category_id = $row->Category_ID;
            $onclick = "return confirm('Anda yakin akan menghapus data ini??')";
            $action = '<a href="' . site_url() . "announcement/ref_category/delete/" . $category_id . '" onclick="' . $onclick . '"><span class="btn btn-xs btn-danger"><i class="fa fa-trash" title="Hapus Data"></i></span></a>';

            $data[] = array(
                ++$no,
                $row->Category,
                $row->Schedule,
                $row->Schedule_tgl,
                $row->Schedule_bln,
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category', 'Category', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('schedule', 'Schedule', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('schedule_tgl', 'Schedule Tanggal', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);
        $this->form_validation->set_rules('schedule_bln', 'Schedule Bulan', 'trim|required', [
            'required' => '{field} harus di isi!'
        ]);



        $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

        if ($this->form_validation->run() == FALSE) {

            $this->template->set('title', 'Add Category');
            $this->template->load('template', 'announcement/ref_category/add');
        } else {
            $data_insert = array(
                'Category'    => strtoupper($this->input->post('category')),
                'Schedule'    => $this->input->post('schedule'),
                'Schedule_tgl'    => $this->input->post('schedule_tgl'),
                'Schedule_bln'    => $this->input->post('schedule_bln')

            );


            $this->db->insert('ref_announcement_category', $data_insert);


            $this->session->set_flashdata('message', "<b>Data berhasil ditambah!</b>");

            //Direct ke view
            redirect('announcement/ref_category');
        }
    }

    function delete($category_id)
    {
        $this->db->where('Category_ID', $category_id);
        $this->db->delete('ref_announcement_category');

        //MESSAGE
        $this->session->set_flashdata('message', "<b>Data berhasil dihapus!</b>");

        redirect('announcement/ref_category');
    }
}
