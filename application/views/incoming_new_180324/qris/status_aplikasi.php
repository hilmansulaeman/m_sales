<?php
	$uri3 = $this->uri->segment(3); //get_status & index

	$br_qris = $breakdown_qris->row();
	$rowTotal = $sql_total->row();

	$day = date('d');
    $month = date('M');
    $year = date('Y');

	if($uri3 == 'get_status') {
		$dari = $this->uri->segment(4);
		$sampai = $this->uri->segment(5);
    	$periode = "01 - ".$day." / ".$month." / ".$year;
	}
	else {
		$bulan = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);

		$dari = $this->uri->segment(3);
		$tgl1_ = explode('-', $dari);
		$tgl1 = $tgl1_[2];

		$sampai = $this->uri->segment(4);
		$tgl2_ = explode('-', $sampai);

		$periode = $tgl1.' - '.$tgl2_[2].' / '.$bulan[$tgl2_[1]].' / '.$tgl2_[0];
	}
?>
<div class="alert alert-info alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?></h4>
</div>

<a href="<?= site_url('incoming/qris/get_status/'.$dari.'/'.$sampai) ?>" class="btn btn-primary">Refresh <i class="fa fa-refresh"></i></a>
<a href="javascript:void(0);" onclick="Filter()" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a>

<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="col-md-6">
			<h4>Setoran Aplikasi QRIS | <?=$this->session->userdata('position')?></h4>
		</div>
		<div class="col-md-6">
			<span class="pull-right badge bg-yellow"><?php echo number_format($rowTotal->qris); ?></span>
		</div>
	</div>
	<div class="box-body">
		<div class="box box-widget widget-user-2">
		<!-- Add the bg color to the header using any of the bg-* classes -->
			<div class="box-footer no-padding">
			<ul class="nav nav-stacked">
				<?php
					foreach($status_aplikasi as $key) {
						$status = $key->status;
						if ($status == 'submit_to_dika') {
							$count_qris = $br_qris->submit_to_dika;
						} elseif ($status == 'pending_fu') {
							$count_qris = $br_qris->pending_fu;
						} elseif ($status == 'submit_to_bca') {
							$count_qris = $br_qris->submit_to_bca;
						} elseif ($status == 'return_from_bca') {
							$count_qris = $br_qris->return_from_bca;
						} elseif ($status == 'resubmit_to_bca') {
							$count_qris = $br_qris->resubmit_to_bca;
						} elseif ($status == 'cancel') {
							$count_qris = $br_qris->cancel;
						} elseif ($status == 'reject') {
							$count_qris = $br_qris->reject;
						}

						if($count_qris == NULL) {
							$count = '0';
						}
						else {
							$count = $count_qris;
						}
				?>
					<li>
						<a href="<?php echo site_url('incoming/qris/index/'.$status.'/'.$dari.'/'.$sampai); ?>">
							<?=strtoupper($key->desc)?> <span class="pull-right badge bg-blue"><?=$count?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>



<Script>
	
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
			window.location='<?php echo base_url() ?>filter_incoming_qris/index/'+ tgl1 +'/'+ tgl2;
		}
		
	}

</script>





<!-- Modal Add -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Filter Form</h4>
            </div>
			<div class="modal-body">
				<form method="post" action="<?php echo site_url(); ?>insentif/filter_performance">
					<div class="form-group">		 
						<div class="input-group">
							<label>Start Date </label>
						</div>	
						<div class="input-group">
							<label class="input-group-btn" for="dt_startDate">
								<span class="btn btn-default">
									<span class="fa fa-calendar"></span>
								</span>
							</label>
							<input type="text" name="start_date" readonly id="dt_startDate" class="form-control tanggal" value="<?php echo $this->input->post('start_date'); ?>" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label>End Date</label>
						</div>
						<div class="input-group">
							<label class="input-group-btn" for="dt_endDate">
								<span class="btn btn-default">
									<span class="fa fa-calendar"></span>
								</span>
							</label>
							<input type="text" name="end_date" readonly id="dt_endDate" class="form-control tanggal" value="<?php echo $this->input->post('end_date'); ?>" autocomplete="off"/>
						</div>			 
					</div>
					<!--<input type="submit" class="btn btn-primary btn-sm" value="Go"/>-->
					<a onclick="return submit_filter()" class="btn btn-primary btn-sm">Go</a>
				</form>
				<br>
				<br>
				<div class="alert alert-info alert-dismissible" style="display:block;" id="alert1">
					<h5><i class="icon fa fa-info"></i> Tanggal Tidak Boleh Kosong. <br> 
					    <i class="icon fa fa-info"></i> Range Tanggal maksimal 31 hari
					</h5>
				</div>
				<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert2">
					<h5><i class="icon fa fa-info"></i> Tanggal Kosong. Isi Tanggal Dengan Benar!</h5>
				</div>
				<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert3">
					<h5><i class="icon fa fa-info"></i> Maaf, range tanggal maksimal 31 hari</h5>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->