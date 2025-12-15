<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Location extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper(array('url', 'html'));
		$this->load->model('location_model');
	}

	function index()
	{
		$data['query'] = $this->location_model->getData();
		$data['title'] = 'Location';
		//Load View
		$this->template->load('template', 'meeting/location/index', $data);
	}

	function get_data_location()
	{
		$query = $this->location_model->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($query->result() as $row) {

			$data[] = array(
				++$no,
				$row->Location_Name,
				$row->Location_Address,
				$row->Location_City,
				// $row->Quota,
				'<a href="javascript:void(0);" onclick="ShowDetail(' . $row->Location_ID . ')" class="btn btn-default btn-icon btn-circle btn-sm"><i class="fa fa-eye"></i></a>
				<a href="javascript:void(0);" onclick="ShowEdit(' . $row->Location_ID . ')" class="btn btn-primary btn-icon btn-circle btn-sm"><i class="fa fa-pencil"></i></a>
				<a href="' . base_url() . 'meeting/location/hapus_data/' . $row->Location_ID . '" onclick="return confirm(\'Apakah anda yakin akan menghapus data ini ?\')" class="btn btn-danger btn-icon btn-circle btn-sm"><i class="fa fa-times"></i></a>',

			);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->location_model->count_filtered(),
			"recordsFiltered" => $this->location_model->count_filtered(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function save()
	{
		$request = array(
			'Location_Name'		=> $this->input->post('location_name'),
			'Location_Address'	=> $this->input->post('location_address'),
			'Location_City'		=> $this->input->post('location_city'),
			// 'Quota'				=>$this->input->post('quota')
		);
		$this->location_model->insert_data($request);
		$this->session->set_flashdata('message', 'Data berhasil disimpan.');
		redirect('meeting/location');
	}

	function ubah($Location_ID)
	{
		$request = array(
			'Location_Name'		=> $this->input->post('location_name'),
			'Location_Address'	=> $this->input->post('location_address'),
			'Location_City'		=> $this->input->post('location_city'),
			//'Quota'				=> $this->input->post('quota')
		);
		$this->location_model->update_data($Location_ID, $request);
		$this->session->set_flashdata('message', 'Data berhasil diubah.');
		redirect('meeting/location');
	}

	function getDetail($Location_ID)
	{
		$data['query'] = $this->location_model->getDataById($Location_ID);

		//view
		$this->load->view('meeting/location/detail', $data);
	}

	function getEdit($Location_ID)
	{
		$data['query'] = $this->location_model->getDataById($Location_ID);
		//view
		$this->load->view('meeting/location/edit', $data);
	}

	function hapus_data($Location_ID)
	{
		$this->location_model->delete_data($Location_ID);
		$this->session->set_flashdata('message', 'Data berhasil dihapus.');
		redirect('meeting/location');
	}
}
