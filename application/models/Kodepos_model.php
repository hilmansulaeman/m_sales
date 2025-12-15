<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Kodepos_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function datakodepos()
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_kota` limit 0, 20");
		return $query;
		$query->free_result();
	}
	
	public function record_count() {
        $query = $this->db->count_all("`internal`.`data_kota`");
		return $query;
		$query->free_result();
    }
	
	public function fetch_countries($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("`internal`.`data_kota`");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
}