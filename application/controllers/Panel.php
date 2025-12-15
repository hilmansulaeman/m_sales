<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel extends CI_Controller
{
	private $model = 'panel_model';
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model($this->model);
		error_reporting(0);
	}
	
	public function index()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			redirect('');
		}
	}

	public function login()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_check_status');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			
			$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

			if ($this->form_validation->run() == FALSE)
			{
				$data['title'] = 'Login Form | Sales Dika';
				$this->load->view('login',$data);
			}
			else
			{
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				if($this->auth->active_session($username) == false){
					$data['title'] = 'Login Form | DIKA';
					$data['login_info'] = "User sudah login di tempat lain..!";
					//Load View
					$this->load->view('login',$data);
				}else{
					//$success = $this->auth->do_login($username,$password);
					if (strpos($username, 'K') !== false OR strpos($username, 'F') !== false OR strpos($username, 'P') !== false OR strpos($username, 'W') !== false){
						$success = $this->auth->do_login($username,$password);
					}
					else {
						$success = $this->auth->do_login_user($username,$password);
					}
					
					if($success)
					{
						//insert user activity to database
						$log= array(
									'username' => $username,
									'message' => 'login sukses',
									'action'  => 'login',
									'from_url' => 'http://'.$_SERVER['HTTP_HOST'], //.$_SERVER['PHP_SELF'],
									'from_ip' => $_SERVER["REMOTE_ADDR"]
								);		
						$this->db->insert('user_logs', $log);
						
						//Check MTV
						$position = $this->session->userdata('position');
						$product = $this->session->userdata('product');
						$channel = $this->session->userdata('channel');
						$position_filter = array('DSR','SPV');
						if(in_array($position,$position_filter)){
							$where = " AND product = '$product'";
						}
						else{
							$where = "";
						}
						$get_mtv = $this->db->query("SELECT * FROM ref_program WHERE is_active = '1' $where");
						if($get_mtv->num_rows() > 0){
							$row_mtv = $get_mtv->row();
							//$is_alert = $row_mtv->is_alert;
							$this->session->set_userdata('poster_file',$row_mtv->poster);
							$this->session->set_flashdata(array('poster'=>TRUE));
						}
						
						// redirect to home
						redirect('');
					}
					else{
						$data['title'] = 'Login Form | Sales Dika';
						$data['login_info'] = "Wrong username or password..!";
						
						//insert user activity to database
						$log= array(
									'username' => $username,
									'message' => 'login gagal',
									'action'  => 'login',
									'from_url' => 'http://'.$_SERVER['HTTP_HOST'], //.$_SERVER['PHP_SELF'],
									'from_ip' => $_SERVER["REMOTE_ADDR"]
								);		
						$this->db->insert('user_logs', $log);
						
						//Load View
						$this->load->view('login',$data);
					}
				}
			}
		}else{
			redirect('');
		}
	}
	
	function logout()
	{
		if($this->auth->is_logged_in() == true)
		{
			// jika dia memang sudah login, destroy session
			$this->auth->do_logout();
			
			//insert user activity to database
			$log= array(
						'username' => $this->session->userdata('username'),
						'message' => 'logout sukses',
						'action'  => 'logout',
						'from_url' => 'http://'.$_SERVER['HTTP_HOST'], //.$_SERVER['PHP_SELF'],
						'from_ip' => $_SERVER["REMOTE_ADDR"]
					);		
			$this->db->insert('user_logs', $log);
		}
		// larikan ke halaman utama
		redirect('');
	}
	
	public function forgot_password()
	{
		global $message_mail;
		$image = base_url() . "public/images/logo-dika1.png";
		$email = $this->input->post('email');
		$data['title'] = 'Lupa Password';
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email', [
			'required' => 'Email wajib diisi!'
		]);
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('forgot_password',$data);

		} 
		else {
			//generate token
			$token = $this->rand_str();
			//reset token
			$this->{$this->model}->reset_token($token,$email);
			//get user detail
			$query = $this->{$this->model}->check_email($email);
			$sales_code = $query['data']->DSR_Code;
			$sales_name = $query['data']->Name;

			$log = array(
				'username' => $sales_code,
				'message' => 'Lupa password',
				'action'  => 'Request',
				'from_url' => 'http://' . $_SERVER['HTTP_HOST'], //.$_SERVER['PHP_SELF'],
				'from_ip' => $_SERVER["REMOTE_ADDR"]
			);
			$this->db->insert('user_logs', $log);

			$message_mail .= '<HTML>
								<BODY>	
									Hi <b>' . $sales_name . '</b>,
									<br/>
									<br/>
									<br/>										
									Silakan klik <a style=\'padding: 12px 22px;color: #33a6cc !important;text-transform: uppercase;background: #ffffff;padding: 20px;border: 4px solid #33a6cc !important;border-radius: 6px;display: inline-block;\' href=\''.site_url().'reset_password?token=' . $token .'\' >disini</a> untuk mereset password.
										
									<br/>											
									<br>
									<br/>
									<br/>
									<span style=\'color:#3276b1;font-family:Arial; font-size:11px; !important;\'>											  
										Hormat kami,
										<br/>
										PT. Danamas Insan Kreasi Andalan
									</span>
									<br/>
									<br/>
									<br/>
									<i>Noted : Email ini dikirim oleh sistem secara otomatis</i>
								</BODY>
							</HTML>
						';
			$this->load->library('email');
			$this->config->load('setting');
			$config['mailtype'] = "html";
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from('support@ptdika.com', 'PT Danamas Insan Kreasi Andalan');
			$this->email->to($email);
			$this->email->subject('Reset Password sales.ptdika.com');
			$this->email->message($message_mail);
			$this->email->send();
			$str = "Request berhasil dikirim ke email <b>$email</b>. Silakan cek email untuk proses selanjutnya.";
			$link = "forgot_password";
			$this->alert_success($str);
			$this->load->view('forgot_password',$data);
		}
	}
	
	function reset_password()
	{
		$token = $_GET['token'];
		$check_token = $this->{$this->model}->check_token($token);
		
		if($check_token['status'] == 0){
			$message = "Maaf, link sudah tidak berlaku.";
			$this->alert_danger($message);
			$this->load->view('reset_password');
		}
		else {
			$id = $check_token['data']->Employee_ID;
			$this->load->library('form_validation');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('password_conf', 'Password Confirmation', 'trim|required|matches[new_password]');
			$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
	
			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Reset Password';
				$this->load->view('reset_password',$data);
			} else {
				//update password
				$this->{$this->model}->update_password($id);
	
				$log = array(
					'username' => $check_token['data']->DSR_Code,
					'message' => 'Ganti Password Sukses',
					'action'  => 'Change Password',
					'from_url' => 'http://' . $_SERVER['HTTP_HOST'], //.$_SERVER['PHP_SELF'],
					'from_ip' => $_SERVER["REMOTE_ADDR"]
				);
				$this->db->insert('user_logs', $log);
				$message = "Password berhasil di ubah. Silahkan login menggunakan password baru.";
				$this->alert_success($message);
				$this->load->view('reset_password');
			}	
		}
	}
	
	//============================================ INTERNAL FUNCTION ============================================//
	
	// cek status
	function check_status()
	{
		$username = $this->input->post('username');
		if (strpos($username, 'K') !== false || strpos($username, 'F') !== false || strpos($username, 'P') !== false){
			$query = $this->{$this->model}->user_detail($username);
			if ($query['status'] == '1') {
				$status = $query['data']->Status;
				
				// Is not active
				if ($status != 'ACTIVE')
				{
					// Let's return false for the validation and set a custom message for this function
					$this->form_validation->set_message('check_status', 'Maaf, Sales Code anda tidak aktif.');
					return FALSE;
				}
				else
				{
					// Everything is good, don't return an error.
					return TRUE;
				}
			} 
			else {
				// Lost Connection to API
				return TRUE;
			}
		}
		else{
			// Login from table user
			return TRUE;
		}
	}
	
	// cek email
	function check_email($str)
	{
		$query = $this->{$this->model}->check_email($str);
		if ($query['status'] == 0) {
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message("check_email", "Maaf, Email <b>$email</b> belum terdaftar, silakan hubungi HRD untuk mendaftartkan email anda.");
			return FALSE;
		}
		else
		{
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
	
	private function alert_danger($str)
	{
		$this->session->set_flashdata('login_info', "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>$str</div>");
	}

	private function alert_success($str)
	{
		$this->session->set_flashdata('login_info', "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>$str</div>");
	}
	
	private function rand_str()
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		// Output: 54esmdr0qf
		return substr(str_shuffle($permitted_chars), 0, 32);
	}
}