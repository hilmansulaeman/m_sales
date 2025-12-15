<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller 
{	
    function __construct()
    {
        parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->helper(array('form', 'url'));
        $this->load->model('db_model');
    }
	
	public function get_upliner($id = NULL) {
        if (!empty($id)) {
			$this->db_model->config('data_sales','DSR_Code');
			$query = $this->db_model->get_by_id($id);

            if ($query->num_rows > 0) {
                $data[] = $query->row_array() ;
                echo json_encode($data) ;
            }
        }
    }
	
	public function get_location($id = NULL) {
        if (!empty($id)) {
			$this->db_model->config('application_sources','code');
			$query = $this->db_model->get_by_id($id);

            if ($query->num_rows > 0) {
                $data[] = $query->row_array() ;
                echo json_encode($data) ;
            }
        }
    }
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */								   