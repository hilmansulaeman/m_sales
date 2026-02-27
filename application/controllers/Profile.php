<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MY_Controller
{
    private $model = 'profile_model';
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('url','file'));
		$this->load->library(array('template'));
		$this->load->model($this->model);
		$this->{$this->model}->config('internal.data_sales','DSR_Code');
	}
	
	function idcard()
	{
		$cekIDCard = $this->{$this->model}->cek_id_card();
		if($cekIDCard->num_rows() > 0) {
			$row = $cekIDCard->row();
			$id_card = $row->ID_Card;
			//generate QR
			$this->generate_qr();
		
			//get data
			$id = $this->session->userdata('username');
			$data['query'] = $this->{$this->model}->get_qr($id)->row();
			
			//load view
			$this->load->view('profile/'.$id_card, $data);
			/*if($id_card == 'pemol') {
				$this->load->view('profile/idcard', $data);
			}
			elseif($id_card == 'cc_reg') {
				$this->load->view('profile/idcard_cc', $data);
			}
			else {
				$this->load->view('profile/idcard_ms', $data);
			}*/
		}
		else {
			$data['error'] = 'Maaf, anda tidak memiliki Virtual ID Card.';
			$data['link_back']='<a href="javascript:history.go(-1)">>>Back to previous page<<</a>';
			
			//load view
			$this->template->set('title','ID Card | Dika');
			$this->template->load('template','profile/idcard_non',$data);
		}

		
	}
	
	function idcard5()
	{
		//load view
		$this->load->view('profile/idcard5');
	}
	
	function idcard6()
	{
		//load view
		$this->load->view('profile/idcard6');
	}
	
	function qr()
	{
		//generate QR
		$this->generate_qr();
		
	    //get data
		$id = $this->session->userdata('username');
		$data['query'] = $this->{$this->model}->get_qr($id)->row();
		$data['sales'] = $this->{$this->model}->get_by_id($id)->row();
		
		//load view
		$this->load->view('profile/qr2',$data);
	}
	
	function qr2()
	{
		//generate QR
		$this->generate_qr();
		
	    //get data
		$id = $this->session->userdata('username');
		$data['query'] = $this->{$this->model}->get_qr($id)->row();
		$data['sales'] = $this->{$this->model}->get_by_id($id)->row();
		
		//load view
		$this->load->view('profile/qr2',$data);
	}
	
	private function generate_qr()
	{
	    $this->load->library('ciqrcode');
		$time = time();
		$expiration = time()-7200; // Two hour limit
		$img_path = './upload/qrcode/';
		
		// Check Expire qr
		$getExpire = $this->db->query("SELECT * FROM qr_code WHERE qr_time < ".$expiration);
		//delete old file
		foreach($getExpire->result() as $item){
		    unlink($img_path.$item->filename);
		}
		//delete data
		$this->db->query("DELETE FROM qr_code WHERE qr_time < ".$expiration);
		/*$now = microtime(TRUE);
		$current_dir = @opendir($img_path);
		while ($filename = @readdir($current_dir))
		{
			if (in_array(substr($filename, -4), array('.jpg', '.png'))
				&& (str_replace(array('.jpg', '.png'), '', $filename) + $expiration) < $now)
			{
				@unlink($img_path.$filename);
			}
		}
		@closedir($current_dir);*/
		
		//cek di data apakah sales yg login sudah ada QR
		$user = $this->session->userdata('username');
		$query = $this->{$this->model}->get_qr($user);
		if($query->num_rows() == 0){
		    $params['data'] = $this->session->userdata('username').'-'.$time;
			$params['level'] = 'H';
			$params['size'] = 8;
			$params['expiration'] = 7200;
			$params['savename'] = FCPATH.$img_path.$params['data'].'.png';
			$qr = $this->ciqrcode->generate($params);
			$data = array(
				'qr_time' => $time,
				'ip_address' => $this->input->ip_address(),
				'sales_code' => $user,
				'sales_name' => $this->session->userdata('realname'),
				'filename' => $params['data'].'.png'
			);
			$this->db->insert('qr_code', $data);
			return $qr;
		}
	}
}

/* End of file Profile.php */
/* Location: ./application/modules/data/controllers/Profile.php */