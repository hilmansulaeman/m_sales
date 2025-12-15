<?php
	$day = date('d');
    $month = date('M');
    $year = date('Y');
    $periode = "01 - ".$day." / ".$month." / ".$year;
?>
<a href="<?= site_url('decision/merchant') ?>" class="btn btn-danger">Back</a>
<br><br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h3>EDC</h3>
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
				
				<!-- NEW -->
				<h4>NEW</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'KREDIT', 'EDC')">KREDIT <span class="pull-right badge bg-blue"><?= $kredit = $allDataEDC->kredit == null ? 0 : $allDataEDC->kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointKredit ?></span></a></li>

				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'NON KREDIT', 'EDC')">NON KREDIT <span class="pull-right badge bg-blue"><?= $non_kredit = $allDataEDC->non_kredit == null ? 0 : $allDataEDC->non_kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointNonKredit ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN CABANG', 'EDC')">Tambahan Cabang <span class="pull-right badge bg-blue"><?= $tc = $allDataEDC->tc == null ? 0 : $allDataEDC->tc; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN TERMINAL', 'EDC')">Tambahan Terminal <span class="pull-right badge bg-blue"><?= $terminal = $allDataEDC->terminal == null ? 0 : $allDataEDC->terminal; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'UBAH FASILITAS', 'EDC')">Ubah Fasilitas <span class="pull-right badge bg-blue"><?= $uf = $allDataEDC->uf == null ? 0 : $allDataEDC->uf; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'REAGREMENT', 'EDC')">Re Agrement <span class="pull-right badge bg-blue"><?= $ra = $allDataEDC->ra == null ? 0 : $allDataEDC->ra; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointsExisEDC ?></span></a></li>
				<!-- END EXISTING -->
				
				<!-- REJECTED -->
				<h4><li><a style="color:red;" href="javascript:void(0);" onclick="detailMerchant('reject', 'REJECT', 'EDC')">Rejected <span class="pull-right badge bg-red"><?= $reject1 = $allDataEDC->rejected == null ? 0 : $allDataEDC->rejected; ?></span></a></li></h4>
				<!-- END REJECTED -->
			</ul>
		</div>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h3>QRIS</h3>
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

				<!-- NEW -->
				<h4>NEW</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'QRD', 'QRIS')">QRD <span class="pull-right badge bg-blue"><?= $nQRD = $dataQrisNew->qrd == null ? 0 : $dataQrisNew->qrd; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'QSD', 'QRIS')">QSD <span class="pull-right badge bg-blue"><?= $nQSD = $dataQrisNew->qsd == null ? 0 : $dataQrisNew->qsd; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointsNewQRIS ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'QRD', 'QRIS')">QRD <span class="pull-right badge bg-blue"><?= $eQRD = $dataQrisExis->qrd == null ? 0 : $dataQrisExis->qrd; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'QSD', 'QRIS')">QSD <span class="pull-right badge bg-blue"><?= $eQSD = $dataQrisExis->qsd == null ? 0 : $dataQrisExis->qsd; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointsExisQRIS ?></span></a></li>
				<!-- END EXISTING -->
				
				<!-- REJECTED -->
				<h4><li><a style="color:red;" href="javascript:void(0);" onclick="detailMerchant('reject', 'REJECT', 'QRIS')">Rejected <span class="pull-right badge bg-red"><?= $reject2 = $dataQrisReject->rejected == null ? 0 : $dataQrisReject->rejected; ?></span></a></li></h4>
				<!-- END REJECTED -->
			</ul>
		</div>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h3>EDC+QRIS</h3>
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

				<!-- NEW -->
				<h4>NEW</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'KREDIT', 'EDC_QRIS')">KREDIT <span class="pull-right badge bg-blue"><?= $kredit2 = $allDataEDC2->kredit == null ? 0 : $allDataEDC2->kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointKredit ?></span></a></li>

				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'NON KREDIT', 'EDC_QRIS')">NON KREDIT <span class="pull-right badge bg-blue"><?= $non_kredit2 = $allDataEDC2->non_kredit == null ? 0 : $allDataEDC2->non_kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointNonKredit ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN CABANG', 'EDC_QRIS')">Tambahan Cabang <span class="pull-right badge bg-blue"><?= $tc2 = $allDataEDC2->tc == null ? 0 : $allDataEDC2->tc; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN TERMINAL', 'EDC_QRIS')">Tambahan Terminal <span class="pull-right badge bg-blue"><?= $terminal2 = $allDataEDC2->terminal == null ? 0 : $allDataEDC2->terminal; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'UBAH FASILITAS', 'EDC_QRIS')">Ubah Fasilitas <span class="pull-right badge bg-blue"><?= $uf2 = $allDataEDC2->uf == null ? 0 : $allDataEDC2->uf; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'REAGREMENT', 'EDC_QRIS')">Re Agrement <span class="pull-right badge bg-blue"><?= $ra2 = $allDataEDC2->ra == null ? 0 : $allDataEDC2->ra; ?></span></a></li>
				<li><a href="javascript:void(0);">Point<span class="pull-right badge bg-yellow"><?= $totalPointsExisEDC ?></span></a></li>
				<!-- END EXISTING -->
				
				<!-- REJECTED -->
				<h4><li><a style="color:red;" href="javascript:void(0);" onclick="detailMerchant('reject', 'REJECT', 'EDC_QRIS')">Rejected <span class="pull-right badge bg-red"><?= $reject3 = $allDataEDC2->rejected == null ? 0 : $allDataEDC2->rejected; ?></span></a></li></h4>
				<!-- END REJECTED -->
			</ul>
		</div>
	</div>
</div>

<script>
	function detailMerchant(mid, ft, product)
	{
		var sales_code = $('#sales_code').val();
		var sales = $('#sales').val();
		var fas = ft.replace(' ', '-');

		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/merchant/det_breakdown_merchant_leader/'+ sales_code + '/' + sales + '/' + mid + '/' + fas + '/' + product + '/popup',
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');
                $("#pop").append(data);  
            }  
        });
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