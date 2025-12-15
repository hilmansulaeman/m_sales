<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$date1 = $this->session->userdata('start_date');
        $date2 = $this->session->userdata('end_date');
        $by    = $this->session->userdata('realname');
		$data['query'] = $this->db->query("select 
									SUM(IF(Category = 'HARI MAKAN',1,0)) AS notif_harimakan,
									SUM(IF(Category = 'LAPORAN',1,0)) AS notif_laporan,
									count(1) AS notif_all
									FROM data_announcement WHERE Created_Date >= '$date1' 
									AND Created_Date  <= '$date2' 
									AND Created_By = '$by'")->row();
		$data['media'] = $this->db->query("select Category, Announcement_Description
									FROM data_announcement WHERE Created_Date >= '$date1' 
									AND Created_Date  <= '$date2' 
									AND Created_By = '$by'
									ORDER BY Announcement_ID DESC");

		$this->load->view('template/notification_validation', $data);
	}

	public function validation()
	{
		$date1 = $this->session->userdata('start_date');
        $date2 = $this->session->userdata('end_date');
        $by    = $this->session->userdata('realname');
		$data['query'] = $this->db->query("select 
									SUM(IF(Category = 'HARI MAKAN',1,0)) AS notif_harimakan,
									SUM(IF(Category = 'LAPORAN',1,0)) AS notif_laporan,
									count(1) AS notif_all
									FROM data_announcement WHERE Created_Date >= '$date1' 
									AND Created_Date  <= '$date2' 
									AND Created_By = '$by'")->row();
		$data['media'] = $this->db->query("select Category, Announcement_Description
									FROM data_announcement WHERE Created_Date >= '$date1' 
									AND Created_Date  <= '$date2' 
									AND Created_By = '$by'
									ORDER BY Announcement_ID DESC");
		//load view
		echo json_encode($data);
	}


}

/* End of file Notification.php */
/* Location: ./application/controllers/Notification.php */
