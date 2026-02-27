<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	private function _get_datatables_query()
    {
        $sales_code = $this->session->userdata('sales_code');
        $position = $this->session->userdata('position');
        $status_aplikasi = $this->session->userdata('status_aplikasi');
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

	    $column_order = array(null,'a.RegnoId','a.Sales_Code','a.Sales_Name'); //field yang ada di table recruitment
		$column_search = array('a.Account_Number','a.Mobile_Phone_Number'); //field yang diizin untuk pencarian 
		$order = array('a.RegnoId' => 'DESC'); // default order
		$this->db->select('a.*');
		$this->db->from('`internal`.`edc_merchant` a');
        $this->db->join('`internal`.`data_sales_structure` b', 'b.DSR_Code = a.Sales_Code','inner');
        if($status_aplikasi == 'submit_to_dika') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '105' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'pending_fu') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status IN('PENDING_FU','SUBMIT_TO_DIKA') AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'return_to_sales') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code IN('103','104') AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'submit_to_bca') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'SUBMIT_TO_BCA' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'return_from_bca') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RETURN_FROM_BCA' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'resubmit_to_bca') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_BCA' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'resubmit_to_dika') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_DIKA' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'cancel') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'CANCEL' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
        elseif($status_aplikasi == 'reject') {
            $where = "a.Product_Type IN('EDC','EDC_QRIS') AND $where1 AND a.Hit_Code = '106' AND a.Status = 'REJECT' AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'";
        }
		
        $this->db->where($where);
 
        $i = 0;
        foreach ($column_search as $item) // looping awals
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if($i===0){ // looping awal
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order))
        {
            $this->db->order_by(key($order), $order[key($order)]);
        }
		//$this->db->group_by($this->primary_key);
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	function count_all()
    {
	    $this->db->select('count(1) as total');
	    $this->db->from('`internal`.`edc_merchant`');
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }

	function total($sales_code, $position, $tgl1, $tgl2)
	{
		if($position == 'DSR') {
			$where = "b.DSR_Code = '$sales_code'";
		}
		else if($position == 'SPV') {
			$where = "b.SPV_Code = '$sales_code'";
		}
		else if($position == 'ASM') {
			$where = "b.ASM_Code = '$sales_code'";
		}
		else if($position == 'RSM') {
			$where = "b.RSM_Code = '$sales_code'";
		}
		else {
			$where = "b.BSH_Code = '$sales_code'";
		}
		$query = $this->db->query("SELECT COUNT(1) AS qris FROM `internal`.`edc_merchant` a
								INNER JOIN `internal`.`data_sales_structure` b ON b.DSR_Code = a.Sales_Code
								WHERE $where AND Product_Type IN('EDC','EDC_QRIS') AND a.Status NOT IN('return_to_dika','CANCEL') 
								AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}
    
    function m_breakdown_edc($sales_code, $position, $tgl1, $tgl2)
    {
		if($position == 'DSR') {
			$where = "b.DSR_Code = '$sales_code'";
		}
		else if($position == 'SPV') {
			$where = "b.SPV_Code = '$sales_code'";
		}
		else if($position == 'ASM') {
			$where = "b.ASM_Code = '$sales_code'";
		}
		else if($position == 'RSM') {
			$where = "b.RSM_Code = '$sales_code'";
		}
		else {
			$where = "b.BSH_Code = '$sales_code'";
		}
		//$sales_code = $this->CI->session->userdata('sales_code'); 
		$query = $this->db->query("SELECT
			SUM(IF(a.Hit_Code IN('102','107'),1,0)) AS new_merchant,
			SUM(IF(a.Hit_Code IN('103','104'),1,0)) AS returned,
			SUM(IF(a.Hit_Code = '105',1,0)) AS submit_to_dika,
			SUM(IF(a.Hit_Code = '106' AND a.Status IN('PENDING_FU','SUBMIT_TO_DIKA'),1,0)) AS pending_fu,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'SUBMIT_TO_BCA',1,0)) AS submit_to_bca,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'RETURN_FROM_BCA',1,0)) AS return_from_bca,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_DIKA',1,0)) AS resubmit_to_dika,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'RESUBMIT_TO_BCA',1,0)) AS resubmit_to_bca,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'CANCEL',1,0)) AS cancel,
			SUM(IF(a.Hit_Code = '106' AND a.Status = 'REJECT',1,0)) AS reject
			FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_structure` b
            ON b.DSR_Code = a.Sales_Code
			WHERE $where AND a.Product_Type IN('EDC','EDC_QRIS') AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'
			");
		
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
    }

}