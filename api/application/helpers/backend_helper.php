<?php
/*
 * @function untuk site url cms
 */
if(!function_exists("en_url"))
{
    function en_url($url=FALSE)
    {
        $en_url = site_url() . link_index() .  get_item_config('path_en');
        if($url and $url != '/')
            $en_url .= $url;

        return $en_url;
    }
}

/*
 * @function untuk base asset url cms
 */
if(!function_exists("en_asset_url"))
{
    function en_asset_url()
    {
        return asset_url() .  get_item_config('path_en');
    }
}

/*
 * @funtion set session
 * Untuk memasukan session, setiap key session ditambahkan "prefix"
 * Ini khusus untuk backend
 */
if(!function_exists("set_session"))
{
    function set_session($data = null)
    {
        $CI =& get_instance();
        $data_array = array();
        if(!empty ($data)){
           foreach ($data as $key=>$val){
               $data_array[get_item_config('en_cookie_prefix').$key] = $val;
           }
        }
        $CI->session->set_userdata($data_array);
    }
}

if(!function_exists("unset_session"))
{
    function unset_session($data = null)
    {
        $CI =& get_instance();
        $data_array = array();
        if(!empty ($data)){
           foreach ($data as $key=>$val){
               $data_array[get_item_config('en_cookie_prefix').$key] = $val;
           }
        }
        // pre($data_array);
        $CI->session->set_userdata($data_array);        
    }
}

/*
 * @function get session
 */
if(!function_exists("get_session"))
{
    function get_session($key = 'en_')
    {
        $CI =& get_instance();
        $val = $CI->session->userdata(get_item_config('en_cookie_prefix').$key);

        return $v = isset($val) ? $val : false;
    }
}

if(!function_exists("is_logged_in_en"))
{
    function is_logged_in_en()
    {
        $is_logged_in = get_session('is_logged_in');

        return $v = ($is_logged_in) ? $is_logged_in : false;
    }
}

if(!function_exists("user_id_en"))
{
    function user_id_en()
    {
        $user_id = get_session('user_id');

        return $v = ($user_id) ? $user_id : 0;
    }
}

if(!function_exists("username_en"))
{
    function username_en()
    {
        $username = get_session('username');

        return $v = ($username) ? $username : "";
    }
}

if(!function_exists("lastlogin_en"))
{
    function lastlogin_en()
    {
        $lastlogin = get_session('lastlogin');

        return $v = ($lastlogin) ? format_date($lastlogin,'F d, Y H:i:s') : "";
    }
}

if(!function_exists("full_name_en"))
{
    function full_name_en()
    {
        $full = get_session('full_name');

        return $v = ($full) ? $full : "";
    }
}

if(!function_exists('role_en'))
{
    function role_en()
    {
        $role = get_session('role');

        return $v = ($role) ? $role : 0;
    }
}

/*
 * @Config Setting
 */
if(!function_exists("xml"))
{
    function xml($id = '')
    {
        $CI =& get_instance();

        return $CI->config->item($id);
    }
}

if(!function_exists("get_item_config"))
{
    function get_item_config($id = '')
    {
        $CI =& get_instance();

        return $CI->config->item($id);
    }
}

/*
 * @untuk form login (variable post)
 */
if(!function_exists("form_username_en"))
{
    function form_username_en()
    {
        $CI =& get_instance();

        $ip = $CI->input->ip_address();
        $username = get_session('form_username');

        if(!$username)
        {
            $username = md5($ip . time() . '_username');
            $newdata = array('form_username'  => $username);
            set_session($newdata);
        }

        return $username;
    }
}

if(!function_exists("form_password_en"))
{
    function form_password_en()
    {
        $CI =& get_instance();

        $ip = $CI->input->ip_address();
        $password = get_session('form_password');

        if(!$password)
        {
            $password = md5($ip . time() . '_password');
            $newdata = array('form_password'  => $password);
            set_session($newdata);
        }

        return $password;
    }
}

if(!function_exists('check_page_access'))
{
    function check_page_access($page_access=NULL,$page=FALSE,$role=0)
    {
        $CI =& get_instance();
        $CI->load->model(get_item_config('path_en') .'role');
        
        $access = FALSE;
        if(!empty ($page_access) && $page)
        {
            $rows = explode(',', $page_access);
            foreach ($rows as $value)
            {
                if($value == $page){
                    $access = TRUE;
                    break;
                }
            }
        }
        //Jika administrator maka semua menu bisa diaksess
        return ($role == $CI->role->administrator) ? TRUE : $access;
    }
}

if(!function_exists('contains_id'))
{
    function contains_id($ids=NULL,$id=FALSE)
    {
        $ststus = FALSE;
        if(!empty ($ids) && $id)
        {
            $rows = explode(',', $ids);
            foreach ($rows as $value)
            {
                if($value == $id){
                    $ststus = TRUE;
                }
            }
        }

        return $ststus;
    }
}

if(!function_exists('contactus'))
{
    function contactus()
    {
        $CI =& get_instance();
        $CI->load->model(get_item_config('path_en') .'contact_mod');
        
        return $CI->contact_mod->get_rows(FALSE,array('contact_us.is_read=0' => NULL));
    }
}

if(!function_exists('pre'))
{
    function pre($data, $next = 0){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if(!$next){ exit; }
    }
}

if ( ! function_exists('xss_clean'))
{
    function xss_clean($post){
        $ci =& get_instance();
        return htmlspecialchars($ci->security->xss_clean($post));
    }
}

/*
 * @Date format UTC
 */
if(!function_exists("date_now"))
{
    function date_now($time=false)
    {
        date_default_timezone_set('UTC');
        if($time){
            $date = date('Y-m-d H:i:s');
            return date("Y-m-d H:i:s", strtotime('+7 hours', strtotime($date)));
        }else {
           return date('Y-m-d');
        }
    }
}

/*
 * @Menambahkan index.php jika htaccess tidak berjalan
 */
if(!function_exists("link_index"))
{
    function link_index()
    {
        if(get_item_config('allow_htaccess'))
            return '';
        else   
            return 'index.php/';
    }
}

/*
 * @function untuk base asset url 
 */
if(!function_exists("asset_url"))
{
    function asset_url()
    {
        return base_url() . 'assets/';
    }
}

/*
 * @Format date
 */
if(!function_exists("format_date"))
{
    function format_date($date,$format = 'd F Y')
    {
        $return = '';
        if(!empty($date)){
            $date = new DateTime($date);
            $return .=$date->format($format);
        }
        return $return;
    }
}


/*
 * Pagination function
 * $base_url = url utama yang nantinya akan ditambahkan $_GET per_page
 * $total_rows = jumlah semua rows
 * $per_page = jumlah row yang akan ditampilkan
 * $cur_page = page yang sedang aktif
 */
if(!function_exists("pagination"))
{
    function pagination($base_url,$total_rows,$per_page,$cur_page)
    {
        $CI =& get_instance();
        //Load library
        $CI->load->library('pagination');
        //set config
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['cur_page'] = $cur_page;
        $config['page_query_string'] = TRUE;
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="prev">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="next">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $CI->pagination->initialize($config);

        return $CI->pagination->create_links();
    }
}