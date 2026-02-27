<?php

	// function pre($exit = null){
	// 	$CI = &get_instance();
	// 	echo "<pre><hr>";
	// 	print_r($CI->db->last_query());
	// 	echo "<hr></pre>";
	// 	// exit();
	// }
	
		
	function cekvar( $var ){
		$CI = &get_instance();
		echo "<pre>";
			print_r( $var );
		echo "</pre>";
		exit();
		
	}
	function cekarray( $var ){
		$CI = &get_instance();
		echo "<pre>";
		print_r( $var->result() );
		echo "</pre>";

		exit();
	}
	function cekarray2( $var ){
		$CI = &get_instance();
		echo "<pre>";
		print_r( $var );
		echo "</pre>";

		exit();
	}
	function cekdb(){
		$CI = &get_instance();
		echo "<pre>";
		print_r($CI->db->last_query());
		echo "</pre>";
		exit();
	}

	if(!function_exists('check_ttd')) {
    function check_ttd() {
			$CI = get_instance();

			$position = $CI->session->userdata('position');
			$dsr_code = $CI->session->userdata('sl_code');

			// $query = $CI->db->order_by('adendum_id', 'DESC')->get_where('data_adendum_sales', array('sales_code' => $dsr_code, 'upload_id >=' => 2, 'is_checked' => 0));
			$query = $CI->db->select('t1.*')->from('data_adendum_sales AS t1')->join('data_adendum_upload AS t2', 't1.upload_id = t2.upload_id')->where('t1.sales_code', $dsr_code)->where('t2.is_active', '1')->where('t1.is_checked', 0)->order_by('t1.adendum_id', 'DESC')->get();

			if ($query->num_rows() != 0) {
				$data = [
					'status' => true,
					'activity' => 1
				];
			} else {
				$data = [
					'status' => false,
					'activity' => 2
				];
			}

			return $data;

			// if ($position == "DSR") {
			// 	$query = $CI->db->get_where('data_adendum_sales', array('sales_code' => $dsr_code))->row();
			// 	if (is_null($query)) {
			// 		$data = [
			// 			'status' => true,
			// 			'activity' => 1
			// 		];
			// 	}
			// 	if ($query->is_checked == 0) {
			// 		return true;
			// 	} else {
			// 		return false;
			// 	}
			// } else {
			// 	return false;
			// }
    }
	}