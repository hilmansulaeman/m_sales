<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Participant extends MY_Controller 
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
		$this->template->load('template','export/participant/index');
	}
	
	//export daily
    function daily()
    {
		$data['id'] = 0;
		$data['error']='';
		
		//date 1
		$day1 = $this->input->post('date_training1');
		$month1 = $this->input->post('month_training1');
		$year1 = $this->input->post('year_training1');
		$date1 = $year1.'-'.$month1.'-'.$day1;
		
		//date2
		$day2 = $this->input->post('date_training2');
		$month2 = $this->input->post('month_training2');
		$year2 = $this->input->post('year_training2');
		$date2 = $year2.'-'.$month2.'-'.$day2;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('date_training1', 'Start Date', 'required');
		$this->form_validation->set_rules('month_training1', 'Start Date', 'required');
		$this->form_validation->set_rules('year_training1', 'Start Date', 'required');
		$this->form_validation->set_rules('date_training2', 'End Date', 'required');
		$this->form_validation->set_rules('month_training2', 'End Date', 'required');
		$this->form_validation->set_rules('year_training2', 'End Date', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{			
			redirect ('export/participant/');
		}
		else
		{
			$getData = $this->export_model->participant_daily($date1,$date2);
			if($getData->num_rows() > 0){
            	$data['query'] = $getData->result_array();
			}
        	else {
				?>
				<script type="text/javascript" language="javascript">
                alert("No data...!!!");
                </script>
                <?php
				echo "<meta http-equiv='refresh' content='0; url=".site_url()."export/participant/'>";
				
				return false;
			}
			//load view
			$this->load->view('export/participant/excel_participant',$data);
		}
    }
	
	//export data
    function monthly()
    {
		$data['id'] = 0;
		$data['error']='';
		
		$month = $this->input->post('month_training');
		$year = $this->input->post('year_training');
		$date = $year.'-'.$month;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('month_training', 'Periode', 'required');
		$this->form_validation->set_rules('year_training', 'Periode', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{			
			redirect ('export/participant/');
		}
		else
		{
		
			$getData = $this->export_model->participant_monthly($date);
			if($getData->num_rows() > 0){
				$data['query'] = $getData->result_array();
			}
			else {
				?>
				<script type="text/javascript" language="javascript">
				alert("No data...!!!");
				</script>
				<?php
				echo "<meta http-equiv='refresh' content='0; url=".site_url()."export/participant/'>";
				
				return false;
			}
			//load view
			$this->load->view('export/participant/excel_participant',$data);
		}
    }
}

/* End of file request.php */
/* Location: ./application/controllers/export/request.php */								   