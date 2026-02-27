<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Decision_merchant extends REST_Controller {

    private $model = 'Decision_merchant_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		$this->{$this->model}->config('`internal`.`edc_result`','id');
		error_reporting(0);
    }

	// get datatable query
	function getDataTable_post()
	{
		$where		= $this->input->post('where');
		$groups		= $this->input->post('groups');
		$groupDate	= $this->input->post('groupDate');

		$getDataId = $this->Decision_merchant_model->get_dataTable($where, $groups, $groupDate);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId->result()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// count datatable query
	function countDataTable_post()
	{
		$where		= $this->input->post('where');
		$groups		= $this->input->post('groups');
		$groupDate	= $this->input->post('groupDate');

		$getDataId = $this->Decision_merchant_model->count_dataTable($where, $groups, $groupDate);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 0
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//
	function breakdownMerchant_get()
	{
		$var			= $this->get('var');
		$sales_code		= $this->get('sales_code');
		$groupDate		= $this->get('groupDate');
		$upVar			= $this->get('upVar');
		$upSales_code	= $this->get('upSales_code');

		// $getDataId = $this->Decision_merchant_model->get_breakdown_cc($sales_code,$groupDate,$upVar,$upSales_code);
		$getEdcResult = $this->Decision_merchant_model->get_summary($var,$sales_code,$groupDate,'EDC',$upVar,$upSales_code);
		$getQrisResult = $this->Decision_merchant_model->get_summary($var,$sales_code,$groupDate,'QRIS',$upVar,$upSales_code);
		
		$actual_new = 0 + $getEdcResult->ntb + $getQrisResult->ntb;
		$actual_existing = 0 + $getEdcResult->exis + $getQrisResult->exis;
		$actual_rejected = 0 + $getEdcResult->rejected + $getQrisResult->rejected;
		$actual_kredit = 0 + $getEdcResult->kredit;
		$actual_nonkredit = 0 + $getEdcResult->non_kredit;

		$point_kredit = $this->Decision_merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
		$point_nonkredit = $this->Decision_merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
		$point_qris_new = $this->Decision_merchant_model->calculate_point('QRIS', 'NEW', 0+$getQrisResult->ntb);
		$totalPointsNew = $point_kredit + $point_nonkredit + $point_qris_new;
		
		$point_edc_exis = $this->Decision_merchant_model->calculate_point('EDC', 'EXISTING', 0+$getEdcResult->exis);
		$point_qris_exis = $this->Decision_merchant_model->calculate_point('QRIS', 'EXISTING', 0+$getQrisResult->exis);
		$totalPointsExis = $point_edc_exis + $point_qris_exis;

		$getDataId = array(
			'actual_new' => $actual_new,
			'actual_new_point' => $totalPointsNew,
			'actual_existing' => $actual_existing,
			'actual_existing_point' => $totalPointsExis,
			'actual_reject' => $actual_rejected
		);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	function breakdownMerchantDetail_get()
	{
		$var			= $this->get('var');
		$sales_code		= $this->get('sales_code');
		$groupDate		= $this->get('groupDate');
		$upVar			= $this->get('upVar');
		$upSales_code	= $this->get('upSales_code');

		$getEdcResult = $this->Decision_merchant_model->get_summary_detail($var,$sales_code,$groupDate,'EDC',$upVar,$upSales_code);
		$getEdcResult2 = $this->Decision_merchant_model->get_summary_detail($var,$sales_code,$groupDate,'EDC_QRIS',$upVar,$upSales_code);
		$getQrisResult = $this->Decision_merchant_model->get_summary_detail($var,$sales_code,$groupDate,'QRIS',$upVar,$upSales_code);
		
		// EDC
		$actual_kredit 		= 0 + $getEdcResult->kredit;
		$actual_nonkredit 	= 0 + $getEdcResult->non_kredit;
		$actual_tc 			= 0 + $getEdcResult->tc;
		$actual_terminal 	= 0 + $getEdcResult->terminal;
		$actual_uf 			= 0 + $getEdcResult->uf;
		$actual_ra 			= 0 + $getEdcResult->ra;
		$totalPointKredit = $this->Decision_merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
		$totalPointNonKredit = $this->Decision_merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
		$totalPointsExisEDC = $this->Decision_merchant_model->calculate_point('EDC', 'EXISTING', 0+$getEdcResult->exis);
		
		// EDC + QRIS
		$actual_kredit2 	= 0 + $getEdcResult2->kredit;
		$actual_nonkredit2 	= 0 + $getEdcResult2->non_kredit;
		$actual_tc2 		= 0 + $getEdcResult2->tc;
		$actual_terminal2 	= 0 + $getEdcResult2->terminal;
		$actual_uf2 		= 0 + $getEdcResult2->uf;
		$actual_ra2 		= 0 + $getEdcResult2->ra;
		$totalPointKredit2 = $this->Decision_merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit2);
		$totalPointNonKredit2 = $this->Decision_merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit2);
		$totalPointsExisEDC2 = $this->Decision_merchant_model->calculate_point('EDC', 'EXISTING', 0+$getEdcResult2->exis);
		
		//QRIS
		$totalPointsNewQRIS = $this->Decision_merchant_model->calculate_point('QRIS', 'NEW', 0+$getQrisResult->ntb);
		$totalPointsExisQRIS = $this->Decision_merchant_model->calculate_point('QRIS', 'EXISTING', 0+$getQrisResult->exis);
		$dataQrisNew = $this->Decision_merchant_model->get_summary_qris($var,$sales_code,$groupDate,'New',$upVar,$upSales_code);
		$actual_qrd_new = 0 + $dataQrisNew->qrd;
		$actual_qsd_new = 0 + $dataQrisNew->qsd;
		$dataQrisExis = $this->Decision_merchant_model->get_summary_qris($var,$sales_code,$groupDate,'Exis',$upVar,$upSales_code);
		$actual_qrd_exis = 0 + $dataQrisExis->qrd;
		$actual_qsd_exis = 0 + $dataQrisExis->qsd;

		$getDataId = [
			'EDC' => [
				'kredit' => $actual_kredit,
				'point_kredit' => $totalPointKredit,
				'non_kredit' => $actual_nonkredit,
				'point_non_kredit' => $totalPointNonKredit,
				'tambahan_cabang' => $actual_tc,
				'tambahan_terminal' => $actual_terminal,
				'ubah_fasilitas' => $actual_uf,
				're_agreement' => $actual_ra,
				'point_exis_edc' => $totalPointsExisEDC,
			],
			'QRIS' => [
				'qrd_new' => $actual_qrd_new,
				'qsd_new' => $actual_qsd_new,
				'point_new' => $totalPointsNewQRIS,
				'qrd_exis' => $actual_qrd_exis,
				'qsd_exis' => $actual_qsd_exis,
				'point_exis' => $totalPointsExisQRIS,
			],
			'EDC_QRIS' => [
				'kredit' => $actual_kredit2,
				'point_kredit' => $totalPointKredit2,
				'non_kredit' => $actual_nonkredit2,
				'point_non_kredit' => $totalPointNonKredit2,
				'tambahan_cabang' => $actual_tc2,
				'tambahan_terminal' => $actual_terminal2,
				'ubah_fasilitas' => $actual_uf2,
				're_agreement' => $actual_ra2,
				'point_exis_edc_qris' => $totalPointsExisEDC2,
			],
		];

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}