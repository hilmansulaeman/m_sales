<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Limit_event_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // get Branch
    function getSales()
    {
        $this->db->select('Name,DSR_Code,Product');
        $this->db->from('internal.data_sales');
        $this->db->where('Status', 'ACTIVE');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
