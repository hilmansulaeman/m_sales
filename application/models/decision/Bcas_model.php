<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Bcas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_last_period()
    {
        $query = $this->db->select('Group_Date')
            ->from('data_upload_oa_pemol_bcas')
            ->order_by('Group_Date', 'DESC')
            ->limit(1)
            ->get();
        return $query;
        $query->free_result();
    }

    function get_bcas_dsr($sales, $group_date)
    {
        $query = $this->db->select("
			SUM(IF(`Status` = 'OA', 1, 0)) AS oa,
			SUM(IF(`Status` = 'NE', 1, 0)) AS ne,")

            ->from('data_upload_oa_pemol_bcas_detail')
            ->where('Sales_Code', $sales)
            ->where('Group_Date', $group_date)
            ->get()
            ->row();

        return $query;
        $query->free_result();
    }

    private function _get_dataTable_query($where = null, $groups = null, $groupDate = null)
    {
        $column_order = array(null, 'Sales_Code', 'Sales_Name', 'SPV_Name', 'ASM_Name', 'RSM_Name', 'Branch', 'Position');
        $column_search = array('Sales_Code', 'Sales_Name', 'SPV_Name', 'ASM_Name', 'RSM_Name', 'Branch', 'Position');

        $this->db->select('*');
        $this->db->from('data_upload_oa_pemol_bcas_detail');

        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($groupDate)) {
            $this->db->where("Group_Date", $groupDate);
        }

        // Grouping data
        if (!empty($groups)) {
            $this->db->group_by($groups);
        }

        if (!empty($_POST['search']['value'])) {
            $searchValue = $_POST['search']['value'];
            $this->db->group_start();
            foreach ($column_search as $item) {
                $this->db->or_like($item, $searchValue);
            }
            $this->db->group_end();
        }

        // Urutan default
        $this->db->order_by('Position', 'DESC');

        // Urutan berdasarkan request datatable
        if (isset($_POST['order'])) {
            $orderColumn = $_POST['order'][0]['column'];
            $orderDir = $_POST['order'][0]['dir'];

            // pastikan index kolom valid
            if (isset($column_order[$orderColumn]) && !empty($column_order[$orderColumn])) {
                $this->db->order_by($column_order[$orderColumn], $orderDir);
            }
        }
    }

    public function get_datatables($where = null, $groups = null, $groupDate = null)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);

        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query; // Tidak perlu $query->free_result() di sini
    }

    public function count_filtered($where = null, $groups = null, $groupDate = null)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);
        $query = $this->db->get();
        return $query->num_rows();
    }


    function get_bcas($var, $sales, $group_date)
    {
        $nik        = $this->session->userdata('sl_code');
        $position   = $this->session->userdata('position');
        $upPosition = $position . '_Code';

        $this->db->select("
        SUM(CASE WHEN `Status` = 'OA' THEN 1 ELSE 0 END) AS oa,
        SUM(CASE WHEN `Status` = 'NE' THEN 1 ELSE 0 END) AS ne,
        COUNT(*) AS total
    ");
        $this->db->from('data_upload_oa_pemol_bcas_detail');
        $this->db->where($var, $sales);
        $this->db->where('Group_Date', $group_date);
        $this->db->where($upPosition, $nik);

        $query = $this->db->get();
        return $query->row();
    }



    function get_bcas_detail($var, $sales, $var2, $upliner, $group_date)
    {
        //

        $this->db->select("
    SUM(CASE WHEN `Status` = 'OA' THEN 1 ELSE 0 END) AS oa,
        SUM(CASE WHEN `Status` = 'NE' THEN 1 ELSE 0 END) AS ne,
    ", false);

        $this->db->from('data_upload_oa_pemol_bcas_detail');
        $this->db->where($var, $sales);
        $this->db->where('Group_Date', $group_date);
        $this->db->where($var2, $upliner);

        $query = $this->db->get();
        return $query->row();
    }


    function get_bcas_export($tgl)
    {
        $nik         = $this->session->userdata('sl_code');
        $position     = $this->session->userdata('position');
        if ($position == 'BSH') {
            $where = "BSH_Code = '$nik'";
        } else if ($position == 'RSM') {
            $where = "RSM_Code = '$nik'";
        } else if ($position == 'ASM') {
            $where = "ASM_Code = '$nik'";
        } else if ($position == 'SPV') {
            $where = "SPV_Code = '$nik' OR ASM_Code = '$nik'";
        } else {
            $where = "Sales_Code = '$nik'";
        }
        $this->db->select('*');
        $this->db->from('data_upload_oa_pemol_bcas_detail');
        $this->db->where('Group_Date', $tgl);
        $this->db->where($where);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
