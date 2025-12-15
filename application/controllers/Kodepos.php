<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


/**
* 
*/
class Kodepos extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
		$this->load->library('pagination');
		$this->load->model('kodepos_model');
	}

	function index()
	{
		$config = array();
        $config["base_url"] = base_url() . "kodepos/index";
        $config["total_rows"] = $this->kodepos_model->record_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->kodepos_model->fetch_countries($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        //$this->load->view("example1", $data);
		
		//$data['query'] = $this->kodepos_model->datakodepos();
		//load view
		$this->template->set('title','Data Kodepos');
		$this->template->load('template','kodepos/index', $data);
	}
}