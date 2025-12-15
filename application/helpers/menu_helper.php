<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('activate_directory')) {
    function activate_directory($folder) {
	    // Getting CI class instance.
        $CI = get_instance();
        // Getting router directory to active.
		$directory = $CI->router->fetch_directory();
        return ($directory == $folder) ? 'active' : '';
    }
}

if(!function_exists('activate_module')) {
    function activate_module($module) {
	    // Getting CI class instance.
        $CI = get_instance();
        // Getting router module to active.
		$module_ = $CI->router->fetch_module();
        return ($module_ == $module) ? 'active' : '';
    }
}

if(!function_exists('activate_menu')) {
    function activate_menu($controller) {
	    // Getting CI class instance.
        $CI = get_instance();
        // Getting router class to active.
        $class = $CI->router->fetch_class();
        return ($class == $controller) ? 'active' : '';
    }
}

if(!function_exists('activate_submenu')) {
    function activate_submenu($page1,$page2) {
	    // Getting CI class instance.
        $CI = get_instance();
        $menu = $CI->uri->segment(1);
        $submenu = $CI->uri->segment(2);
        return ($menu == $page1 && $submenu == $page2) ? 'active' : '';
    }
}

if(!function_exists('show_menu')) {
    function show_menu($rules) {
	    // Getting CI class instance.
        $CI = get_instance();
		//check available rule
		$show_menu = check_menu($rules);
        // Getting router class to active.
        return ($show_menu == '1') ? '' : 'none';
    }
	
	function check_menu($rules){
	    // Getting CI class instance.
        $CI = get_instance();
		$position_ = $CI->session->userdata('position');
		$getPosition = $CI->db->query("SELECT * FROM ref_position WHERE Position = '$position_'")->row();
		$position = $getPosition->Position_Group;
		$product  = $CI->session->userdata('product');
		$channel  = $CI->session->userdata('channel');
		if($position == 1){
			return '1';
		}
		else{
		    //check available rules
			$query = $CI->db->query("SELECT * FROM set_menu 
				WHERE Position = '$position' 
				AND(
				CASE WHEN (Product = 'ALL' AND Channel = 'ALL') THEN Menu LIKE '%$rules%'
					WHEN (Product = 'ALL' AND Channel != 'ALL') THEN Channel = '$channel' AND Menu LIKE '%$rules%'
					WHEN (Product != 'ALL' AND Channel = 'ALL') THEN Product = '$product' AND Menu LIKE '%$rules%'
					ELSE Product = '$product' AND Channel = '$channel' AND Menu LIKE '%$rules%'
				END)
			");
			if($query->num_rows() > 0){
				return '1';
			}
			else{
				return '0';
			}
		}
	}
}
?>