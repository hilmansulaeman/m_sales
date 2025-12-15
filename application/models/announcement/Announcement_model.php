<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Announcement_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // ============== QUERY FOR DATA ANNOUNCEMENT. ============== //

    private function _get_datatables_query($date1, $date2, $by)
    {
        $column_order = array(null, 'Category'); //field yang ada di table recruitment
        $column_search = array('Category', 'Is_Publish', 'Announcement_Description'); //field yang diizin untuk pencarian 
        $order = array('Announcement_ID' => 'DESC'); // default order
        $this->db->select('*');
        $this->db->from('`data_announcement`');
        $this->db->where('Created_Date >=', $date1);
        $this->db->where('Created_Date <=', $date2);
        $this->db->where('Created_By', $by);
        // $this->db->group_by('Announcement_Description');
        // $this->db->group_by('Is_Publish');

        $i = 0;
        foreach ($column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if ($i === 0) { // looping awal
                    //$this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) {
                    //$this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
        //$this->db->group_by($this->primary_key);
    }

    function get_datatables($date1, $date2, $by)
    {
        $this->_get_datatables_query($date1, $date2, $by);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    function count_filtered($date1, $date2, $by)
    {
        $this->_get_datatables_query($date1, $date2, $by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {
        $this->db->select('count(1) as total');
        $this->db->from('`data_announcement`');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    function insert($data_insert)
    {
        $this->db->insert('`data_announcement`', $data_insert);
    }

    function edit($id)
    {
        return $this->db->query("SELECT * FROM `data_announcement` WHERE Announcement_ID = '$id'")->row();
    }




    function get_employee($asm_code)
    {
        // $this->db->select('a.*,b.*');
        // $this->db->from('internal.data_sales_structure b');
        // $this->db->join('`internal.data_employee` a', 'b.DSR_Code = a.NIK');
        // $this->db->where('b.Status', 'ACTIVE');
        // $this->db->where('b.ASM_Code', $asm_code);
        // $this->db->order_by('b.Name', 'ASC');

        $this->db->select('*');
        $this->db->from('internal.data_sales_structure ');
        $this->db->where('Status', 'ACTIVE');
        $this->db->where('ASM_Code', $asm_code);
        $this->db->order_by('Name', 'ASC');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }


    function getdataemployeeid($id)
    {
        $this->db->select('*');
        $this->db->from('`internal`.`data_employee`');
        $this->db->where('NIK', $id);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
    function getdatacategory($Category)
    {
        $this->db->select('*');
        $this->db->from('`ref_announcement_category`');
        $this->db->like('Category', $Category);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
