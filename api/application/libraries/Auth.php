<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth library
 */
 
class Auth
{
	var $CI = NULL;
	
	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();		
		$this->CI->load->helper('cookie');
		$this->CI->load->library('session');
		$this->CI->load->database();
	}
	
	// untuk validasi login
	function do_login($username,$password)
	{
		// cek di database, ada ga?
		$this->CI->db->from('users');
		$this->CI->db->where('Username',$username);
		$this->CI->db->where('Password=MD5("'.$password.'")','',false);
		$this->CI->db->where('Is_Active','1');
		$result = $this->CI->db->get();
		if($result->num_rows() == 0) 
		{
			// username dan password tsb tidak ada 
			return false;
		}
		else	
		{
			// ada, maka ambil informasi dari database
			$userdata = $result->row();
			$session_data = array(
				'id'	=> $userdata->User_ID,
				'username'	=> $userdata->Username,
				'realname'	=> $userdata->Name,
				'level'		=> $userdata->Profile_ID
			);
			// buat session
			$this->CI->session->set_userdata($session_data);
			return true;
		}
	}
	
	// untuk mengecek apakah user sudah login/belum
	function is_logged_in()
	{
		if($this->CI->session->userdata('username') == '')
		{
			return false;
		}
		return true;
	}
	
	// untuk validasi di setiap halaman yang mengharuskan authentikasi
	function restrict()
	{
		if($this->is_logged_in() == false)
		{
			redirect('sites/login');
		}
	}
	
	// untuk logout
	function do_logout()
	{
		$this->CI->session->sess_destroy();	
	}
}