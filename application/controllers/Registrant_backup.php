<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registrant extends MY_Controller
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
		$this->db_model->config('training_participants', 'id');
		$data['query'] = $this->db->query("select * from training_participants order by id desc");
		
		$data['levels'] = $this->db->query("select * from area order by id ASC");
		
        //load view
		$this->template->set('title','Data Registrant');
		$this->template->load('template','registrant/index',$data);
    }
	
	public function get_schedule()
	{
		$data = $this->db->get_where('schedule_training',array('expire'=>'0'))->row_array();
		echo json_encode($data);
	}
	
	public function get_data($id)
	{
		$this->db_model->config('training_participants', 'id');
		$data = $this->db_model->get_by_id($id)->row_array();
		echo json_encode($data);
	}
	
	function add()
	{
		$this->db_model->config('training_participants', 'id');
		
		$slug = $this->uri->segment(3);
		if($slug != "")
		{
			$filter= " where slug ='$slug'";
		}
		else
		{
			$filter = "";
		}
		$data['sql_sch'] = $this->db->query("select * from schedule_training $filter order by id ASC");
		
		
		$replace = str_replace('-', ' ', $this->uri->segment(3));
		$replace1 = str_replace('-', ' ', $this->uri->segment(4));
		$data['levels'] = $this->db->query("select * from area order by id ASC");
		
		$getData = $this->db_model->location($replace);
		$data['location'] = $getData->result_array();
		
		$getData1 = $this->db_model->available_date($replace, $replace1);
		$data['tanggal'] = $getData1->result_array();
		
		$getData2 = $this->db_model->available_time($replace, $replace1, $this->uri->segment(5));
		$data['time'] = $getData2->result_array();
		
		//load view
		$this->template->set('title','Input Data Registrant');
		$this->template->load('template','registrant/add',$data);
	}
	
	function list_schedule($id)
	{
		$this->db_model->config('schedule_training', 'slug');
        $data['query'] = $this->db->get('schedule_training');
		
		$data['query'] = $this->db->get_where('schedule_training',array('expire'=>'0'));
		
		//load view
		$this->template->set('title','Input Data Registrant');
		$this->template->load('template','registrant/list_schedule',$data);
	}
	
	function update_sch()
	{
		$this->db_model->config('training_participants', 'id');
		
		$idSch = $this->uri->segment(5);
		$id = $this->uri->segment(3);
		
		$data = $this->db->query("select * from schedule_training where id='$idSch'");
		
		$idOldSch =  $this->uri->segment(4);
		$updateSeatOld = $this->db_model->update_seat_old($idOldSch);
		$updateSeatNew = $this->db_model->update_seat_new($idSch);
		
		$post = $this->input->post('idSchedule');
		foreach($data->result() as $row)
		{
			$data_request = array(
				'Area'	=>$row->area,
				'Location'	=>$row->location,
				'tgl_training'	=>$row->available_date,
				'waktu_training' =>$row->time,
				'schedule_id'	=>$row->id,
				'Modified_Date'=>date('Y-m-d H:i:s'),
				'Modified_by'	=>$this->session->userdata('realname')
			);
			$this->db_model->update($data_request,$id);
		}
		redirect('registrant', 'refresh');
	}
	
	function insert_data_reg()
	{
		$this->db_model->config('training_participants', 'id');
		
		$data_request = array(
			'area'	=>$this->input->post('area'),
			'location'	=>$this->input->post('location'),
			'tgl_training'	=>$this->input->post('tanggal'),
			'waktu_training'	=>$this->input->post('waktu'),
			'nama_kasir'	=>$this->input->post('nama_kasir'),
			'tlp_kasir' =>$this->input->post('tlp_kasir'),
			'nama_merchant'	=>$this->input->post('nama_merchant'),
			'mid'	=>$this->input->post('mid'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->insert($data_request);
		redirect('registrant', 'refresh');
	}
	
	function update_peserta()
	{
		$this->db_model->config('training_participants', 'id');
		$id = $this->input->post('id');
		$data_request = array(
			'nama_kasir'	=>strtoupper($this->input->post('nama_kasir')),
			'ktp' =>$this->input->post('ktp'),
			'tgl_lahir' =>$this->input->post('tgl_lahir'),
			'alamat_rumah'	=>$this->input->post('alamat_rumah'),
			'tlp_kasir' =>$this->input->post('tlp_kasir'),
			'email'	=>$this->input->post('email'),
			'facebook'	=>$this->input->post('facebook'),
			'pin_bb'	=>$this->input->post('pin_bb'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_merchant()
	{
		$this->db_model->config('training_participants', 'id');
		$id = $this->input->post('id');
		$data_request = array(
			'nama_merchant'	=>strtoupper($this->input->post('nama_merchant')),
			'alamat_merchant'	=>$this->input->post('alamat_merchant'),
			'mid'	=>$this->input->post('mid'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_training()
	{
		$this->db_model->config('training_participants', 'id');
		$id = $this->input->post('id');
		$data_request = array(
			'schedule_id'	=>$this->input->post('schedule_id'),
			'Area' =>$this->input->post('Area'),
			'Location' =>$this->input->post('Location'),
			'tgl_training'	=>$this->input->post('tgl_training'),
			'waktu_training' =>$this->input->post('waktu_training'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_status()
	{
		$this->db_model->config('training_participants', 'id');
		$id = $this->input->post('id');
		$data_request = array(
			'status_schedule'	=>$this->input->post('status_schedule'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_absen()
	{
		$this->db_model->config('training_participants', 'id');
		$id = $this->input->post('id');
		$data_request = array(
			'status_kehadiran'	=>$this->input->post('status_kehadiran'),
			'Modified_Date'=>date('Y-m-d H:i:s'),
			'Modified_by'	=>$this->session->userdata('realname')
		);
		$this->db_model->update($data_request,$id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_status_kehadiran()
	{
		$this->db_model->config('training_participants', 'id');
		$tsid_ = $this->input->post('tsid');
		
		foreach($tsid_ as $ids)
		{
			$data_request = array(
				'status_kehadiran'	=> $this->input->post('status_kehadiran_'.$ids),
				'Modified_Date'=>date('Y-m-d H:i:s'),
				'Modified_by'	=>$this->session->userdata('realname')
			);
			$this->db_model->update($data_request,$ids);
		}
		redirect('schedule', 'refresh');
	}
	
	function detail($id)
    {
        //load data
		$this->db_model->config('training_participants', 'id');
		$data['request_data'] = $this->db_model->get_by_id($id);
		
        //load view
		$this->template->set('title','Detail Data');
		$this->template->load('template','registrant/detail',$data);
		
    }
	
	function delete($id)
	{
		$this->db_model->config('training_participants', 'id');
		$this->db_model->delete($id);
		
        //redidrect to index 
        redirect ('registrant', 'refresh');
	}
}

/* End of file registrant.php */
/* Location: ./application/controllers/registrant.php */