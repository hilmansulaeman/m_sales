<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller 
{
    private $limit = 20;
	
    function __construct()
    {
        parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('dashboard_model');
    }
	
	function index()
    {
        /*$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if($posisi == "DSR")
        {
            $var_code = "DSR_Code";
        }elseif ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
        }elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
        }elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
        }elseif ($posisi == "BSH" ) {
            $var_code = "BSH_Code";
        }
        $data['query'] = $this->dashboard_model->view($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['query_app'] = $this->dashboard_model->view_app($Sales_Code, $var_code, date('Y-m'));*/
		$position = $this->session->userdata('position');
		$product = $this->session->userdata('product');
		$channel = $this->session->userdata('channel');
		$channel_filter = array('DSR','SPV');
		if(in_array($position,$channel_filter)){
			$where = "channel = '$channel'";
			if($channel == 'All'){
				$where = array('product'=>$product);
			}
			else{
				$where = array('product'=>$product, 'channel'=>$channel);
			}
		}
		else{
			$where = "";
		}
		$data['get_poster'] = $this->dashboard_model->get_poster();

        //load view
		$this->template->set('title','Dashboard');
		$this->template->load('template','dashboard', $data);
    }

    function index2()
    {
    	$this->template->set('title', 'Dashboard2');
    	$this->template->load('template', 'dashboard2');
    }
	function get_veryfied()
	{
		$query = $this->Addendum_model->getDataVerified();
		$data = $query->row();

		echo json_encode($data);
	}
	function ubah_password()
	{
		//load view
		$this->template->set('title','Dashboard');
		$this->template->load('template','ubah_password');
	}
	
	function update_password()
	{
	    $password = $this->input->post('password');
		$password_conf = $this->input->post('retype_password');
		
		if(!empty($password) && !empty($password_conf)){
		
			if($password == $password_conf)
			{
				$update = $this->dashboard_model->UpdatePassword();
				$this->session->sess_destroy();
				?>
					<script>
						alert('Password berhasil diubah');
						window.location.href='<?php echo site_url(); ?>';
					</script>
				<?php
			}
			else
			{
				?>
				<script>
					alert('Gagal, Password Tidak Sama');
					window.location.href='<?php echo site_url(); ?>';
				</script>
				<?php
			}
		}
		else{
		    ?>
			<script>
				alert('Gagal, Password Tidak Boleh Kosong.');
				window.location.href='<?php echo site_url(); ?>';
			</script>
			<?php
		}
	}
	
	function update_password1($sales_code)
	{
		if($this->input->post('password') == $this->input->post('retype_password'))
		{
			$data_request = array(
				'Password'	=>md5($this->input->post('password')),
				'Password_Change'	=>1
			);
			$this->dashboard_model->UpdatePassword($data_request, $sales_code);
			$this->session->sess_destroy();
		?>
			<script>
				alert('Password berhasil diubah');
				window.location.href='<?php echo base_url(); ?>';
			</script>
		<?php
		}
		else
		{
			?>
			<script>
				alert('Gagal, Password Tidak Sama');
				window.location.href='<?php echo base_url(); ?>';
			</script>
			<?php
		}
	}
	
	function update_password2($sales_code)
	{
	    $this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]');
		$this->form_validation->set_rules('retype_password', 'Password Confirmation', 'required|matches[password]');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
		if ($this->form_validation->run() == FALSE)
		{
			$this->template->set('title','Ubah Password');
			$this->template->load('template','ubah_password');
		}
		else
		{
			$data_request = array(
				'Password'	=>md5($this->input->post('password')),
				'Password_Change'	=>1
			);
			$this->dashboard_model->UpdatePassword($data_request, $sales_code);
			$this->session->sess_destroy();
			?>
				<script>
					alert('Password berhasil diubah');
					window.location.href='<?php echo site_url(); ?>';
				</script>
			<?php
		}
	}
}

/* End of file Dasboard.php */
/* Location: ./application/controllers/Dashboard.php */								   