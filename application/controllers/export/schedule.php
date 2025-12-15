<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends MY_Controller 
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
        //load view
		$this->template->set('title','Export Data | Dika');
		$this->template->load('template','export/schedule/index');
	}
	
	//export monthly
    function monthly()
    {
		$data['id'] = 0;
		$data['error']='';
		$month = $this->input->post('month_request2');
		$year = $this->input->post('year_request2');
		$date = $year.'-'.$month;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('month_request2', 'Date', 'required');
		$this->form_validation->set_rules('year_request2', 'Date', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{			
			redirect ('export/schedule/');
		}
		else
		{
			$getData = $this->export_model->schedule_training($date);
			if($getData->num_rows() > 0){
            	$data['query'] = $getData->result_array();
			}
        	else {
				?>
				<script type="text/javascript" language="javascript">
                alert("No data...!!!");
                </script>
                <?php
				echo "<meta http-equiv='refresh' content='0; url=".site_url()."export/request/'>";
				
				return false;
			}
			//load view
			$this->load->view('export/schedule/excel_schedule',$data);
		}
    }
}

/* End of file request.php */
/* Location: ./application/controllers/export/request.php */								   