<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Absent_form extends MY_Controller 
{	
    function __construct()
    {
        parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('form','url'));
        $this->load->model('export_model');
    }
	
	//index
	function index()
    {
		//get data
		$this->load->model('db_model');
		$this->db_model->config('schedule_training', 'id');
		$data['query'] = $this->db->get('schedule_training');
		
        //load view
		$this->template->set('title','Export Data | Dika');
		$this->template->load('template','export/absent/index',$data);
	}
	
	//export data
    function export($id)
    {
		$data['id'] = 0;
		$data['error']='';
		
		$getData = $this->export_model->registrant($id);
		if($getData->num_rows() > 0){
			$data['rows'] = $this->db->get_where('schedule_training',array('id'=>$id))->row();
			$data['query'] = $getData->result_array();
		}
		else {
			?>
			<script type="text/javascript" language="javascript">
			alert("No data...!!!");
			</script>
			<?php
			echo "<meta http-equiv='refresh' content='0; url=".site_url()."export/absent_form/'>";
			
			return false;
		}
		//load view
		$this->load->view('export/absent/absent_form',$data);
    }
}

/* End of file absent_form.php */
/* Location: ./application/controllers/export/absent_form.php */								   