<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		//load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url','form','file'));
	}
	
	//index
	function index()
    {			
        //load view
		$this->template->set('title','Upload Data | Dika');
		$this->template->load('template','upload/index');
    }

    function do_upload()
	{
		$config['upload_path'] = './temp_upload/';
		$config['allowed_types'] = 'xls|xlsx';
                
		$this->load->library('upload', $config);
                

		if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			
		}
		else
		{
            $data = array('error' => false);
			$upload_data = $this->upload->data();

            $this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');

			$file =  $upload_data['full_path'];
			$this->excel_reader->read($file);
			error_reporting(E_ALL ^ E_NOTICE);

			// Sheet 1
			$data = $this->excel_reader->sheets[0] ;
            $dataexcel = array();
			for ($i = 1; $i <= $data['numRows']; $i++) 
			{

				if ($data['cells'][$i][1] == '') break;
				if ($data['cells'][$i][2] == '') break;
				//if ($data['cells'][$i][8] == NULL) { $data['cells'][$i][8]=''; }
				
				$dataexcel[$i-1]['area'] = $data['cells'][$i][2];
				$dataexcel[$i-1]['location'] = $data['cells'][$i][3];
				$dataexcel[$i-1]['training_day'] = $data['cells'][$i][4];
				$dataexcel[$i-1]['available_date'] = date('Y-m-d',strtotime('1899-12-31+'.($data['cells'][$i][5]-1).'days'));
				$dataexcel[$i-1]['time'] = $data['cells'][$i][6];
				$dataexcel[$i-1]['quota'] = $data['cells'][$i][7];
				$dataexcel[$i-1]['available_seat'] = $data['cells'][$i][8];
				$dataexcel[$i-1]['expire'] = '0';
			}
                                                
            delete_files($upload_data['file_path']);
            $this->load->model('upload_model');
            $this->upload_model->do_upload($dataexcel);
		}
		$total_upload = $data['numRows'] - 1;
		$data['link_back'] = anchor ('upload/', 'Return to Upload Form', array('class'=>'back'));
		if($total_upload > 0){
			$data['message'] = "Upload has been successfully finished, ".$total_upload." queries executed";
		}
		else{
			$data['message'] = "No data uploaded";
		}
		
        //view result
		$this->template->set('title','Export Data Result | Dika');
		$this->template->load('template','upload/result',$data);
	}
}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */