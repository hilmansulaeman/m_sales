<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('users_model');
	}
	
	function index()
    {
		//get data users
		$data['query'] = $this->users_model->get_all_users();
	
        //load view
		$this->template->set('title','Data Users');
		$this->template->load('template','users/index',$data);
    }
	
	function add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|is_unique[users.username]');
		$this->_validation();

		if ($this->form_validation->run() == FALSE)
		{
			$data['level'] = $this->users_model->get_all_level();
			
			//load view
			$this->template->set('title','Add User');
			$this->template->load('template','users/add',$data);
		}
		else
		{
			$data_user = array(
				'name'	=>ucwords($this->input->post('name')),
				'email'	=>$this->input->post('email'),
				'status'	=> 'Active',
				'username'	=>$this->input->post('username'),
				'password'	=>md5($this->input->post('password')),
				'privilege'	=>$this->input->post('privilege'),
				'created_by'	=>$this->session->userdata('realname')
			);
			$this->users_model->insert($data_user);
			
			// redirect to index
			redirect('users');		
		}
	}
	
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]');
		$this->_validation();
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['level'] = $this->users_model->get_all_level();
			// get data users
			$data['user'] = $this->users_model->get_by_id($id);
			
			//load view
			$this->template->set('title','Edit User');
			$this->template->load('template','users/edit',$data);
		}
		else
		{
			$data_user = array(
				'name'	=>ucwords($this->input->post('name')),
				'email'	=>$this->input->post('email'),
				'status'	=>$this->input->post('status'),
				'username'	=>$this->input->post('username'),
				'password'	=>md5($this->input->post('password')),
				'privilege'	=>$this->input->post('privilege')
			);
			$this->users_model->update($data_user,$id);
			
			// redirect to index
			redirect('users');		
		}
	}
	
	function delete($id)
	{
		$this->users_model->delete($id);
		// redirect to index
		redirect('users');	
	}
	
	//================================================= INTERNAL FUNCTION =============================================//
	
	//form validation
	function _validation()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'trim|required|matches[password]');
		$this->form_validation->set_rules('privilege', 'Privilege', 'trim|required');
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	}
}