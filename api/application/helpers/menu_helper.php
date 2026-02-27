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

if(!function_exists('activate_menu')) {
    function activate_menu($controller) {
	    // Getting CI class instance.
        $CI = get_instance();
        // Getting router class to active.
        $class = $CI->router->fetch_class();
        return ($class == $controller) ? 'active' : '';
    }
}

if(!function_exists('show_menu')) {
    function show_menu($profile,$rules) {
	    // Getting CI class instance.
        $CI = get_instance();
		//check available rule
		$show_menu = check_menu($profile,$rules);
        // Getting router class to active.
        return ($show_menu == '1') ? 'block' : 'none';
    }
	
	function check_menu($profile,$rules){
	    // Getting CI class instance.
        $CI = get_instance();
		if($profile == 1){
		    return '1';
		}
		else{
		    //check available rule
			$CI->db->select('*');
			$CI->db->from('set_user_rules');
			$CI->db->where('Profile_ID',$profile);
			$CI->db->where('Rules',$rules);
			$query = $CI->db->get();
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