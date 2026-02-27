<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		//load library dan helper yg dibutuhkan
        $this->load->library(array('table','template'));
        $this->load->helper(array('form','url'));
        $this->load->model('db_model');
	}
	
	function index()
    {
        //get data
		$this->db_model->config('schedule_training', 'id');
		$data['query'] = $this->db->get_where('schedule_training',array('expire'=>0));
		
        //load view
		$this->template->set('title','Schedule Training');
		$this->template->load('template','schedule/index',$data);
    }
	
	function detail($id)
    {
        //load data
		$this->db_model->config('schedule_training', 'id');
		$data['request_data'] = $this->db_model->get_by_id($id);
		
		$data['levels'] = $this->db->query("select * from area order by id ASC");
	
        //load view
		$this->template->set('title','Detail Data');
		$this->template->load('template','schedule/detail',$data);
    }
	
	function update_detail()
	{
		$this->db_model->config('schedule_training', 'id');
		$id = $this->input->post('idDet');
		$data_request = array(
			'area'	=>$this->input->post('area'),
			'location'=>$this->input->post('location'),
			'available_date'	=>$this->input->post('available_date'),
			'time'	=>$this->input->post('time'),
			'quota'	=>$this->input->post('quota')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
		redirect ('schedule', 'refresh');
	}
	
	function registrant($id)
    {
        //load data
		$data['rows'] = $this->db->get_where('schedule_training',array('id'=>$id))->row();
		$data['query'] = $this->db->get_where('training_participants',array('schedule_id'=>$id,'status_schedule'=>'Register'));
	
        //load view
		$this->template->set('title','Detail Data');
		$this->template->load('template','schedule/registrant',$data);
    }
	
	function detail_registrant($id)
    {
        //load data
		$this->db_model->config('training_participants', 'id');
		$data['request_data'] = $this->db_model->get_by_id($id);
	
        //load view
		$this->template->set('title','Detail Data');
		$this->template->load('template','schedule/detail_registrant',$data);
    }
	
	function delete($id)
	{
		$this->db_model->config('schedule_training', 'id');
		$this->db_model->delete($id);
		
        //redidrect to index 
        redirect ('schedule', 'refresh');
	}
}

/* End of file request.php */
/* Location: ./application/controllers/request.php */