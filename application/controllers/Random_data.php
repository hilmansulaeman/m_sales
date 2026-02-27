<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Random_data extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('Model_randomdata');
	}
	
	function index()
	{
		//Filter data
		$tglawal = $this->input->post('Start_date');
		$tglakhir = $this->input->post('End_date');
		
		if($tglawal != null and $tglakhir != null)
		{
			$sql = "select * from training_participants where status_kehadiran='Hadir' and flag_win='0' and tgl_training BETWEEN '$tglawal' and '$tglakhir' order by RAND() limit 0,1";
		}
		else
		{
			$sql = "select * from training_participants where status_kehadiran='Hadir' and flag_win='0' order by id asc limit 0,0";
		}
		
		//get data random
		$data['query'] = $this->Model_randomdata->get_all_data($sql);
		$data['sql'] = $this->Model_randomdata->getData();
		
		//load view
		$this->template->set('title', 'Random Data');
		$this->template->load('template', 'randomdata/index', $data);
	}
	
	function update_flag($id)
	{
		$flag = "1";
		$data_request = array(
			'flag_win'	=>strtoupper($this->input->post('nama_kasir')),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->Model_randomdata->update_flag($data_request, $id);
		redirect('random_data', 'refresh');
	}
}