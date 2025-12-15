<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once APPPATH.'third_party/spout/src/Spout/Autoloader/autoload.php';

//use Box\Spout\Reader\ReaderFactory;
//use Box\Spout\Common\Type;

class edc extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model(array('incoming/edc_model'));
	}

	function get_status() {
		$sales_code = $this->session->userdata('username');
		$position = $this->session->userdata('position');
		$tgl = date('d');
		$bln = date('m');
		$thn = date('Y');
		$tgl1 = $thn.'-'.$bln.'-01';

		$tgl2 = $thn.'-'.$bln.'-'.$tgl;

		$data['sql_total'] = $this->edc_model->total($sales_code, $position, $tgl1, $tgl2);
		$data['status_aplikasi'] = $this->db->query("SELECT * FROM `internal`.`edc_status` WHERE status NOT IN('return_to_dika','CANCEL')");
		$data['breakdown_edc'] = $this->edc_model->m_breakdown_edc($sales_code, $position, $tgl1, $tgl2);
        //load view
		$this->template->set('title','Data Incoming EDC');
		$this->template->load('template','incoming/edc/status_aplikasi', $data);
	}
	
	function index($status_aplikasi, $tgl1, $tgl2)
    {
    	$this->session->set_userdata('status_aplikasi', $status_aplikasi);
    	$this->session->set_userdata('tgl1', $tgl1);
    	$this->session->set_userdata('tgl2', $tgl2);

        $sales_code = $this->session->userdata('username');
        $this->session->set_userdata('sales_code', $sales_code);

        $position = $this->session->userdata('position');
        $this->session->set_userdata('position', $position);

        //load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','incoming/edc/index');
    }
	
	function get_data_incoming()
    {
        $query = $this->edc_model->get_datatables();
		$count_data = $this->edc_model->count_all()->row(); //
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row){
			$hitcode = $row->Hit_Code;
			$status = $row->Status;
			if($status == 'REJECT') {
				$note = '<br><span style="color:red">Note : '.$row->Note.'</span>';
			}
			elseif($status == 'RETURN_TO_SALES') {
				if($hitcode == '103') {
					$note = '<br><span style="color:red">Reason : '.$row->Reason_Category.' | '.$row->Reason_Detail.'</span>';
				}
				else {
					$note = '<br><span style="color:red">Reason : '.$row->FU_Reason.'</span>';
				}
			}
			elseif($status == 'PENDING_FU') {
				$note = '<br><span style="color:red">Note : '.$row->FU_Reason.'</span>';
			}
			elseif($status == 'RETURN_FROM_BCA') {
				$note = '<br><span style="color:red">Note : '.$row->Return_BCA_Reason.'</span>';
			}
			else {
				$note = '';
			}
        	
		    $data[] = array(
				++$no,
				'<b>Nama Merchant</b> : '.$row->Merchant_Name.'<br>'.
				'<b>Nama Owner</b> : '.$row->Owner_Name.'<br>'.
				'<b>No. Handphone</b> : '.$row->Mobile_Phone_Number.'<br>'.
				'<b>Jenis Pengajuan</b> : '.$row->Product_Type.'<br>'.
				'<b>Tipe MID</b> : '.$row->MID_Type.'<br>'.
				'<b>Email</b> : '.$row->Email.$note
			);
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->edc_model->count_filtered(),
            "recordsFiltered" => $this->edc_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

}