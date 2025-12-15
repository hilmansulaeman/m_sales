<?php
	// Data Input
	$dataIS = $getDataIS; //IS = Input => Input System

	// Data Processing
	$dataPR = $getDataPR;
	$dataPI = $getDataPI;
	$dataPRTS = $getDataPRTS;
	$dataPS = $getDataPS;
	// $dataPTotal = $getDataPTotal;

	$date1 = strtotime($date_from);
	$date2 = strtotime($date_to);

    $periode = date('d F Y', $date1)."-".date('d F Y', $date2);
?>
<div class="alert alert-info alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?></h4>
</div>

<a href="<?= site_url('incoming/merchant/index/') ?>" class="btn btn-danger">Back <i class="fa fa-arrow-left"></i></a>
<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Data Input</h4>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($dataIS->total); ?></span>
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">

				<!-- EDC -->
					<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc');">EDC <span class="pull-right badge bg-blue"><?= $edc = $dataIS->edc == null ? 0 : $dataIS->edc ?></span></a></li>
				<!-- END EDC -->

				<!-- QRIS -->
					<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'qris');">QRIS <span class="pull-right badge bg-blue"><?= $qris = $dataIS->qris == null ? 0 : $dataIS->qris ?></span></a></li>
				<!-- END QRIS -->

				<!-- EDC QRIS -->
					<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc_qris');">EDC+QRIS <span class="pull-right badge bg-blue"><?= $edc_qris = $dataIS->edc_qris == null ? 0 : $dataIS->edc_qris ?></span></a></li>
				<!-- END EDC QRIS -->
			</ul>
		</div>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>App Processing</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5>Received Total</h5>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($getTotalsReceived->total); ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5>Inprocess Total</h5>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($getTotalsInprocess->total); ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5>RTS Total</h5>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($getTotalsRTS->total); ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5>Send Total</h5>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($getTotalsSend->total); ?></span>
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">

				<!-- EDC -->
					<h3>EDC</h3>
					<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'edc');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->edc == null ? 0 : $dataPR->edc ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'edc');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->edc == null ? 0 : $dataPI->edc ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'edc');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->edc == null ? 0 : $dataPRTS->edc ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'edc');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->edc == null ? 0 : $dataPS->edc ?></span></a></li>
				<!-- END EDC -->

				<!-- QRIS -->
					<h3>QRIS</h3>
					<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'qris');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->qris == null ? 0 : $dataPR->qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'qris');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->qris == null ? 0 : $dataPI->qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'qris');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->qris == null ? 0 : $dataPRTS->qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'qris');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->qris == null ? 0 : $dataPS->qris ?></span></a></li>
				<!-- END QRIS -->

				<!-- EDC QRIS -->
					<h3>EDC+QRIS</h3>
					<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'edc_qris');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->edc_qris == null ? 0 : $dataPR->edc_qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'edc_qris');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->edc_qris == null ? 0 : $dataPI->edc_qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'edc_qris');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->edc_qris == null ? 0 : $dataPRTS->edc_qris ?></span></a></li>
					<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'edc_qris');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->edc_qris == null ? 0 : $dataPS->edc_qris ?></span></a></li>
				<!-- END EDC QRIS -->
			</ul>
		</div>
	</div>
</div>

<script>
	function detailMerchant(status, part)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>incoming/merchant/det_breakdown_merchant/'+ status + '/' + part,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}

	function datedif(tgl1, tgl2){
		// varibel miliday sebagai pembagi untuk menghasilkan hari
		var miliday = 24 * 60 * 60 * 1000;
		//buat object Date
		var tanggal1 = new Date(tgl1);
		var tanggal2 = new Date(tgl2);
		// Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
		var tglPertama = Date.parse(tanggal1);
		var tglKedua = Date.parse(tanggal2);
		var selisih = (tglKedua - tglPertama) / miliday;
		return selisih;
	}
</script>