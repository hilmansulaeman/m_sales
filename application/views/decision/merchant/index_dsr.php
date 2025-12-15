<?php
	$last_period = $this->session->userdata('groupDate').'-01';

	$month = date('M',strtotime($last_period));
    $year = date('Y',strtotime($last_period));
    $periode = $month." / ".$year;
	
	if($this->input->post('date') == "")
	{
		$tgl = $this->session->userdata('groupDate');
		$periode = $periode;
	}
	else
	{
		$tgl = $this->input->post('date');
		$periode = date('M / Y', strtotime($this->input->post('date')));
	}
?>
<div class="alert alert-info alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?></h4>
</div>

<a href="<?= site_url('decision/merchant') ?>" class="btn btn-primary">Refresh <i class="fa fa-refresh"></i></a>
<a data-toggle="modal" data-target="#myModal" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a>

<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h3>EDC</h3>
				<input type="hidden" name="sales_code" id="sales_code" value="<?= $sales_code ?>">
				<input type="hidden" name="sales" id="sales" value="<?= $sales ?>">
			</div>
			<!-- <div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php //echo number_format($dataIS->total); ?></span>
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
				<li><a href="javascript:void(0);">Point Kredit<span class="pull-right badge bg-yellow"><?= $totalPointKredit ?></span></a></li>

				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'NON KREDIT', 'EDC')">NON KREDIT <span class="pull-right badge bg-blue"><?= $non_kredit = $allDataEDC->non_kredit == null ? 0 : $allDataEDC->non_kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point Non Kredit<span class="pull-right badge bg-yellow"><?= $totalPointNonKredit ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN CABANG', 'EDC')">Tambahan Cabang <span class="pull-right badge bg-blue"><?= $tc = $allDataEDC->tc == null ? 0 : $allDataEDC->tc; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN TERMINAL', 'EDC')">Tambahan Terminal <span class="pull-right badge bg-blue"><?= $terminal = $allDataEDC->terminal == null ? 0 : $allDataEDC->terminal; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'UBAH FASILITAS', 'EDC')">Ubah Fasilitas <span class="pull-right badge bg-blue"><?= $uf = $allDataEDC->uf == null ? 0 : $allDataEDC->uf; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'REAGREMENT', 'EDC')">Re Agrement <span class="pull-right badge bg-blue"><?= $ra = $allDataEDC->ra == null ? 0 : $allDataEDC->ra; ?></span></a></li>
				<li><a href="javascript:void(0);">Point Existing<span class="pull-right badge bg-yellow"><?= $totalPointsExisEDC ?></span></a></li>
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
				<span class="pull-right badge bg-yellow"><?php //echo number_format($dataIS->total); ?></span>
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
				<li><a href="javascript:void(0);">Point New<span class="pull-right badge bg-yellow"><?= $totalPointsNewQRIS ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'QRD', 'QRIS')">QRD <span class="pull-right badge bg-blue"><?= $eQRD = $dataQrisExis->qrd == null ? 0 : $dataQrisExis->qrd; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'QSD', 'QRIS')">QSD <span class="pull-right badge bg-blue"><?= $eQSD = $dataQrisExis->qsd == null ? 0 : $dataQrisExis->qsd; ?></span></a></li>
				<li><a href="javascript:void(0);">Point Existing<span class="pull-right badge bg-yellow"><?= $totalPointsExisQRIS ?></span></a></li>
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
				<span class="pull-right badge bg-yellow"><?php //echo number_format($dataIS->total); ?></span>
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
				<li><a href="javascript:void(0);">Point Kredit<span class="pull-right badge bg-yellow"><?= $totalPointKredit2 ?></span></a></li>

				<li><a href="javascript:void(0);" onclick="detailMerchant('new', 'NON KREDIT', 'EDC_QRIS')">NON KREDIT <span class="pull-right badge bg-blue"><?= $non_kredit2 = $allDataEDC2->non_kredit == null ? 0 : $allDataEDC2->non_kredit; ?></span></a></li>
				<li><a href="javascript:void(0);">Point Non Kredit<span class="pull-right badge bg-yellow"><?= $totalPointNonKredit2 ?></span></a></li>
				<!-- END NEW -->

				<!-- EXISTING -->
				<h4>EXISTING</h4>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN CABANG', 'EDC_QRIS')">Tambahan Cabang <span class="pull-right badge bg-blue"><?= $tc2 = $allDataEDC2->tc == null ? 0 : $allDataEDC2->tc; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'TAMBAHAN TERMINAL', 'EDC_QRIS')">Tambahan Terminal <span class="pull-right badge bg-blue"><?= $terminal2 = $allDataEDC2->terminal == null ? 0 : $allDataEDC2->terminal; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'UBAH FASILITAS', 'EDC_QRIS')">Ubah Fasilitas <span class="pull-right badge bg-blue"><?= $uf2 = $allDataEDC2->uf == null ? 0 : $allDataEDC2->uf; ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="detailMerchant('existing', 'REAGREMENT', 'EDC_QRIS')">Re Agrement <span class="pull-right badge bg-blue"><?= $ra2 = $allDataEDC2->ra == null ? 0 : $allDataEDC2->ra; ?></span></a></li>
				<li><a href="javascript:void(0);">Point Existing<span class="pull-right badge bg-yellow"><?= $totalPointsExisEDC2 ?></span></a></li>
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
            url:'<?php echo site_url(); ?>decision/merchant/det_breakdown_merchant_dsr/'+ sales_code + '/' + sales + '/' + mid + '/' + fas + '/' + product,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function Filter()
	{
		//filter_incoming
		$('#modalFilter').modal('show');
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
	
	function submit_filter()
	{
		var tgl1 = document.getElementById('dt_startDate').value;
		var tgl2 = document.getElementById('dt_endDate').value;
		var days = datedif(tgl1,tgl2);
		if(tgl1 == "" || tgl2 ==  ""){
			document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'block';
			document.getElementById('alert3').style.display= 'none';
		}
		else if(days > 31){
		    document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'none';
			document.getElementById('alert3').style.display= 'block';
		}
		else{
			// window.location='<?php echo base_url() ?>filter_incoming/index/merchant/'+ tgl1 +'/'+ tgl2;
			window.location='<?php echo base_url() ?>decision/merchant/filter_incoming/'+ tgl1 +'/'+ tgl2;
		}
		
	}
</script>

<!-- Modal Add -->
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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Filter</h4>
			</div>
			<div class="modal-body">
				<form method="post">
					<div class="form-group">
						<div class="input-group">
							<label>Date </label>
						</div>
						<div class="input-group">
							<label class="input-group-btn" for="dt_startDate">
								<span class="btn btn-default">
									<span class="fa fa-calendar"></span>
								</span>
							</label>
							<input type="text" name="date" id="dt_startDate" class="form-control date-input"
								value="<?php echo $this->input->post('start_date'); ?>" placeholder="Y-m"
								autocomplete="off" />
						</div>
					</div>

			</div>
			<div class="modal-footer">
				<input type="submit" value="Go" name="submit" class="btn btn-primary" />
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</form>
			</div>
		</div>
	</div>
</div>