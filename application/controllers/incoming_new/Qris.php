<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once APPPATH.'third_party/spout/src/Spout/Autoloader/autoload.php';

//use Box\Spout\Reader\ReaderFactory;
//use Box\Spout\Common\Type;

class Qris extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model(array('incoming/qris_model'));
	}

	function get_status() {
		$sales_code = $this->session->userdata('username');
		$position = $this->session->userdata('position');
		$tgl = date('d');
		$bln = date('m');
		$thn = date('Y');
		$tgl1 = $thn.'-'.$bln.'-01';

		$tgl2 = $thn.'-'.$bln.'-'.$tgl;

		$data['sql_total'] = $this->qris_model->total($sales_code, $position, $tgl1, $tgl2);
		$data['status_aplikasi'] = $this->qris_model->get_status_api();
		$data['breakdown_qris'] = $this->qris_model->m_breakdown_qris($sales_code, $position, $tgl1, $tgl2);
        //load view
		$this->template->set('title','Data Incoming QRIS');
		$this->template->load('template','incoming/qris/status_aplikasi', $data);
	}
	
	function index($status_aplikasi, $tgl1, $tgl2)
    {
    	$this->session->set_userdata('status_aplikasi', $status_aplikasi);
    	$this->session->set_userdata('tgl1', $tgl1);
    	$this->session->set_userdata('tgl2', $tgl2);

        $sales_code = $this->session->userdata('username');
        $this->session->set_userdata('sales_code', $sales_code);

        //load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','incoming/qris/index');
    }
	
	function get_data_Incoming()
    {
		$status_aplikasi = $this->session->userdata('status_aplikasi');
        $sales_code = $this->session->userdata('username');
        $position = $this->session->userdata('position');
		$tgl1 = $this->session->userdata('tgl1');
		$tgl2 = $this->session->userdata('tgl2');

		if($position == 'DSR') {
			$where1 = "b.DSR_Code = '$sales_code'";
		}
		else if($position == 'SPV') {
			$where1 = "b.SPV_Code = '$sales_code'";
		}
		else if($position == 'ASM') {
			$where1 = "b.ASM_Code = '$sales_code'";
		}
		else if($position == 'RSM') {
			$where1 = "b.RSM_Code = '$sales_code'";
		}
		else {
			$where1 = "b.BSH_Code = '$sales_code'";
		}
		
        if($status_aplikasi == 'submit_to_dika') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '105' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'pending_fu') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status IN('PENDING_FU','SUBMIT_TO_DIKA') AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'return_to_sales') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code IN('103','104') AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'submit_to_bca') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'SUBMIT_TO_BCA' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'return_from_bca') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RETURN_FROM_BCA' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'resubmit_to_bca') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_BCA' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'resubmit_to_dika') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_DIKA' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'cancel') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'CANCEL' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'reject') {
            $where = "a.Product_Type = 'QRIS' AND $where1 AND a.Hit_Code = '106' AND a.Status = 'REJECT' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
		
		$query = $this->qris_model->_get_datamerchant_query($where);
		
        //$query = $this->merchant_model->get_datatables();
		//$count_data = $this->merchant_model->count_all()->row();
        $data = array();
        $no = $this->input->post['start'];
        foreach ($query as $row){

		    $data[] = array(
				++$no,
				'<b>Nama Merchant</b> : '.$row->Merchant_Name.'<br>'.
				'<b>Nama Owner</b> : '.$row->Owner_Name.'<br>'.
				'<b>No. Handphone</b> : '.$row->Mobile_Phone_Number.'<br>'.
				'<b>Jenis Pengajuan</b> : '.$row->Product_Type.'<br>'.
				'<b>Tipe MID</b> : '.$row->MID_Type.'<br>'.
				'<b>Email</b> : '.$row->Email
			);
        }
 
        $output = array(
            "draw" => $this->input->post['draw'],
            "recordsTotal" => $this->qris_model->_count_datamerchant($where),
            "recordsFiltered" => $this->qris_model->_count_datamerchant($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	function get_data_Incoming_()
    {
        $query = $this->qris_model->get_datatables();
		$count_data = $this->qris_model->count_all()->row(); //
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row){
        	
		    $data[] = array(
				++$no,
				'<b>Nama Merchant</b> : '.$row->Merchant_Name.'<br>'.
				'<b>Nama Owner</b> : '.$row->Owner_Name.'<br>'.
				'<b>No. Handphone</b> : '.$row->Mobile_Phone_Number.'<br>'.
				'<b>Jenis Pengajuan</b> : '.$row->Product_Type.'<br>'.
				'<b>Tipe MID</b> : '.$row->MID_Type.'<br>'.
				'<b>Email</b> : '.$row->Email
			);
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->qris_model->count_filtered(),
            "recordsFiltered" => $this->qris_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

}