<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Access_denied extends MY_Controller 
{
	
    function __construct()
    {
        parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper('url');
    }
	
	//if error
	function index()
	{
		$data['error'] = 'Maaf, anda tidak dapat mengakses halaman ini.';
		$data['link_back']='<a href="javascript:history.go(-1)">>>Back to previous page<<</a>';
		
		//load view
		$this->template->set('title','Error Page | Dika');
		$this->template->load('template','access_denied',$data);
	}

	//error update
	function update_error()
	{
		$data['title'] = 'Error Page | Access Denied';
		$data['error_message2'] = 'Please click your browser\'s "Back" button or follow the button below to return to home page !!';
		$data['link_back'] = anchor ('dashboard', 'Go Home', array('class'=>'btn btn-success p-l-20 p-r-20'));
		
		//load view
		$this->load->view('errors/html/access_denied',$data);
	}

}

/* End of file error.php */
/* Location: ./application/controllers/error.php */								   