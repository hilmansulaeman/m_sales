<?php
	$actualTotal = $getPemol->oa+$getPemol->sn+$getPemol->sk+$getPemol->sd+$getPemol->ktb;
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

<a href="<?= site_url('decision/pemol') ?>" class="btn btn-primary">Refresh <i class="fa fa-refresh"></i></a>
<a data-toggle="modal" data-target="#myModal" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a>

<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Data Decision</h4>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-grey"><?php echo number_format($actualTotal); ?></span>
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">

				<!-- OA -->
					<li><a href="javascript:void(0);">OA <span class="pull-right badge bg-black"><?= $actualOA = $getPemol->oa == null ? 0 : $getPemol->oa ?></span></a></li>
				<!-- END OA -->
				<!-- SN -->
				<li><a href="javascript:void(0);">SN <span class="pull-right badge bg-red"><?= $actualSN = $getPemol->sn == null ? 0 : $getPemol->sn ?></span></a></li>
				<!-- END SN -->
				<!-- SK -->
				<li><a href="javascript:void(0);">SK <span class="pull-right badge bg-green"><?= $actualSK = $getPemol->sk == null ? 0 : $getPemol->sk ?></span></a></li>
				<!-- END SK -->
				<!-- SD -->
				<li><a href="javascript:void(0);">SD <span class="pull-right badge bg-blue"><?= $actualSD = $getPemol->sd == null ? 0 : $getPemol->sd ?></span></a></li>
				<!-- END SD -->
				<!-- KTB -->
				<li><a href="javascript:void(0);">KTB <span class="pull-right badge bg-yellow"><?= $actualKTB = $getPemol->ktb == null ? 0 : $getPemol->ktb ?></span></a></li>
				<!-- END KTB -->

			</ul>
		</div>
	</div>
</div>

<script>
	function detailMerchant(status, part)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/pemol/det_breakdown_merchant_dsr/'+ status + '/' + part,
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
			window.location='<?php echo base_url() ?>decision/pemol/filter_incoming/'+ tgl1 +'/'+ tgl2;
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