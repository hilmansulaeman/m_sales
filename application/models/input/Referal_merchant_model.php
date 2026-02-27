<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Referal_merchant_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function getDataTable()
	{
		$getApi = $this->db->get_where('key_api', ['Description' => 'Rest API'])->row();
		$postData = $this->input->post(); 
		$url = $getApi->url . 'merchant/referal_merchant/getDataTable';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $getApi->Username . ':' . $getApi->Password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: " . $getApi->api_key,
			"Content-Type: application/json"
		));
		curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //local
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //local
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

		$result = curl_exec($ch);
		curl_close($ch);

		$dataDecode = json_decode($result);

		if (!$dataDecode) {
			// Kirim format kosong agar DataTables tidak stuck "Processing"
			return (object)[
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => []
			];
		}

		
		return $dataDecode; 
	}
	

	public function insert($data)
	{
		$getApi = $this->db
			->get_where('key_api', ['Description' => 'Rest API'])
			->row();

		if (!$getApi) {
			return [
				"status" => false,
				"message" => "API config not found"
			];
		}

		$url = $getApi->url . 'merchant/referal_merchant/add';

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $getApi->Username . ':' . $getApi->Password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: " . $getApi->api_key,
			"Content-Type: application/json"
		));

		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // local
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // local

		// WAJIB kirim POST
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return [
				"status" => false,
				"message" => curl_error($ch)
			];
		}

		curl_close($ch);

		$response = json_decode($result, true);

		if (!$response) {
			return [
				"status" => false,
				"message" => "Invalid JSON: " . $result
			];
		}

		return $response;
	}

	function get_by_id($id)
	{
		$getApi = $this->db
			->get_where('key_api', ['Description' => 'Rest API'])
			->row();

		if (!$getApi) {
			return [
				"status" => false,
				"message" => "API config not found"
			];
		}

		$url = $getApi->url . 'merchant/referal_merchant/by_id?id=' . $id;
		// var_dump($url); die;
		

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $getApi->Username . ':' . $getApi->Password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: " . $getApi->api_key,
			"Content-Type: application/json"
		));

		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //local
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //local

		// PERBAIKAN: Gunakan GET dan hapus POSTFIELDS
		curl_setopt($ch, CURLOPT_HTTPGET, true);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return [
				"status" => false,
				"message" => curl_error($ch)
			];
		}

		curl_close($ch);

		$response = json_decode($result, true);

		if (!$response) {
			return [
				"status" => false,
				"message" => "Invalid JSON: " . $result
			];
		}

		return $response;
	}

	function update($id, $data) {
	$getApi = $this->db
			->get_where('key_api', ['Description' => 'Rest API'])
			->row();

		if (!$getApi) {
			return [
				"status" => false,
				"message" => "API config not found"
			];
		}

		$data['id'] = $id;

		$url = $getApi->url . 'merchant/referal_merchant/update';
		

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $getApi->Username . ":" . $getApi->Password);
		
		// Gunakan application/json agar konsisten dengan fungsi add
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: " . $getApi->api_key,
			"Content-Type: application/json"
		));

		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // local
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // local
		
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Kirim sebagai JSON

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
			curl_close($ch);
			return [
				"status" => false,
				"message" => $error_msg
			];
		}

		curl_close($ch);
		$response = json_decode($result, true);
		if (!$response) {
			return [
				"status" => false,
				"message" => "Invalid Response from API: " . $result
			];
		}

		return $response;
	}

}