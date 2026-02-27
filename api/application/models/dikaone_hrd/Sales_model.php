<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function updateDataRequestUser($request_user_id, $data_update_request)
	{
		$this->db->where('Request_User_ID', $request_user_id);
        $this->db->update('data_request_user', $data_update_request);

		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insertDataProcessLog($dataLog)
	{
		$this->db->insert('data_process_log', $dataLog);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function getDataRequestUser($request_user_id)
	{
		$query = $this->db->get_where('data_request_user', array('Request_User_ID' => $request_user_id));
		
		return $query;
		$query->free_result();
	}
}