<?php
	// Data Input
	$dataIS = $dataGetDataIS; //IS = Input => Input System
	$dataBS = $dataGetDataBS; //BS = Input => Belum Setor

	// Data Processing
	$dataPR 	= $dataGetDataPR;
	$dataPI 	= $dataGetDataPI;
	$dataPRTS 	= $dataGetDataPRTS;
	$dataPS 	= $dataGetDataPS;
	$dataPC 	= $dataGetDataPC;
	$dataPPS 	= $dataGetDataPPS;
	// $dataPTotal = $getDataPTotal;

	$day = date('d');
    $month = date('M');
    $year = date('Y');
    $periode = "01 - ".$day." / ".$month." / ".$year;
	
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Data Input</h4>
				<input type="hidden" name="sales_code" id="sales_code" value="<?= $sales_code ?>">
				<input type="hidden" name="sales" id="sales" value="<?= $sales ?>">
			</div>
			<!-- <div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($dataIS->total); ?></span>
			</div> -->
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">

				<!-- EDC -->
				<h4><b>EDC</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc_system');">Total Input <span class="pull-right badge bg-blue"><?= $edcIS = $dataIS->edc == null ? 0 : $dataIS->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc_belum_setor');">Belum Setor <span class="pull-right badge bg-blue"><?= $edcBS = $dataBS->edc == null ? 0 : $dataBS->edc ?></span></a></li>
				<!-- END EDC -->

				<!-- QRIS -->
				<h4><b>QRIS</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'qris_system');">Total Input <span class="pull-right badge bg-blue"><?= $qrisIS = $dataIS->qris == null ? 0 : $dataIS->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'qris_belum_setor');">Belum Setor <span class="pull-right badge bg-blue"><?= $qrisBS = $dataBS->qris == null ? 0 : $dataBS->qris ?></span></a></li>
				<!-- END QRIS -->

				<!-- EDC QRIS -->
				<h4><b>EDC+QRIS</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc_qris_system');">Total Input <span class="pull-right badge bg-blue"><?= $edc_qrisIS = $dataIS->edc_qris == null ? 0 : $dataIS->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('system', 'edc_qris_belum_setor');">Belum Setor <span class="pull-right badge bg-blue"><?= $edc_qrisBS = $dataBS->edc_qris == null ? 0 : $dataBS->edc_qris ?></span></a></li>
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
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">

				<!-- EDC -->
				<h4><b>EDC</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'edc');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->edc == null ? 0 : $dataPR->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'edc');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->edc == null ? 0 : $dataPI->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'edc');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->edc == null ? 0 : $dataPRTS->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'edc');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->edc == null ? 0 : $dataPS->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('pending', 'edc');">Pending <span class="pull-right badge bg-blue"><?= $pending = $dataPPS->edc == null ? 0 : $dataPPS->edc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('cancel', 'edc');">Cancel <span class="pull-right badge bg-blue"><?= $cancel = $dataPC->edc == null ? 0 : $dataPC->edc ?></span></a></li>
				<!-- END EDC -->

				<!-- QRIS -->
				<h4><b>QRIS</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'qris');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->qris == null ? 0 : $dataPR->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'qris');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->qris == null ? 0 : $dataPI->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'qris');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->qris == null ? 0 : $dataPRTS->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'qris');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->qris == null ? 0 : $dataPS->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('pending', 'qris');">Pending <span class="pull-right badge bg-blue"><?= $pending = $dataPPS->qris == null ? 0 : $dataPPS->qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('cancel', 'qris');">Cancel <span class="pull-right badge bg-blue"><?= $cancel = $dataPC->qris == null ? 0 : $dataPC->qris ?></span></a></li>
				<!-- END QRIS -->

				<!-- EDC QRIS -->
				<h4><b>EDC+QRIS</b></h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('received', 'edc_qris');">Received <span class="pull-right badge bg-blue"><?= $received = $dataPR->edc_qris == null ? 0 : $dataPR->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('inprocess', 'edc_qris');">Inprocess <span class="pull-right badge bg-blue"><?= $inprocess = $dataPI->edc_qris == null ? 0 : $dataPI->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('rts', 'edc_qris');">RTS <span class="pull-right badge bg-blue"><?= $rts = $dataPRTS->edc_qris == null ? 0 : $dataPRTS->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('send', 'edc_qris');">Send <span class="pull-right badge bg-blue"><?= $send = $dataPS->edc_qris == null ? 0 : $dataPS->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('pending', 'edc_qris');">Pending <span class="pull-right badge bg-blue"><?= $pending = $dataPPS->edc_qris == null ? 0 : $dataPPS->edc_qris ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('cancel', 'edc_qris');">Cancel <span class="pull-right badge bg-blue"><?= $cancel = $dataPC->edc_qris == null ? 0 : $dataPC->edc_qris ?></span></a></li>
				<!-- END EDC QRIS -->
			</ul>
		</div>
	</div>
</div>

<script>
	function detailMerchant(status, part)
	{
		// console.log(status, part);
		// $('#modalDetail').modal('show');
		// $.ajax({
        //     url:'<?php echo site_url(); ?>incoming/merchant/det_breakdown_merchant/'+ status + '/' + part,
        //     type:'POST',
        //     data:$('#frmsave').serialize(),
        //     success:function(data){ 
        //         $("#pop").html('');
        //         $("#pop").append(data);  
        //     }  
        // });
		var sales_code = $('#sales_code').val();
		var sales = $('#sales').val();
		window.open('<?php echo site_url('incoming/merchant/det_breakdown_merchant_leader'); ?>/' + sales_code + '/' + sales + '/' + status + '/' + part + '/link', '_blank');
	}
</script>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> View Detail</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<div id="pop"></div>
			</div>
		</div>
	</div>
</div>