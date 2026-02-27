<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Profile_team extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('profile_team_model');
	}

	function index()
	{
		//$data['query'] = "";
		//load view
		$this->template->set('title','Profile Team');
		$this->template->load('template','profile_team/index_bsh');
	}
	
	function lihat_team_bsh($sales_code)
	{
		$this->template->set('title','Profile Team');
		$this->template->load('template','profile_team/index_bsh');
	}
}