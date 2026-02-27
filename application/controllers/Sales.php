<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('sales_model');
	}
	
	/*function index()
    {
		//get data users
		$data['query'] = $this->sales_model->get_all_data();
	
        //load view
		$this->template->set('title','Data Sales');
		$this->template->load('template','sales/index',$data);
    }*/
	
	function index()
    {
        //load view
		$this->template->set('title','Data Sales');
		$this->template->load('template','sales/index');
    }
	
	function get_data()
    {
	    $image_url = "https://internal.ptdika.com/assets/photos/real/";
        $query = $this->sales_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row){
		    $status_ = $row->Status;
			if ($status_ == 'ACTIVE'){
				$status = '<label class="label label-success">'.$status_.'</label>';
			}
			else if ($status_ =='BATAL JOIN'){
				$status = '<label class="label label-warning">'.$status_.'</label>';
			}
			else {
				$resign_date = date ('d/M/Y', strtotime ($row->Resign_Date));
				$status = '<label class="label label-danger">'.$status_.'</label> <br>'.$resign_date;
			}
            $data[] = array(
				++$no,
				'<img src="'.$image_url.''.$row->Image.'" class="image" width="48" height="48"></a>',
				'<b>Sales Code : '.$row->DSR_Code.'</b><br>
				<b>Sales Name : '.$row->Name.'</b>',
				$row->Branch,
				$row->Position,
				$row->Product,
				$row->Channel,
				$row->Level,
				$row->SPV_Name,
				$status
			);
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sales_model->count_filtered(),
            "recordsFiltered" => $this->sales_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	/*function add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nik','NIK','trim|required|min_length[8]|max_length[8]|is_unique[user_ro.nik]');
		$this->_validation();

		if ($this->form_validation->run() == FALSE)
		{
			$data['area'] = $this->db->get('area');
			
			//load view
			$this->template->set('title','Add Data');
			$this->template->load('template','sales/add',$data);
		}
		else
		{
			$data_sales = array(
				'nik'	=>$this->input->post('nik'),
				'nama'	=>ucwords(strtolower($this->input->post('nama'))),
				'dob'	=>$this->input->post('dob'),
				'password'	=>md5($this->input->post('dob')),
				'spv_code'	=>$this->input->post('spv_code'),
				'spv_name'	=>$this->input->post('spv_name'),
				'area'	=>$this->input->post('area'),
				'level'	=>$this->input->post('level'),
				'created_by'	=>$this->session->userdata('realname')
			);
			$this->sales_model->insert($data_sales);
			
			// redirect to index
			redirect('sales');		
		}
	}
	
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nik','NIK','trim|required|min_length[8]|max_length[8]');
		$this->_validation();
		
		if ($this->form_validation->run() == FALSE)
		{
			//get data
			$data['area'] = $this->db->get('area');
			$data['sales'] = $this->sales_model->get_by_id($id);
			
			//load view
			$this->template->set('title','Edit Data');
			$this->template->load('template','sales/edit',$data);
		}
		else
		{
			$data_sales = array(
				'nik'	=>$this->input->post('nik'),
				'nama'	=>ucwords(strtolower($this->input->post('nama'))),
				'dob'	=>$this->input->post('dob'),
				'spv_code'	=>$this->input->post('spv_code'),
				'spv_name'	=>$this->input->post('spv_name'),
				'area'	=>$this->input->post('area'),
				'level'	=>$this->input->post('level')
			);
			$this->sales_model->update($data_sales,$id);
			
			// redirect to index
			redirect('sales');		
		}
	}
	
	function delete($id)
	{
		$this->sales_model->delete($id);
		// redirect to index
		redirect('sales');	
	}
	
	//================================================= INTERNAL FUNCTION =============================================//
	
	//form validation
	function _validation()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('dob', 'Tanggal Lahir', 'trim|required');
		$this->form_validation->set_rules('spv_code', 'SPV Code', 'trim|required');
		$this->form_validation->set_rules('area', 'Area', 'required');
		$this->form_validation->set_rules('level', 'Level', 'required');
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	}*/
}