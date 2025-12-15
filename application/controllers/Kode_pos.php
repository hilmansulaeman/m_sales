<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Kode_pos extends MY_Controller
{
	private $limit = 10;
	function __construct()
	{
		parent::__construct();
		//load library dan helper yg dibutuhkan
		$this->load->library('template');
        $this->load->helper(array('form', 'url', 'html'));
		$this->load->model('db_model');
	}
	
	function index()
	{
		$data = array();
        //total rows count
        $totalRec = count($this->db_model->getKota());
        
        //pagination configuration
		$this->load->library('ajax_pagination');
        $config['target']      = '#listKota';
        $config['base_url']    = site_url().'kode_pos/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->limit;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //get data
        $data['query'] = $this->db_model->getKota(array('limit'=>$this->limit));
        
        //load the view
		$this->template->set('title','Data Kode Pos | DIKA');
		$this->template->load('template','kode_pos/index',$data);
	}
	
	function ajaxPaginationData()
	{
        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        //total rows count
        $totalRec = count($this->db_model->getKota($conditions));
        
        //pagination configuration
		$this->load->library('ajax_pagination');
        $config['target']      = '#listKota';
        $config['base_url']    = site_url().'kode_pos/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->limit;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->limit;
        
        //get posts data
		$data['query'] = $this->db_model->getKota($conditions);
        
        //load the view
		$this->load->view('kode_pos/ajax-pagination-data', $data, false);
    }
}