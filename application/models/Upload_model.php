<?php
class Upload_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		//$this->load->library('db_mysql');
		//$this->load->database();
	}

    function do_upload($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++){
            $data = array(
                'area'=>$dataarray[$i]['area'],
				'location'=>$dataarray[$i]['location'],
				'training_day'=>$dataarray[$i]['training_day'],
				'available_date'=>$dataarray[$i]['available_date'],
				'time'=>$dataarray[$i]['time'],
				'quota'=>$dataarray[$i]['quota'],
				'available_seat'=>$dataarray[$i]['available_seat'],				
				'expire'=>$dataarray[$i]['expire']
            );
			$this->db->trans_start();
			$this->db->replace('schedule_training', $data);
			$id = $this->db->insert_id();
			$this->db->trans_complete();
			
			//update slug
			$slug = array(
				'slug'	=>md5($id)
			);
			$this->db->where('id',$id);
			$this->db->update('schedule_training',$slug);
        }
    }

    function get_data()
    {
        $query = $this->db->get('data_customer');
        return $query->result();
    }
	
	function update ($data,$id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_name,$data);
	}

}
?>