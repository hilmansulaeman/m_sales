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