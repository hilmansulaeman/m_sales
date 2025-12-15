<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->from('internal_sms.ref_category_structure');
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
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
        $this->db->from('internal_sms.ref_category_structure');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    function edit($category_id)
    {
        $this->db->select('*');
        $this->db->from('internal_sms.ref_category_structure');
        $this->db->where('Category_ID', $category_id);

        return $this->db->get();
    }

    function get_data_category_form()
    {
        $this->db->select('*');
        $this->db->from('internal_sms.ref_category_form');
        return $this->db->get();
    }
}
