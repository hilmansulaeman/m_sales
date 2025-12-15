<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');
class Duplicate_cc_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function data_duplicate_cc($sales_code, $filter)
	{
		$query = $this->db->query("
								  Select *  from `internal`.`applications`  where sales_code='$sales_code' and status in('DUPLICATE_DIKA','PROJECT','SEND_BCA') $filter
								  ");								 
		return $query;
		$query->free_result();
		
	}
	function combo_periode($filter)
	{
		$query = $this->db->query("select  distinct(periode) as periode  from tbl_estimasi where sales_code='$filter'");
	 
		return $query;			
		$query->free_result(); 	
		
	}
	 function do_upload($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++){
            $data = array(
                'sales_code'=>$dataarray[$i]['sales_code'],
				'periode'=>$dataarray[$i]['periode'],
				'allowance'=>$dataarray[$i]['allowance'],
				'incentive'=>$dataarray[$i]['incentive'],
				'bonus'=>$dataarray[$i]['bonus']
            );
			$this->db->trans_start();
			$this->db->replace('tbl_estimasi', $data);
			$id = $this->db->insert_id();
			$this->db->trans_complete();			
        }
    }
	public function do_insert($param){
		$this->db->insert('tbl_estimasi',$param);
		return true;
	}
    function get_data()
    {
        $query = $this->db->get('data_customer');
        return $query->result();
    }
	
	function cekDataDuplicate($nama, $dob)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`apps_declined` WHERE Customer_Name ='$nama' AND Date_Of_Birth='$dob' AND Expire='0'");
		return $query;
		$query->free_result();
	}
	
	function cekDataDuplicateApps($nama, $dob)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`applications` WHERE Customer_Name='$nama' AND Date_Of_Birth='$dob' AND Expire='0'");
		return $query;
		$query->free_result();
	}
	
	function getDuplicateWithKtp($ktp)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`applications` WHERE ID_Number='$ktp' AND Expire='0'");
		return $query;
		$query->free_result();
	}
}
?>