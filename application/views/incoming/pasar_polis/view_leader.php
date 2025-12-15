<?php
$date_from = date('d/M/Y', strtotime($this->session->userdata('date_from')));
$date_to   = date('d/M/Y', strtotime($this->session->userdata('date_to')));
$periode   = $date_from . ' - ' . $date_to;

$total_basic = ($query == null) ? 0 : $query->total_basic;
$total_platinum = ($query == null) ? 0 : $query->total_platinum;
$total_produk = ($query == null) ? 0 : $query->jumlah;

$premi_basic = ($query->premi_basic == null) ? 0 : $query->premi_basic;
$premi_platinum = ($query->premi_platinum == null) ? 0 : $query->premi_platinum;
$total_premi = ($query->total_premi == null) ? 0 : $query->total_premi;

?>
<div class="alert alert-info alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?></h4>
</div>

<a href="<?= site_url('incoming/pasar_polis') ?>" class="btn btn-danger">Back</a>
<a href="javascript:void(0);" onclick="Filter()" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a>

<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Setoran Aplikasi PASAR POLIS</h4>
			</div>
			<div class="col-md-6">
				<!-- <span class="pull-right badge bg-yellow"><?php echo number_format($query->total); ?></span> -->
			</div>
		</div>


		<hr>


		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">
				<li><p><b> Basic </b></p></li>
				<li> Total <span class="pull-right badge bg-blue"><?php echo $total_basic ?></span></></li>
				<li> Premi <span class="pull-right badge bg-blue"><?php echo $premi_basic ?></span></></li>
				<li><p><b> Platinum </b></p></li>
				<li> Total <span class="pull-right badge bg-blue"><?php echo $total_platinum ?></span></></li>
				<li> Premi <span class="pull-right badge bg-blue"><?php echo $premi_platinum ?></span></></li>
				<li><p><b> Total Produk </b></p></li>
				<li> Total <span class="pull-right badge bg-blue"><?php echo $total_produk ?></span></></li>
				<li> Premi <span class="pull-right badge bg-blue"><?php echo $total_premi ?></span></></li>
				
			</ul>
		</div>
	</div>
</div>

<script>
	function Filter() {
		//filter_incoming
		$('#modalFilter').modal('show');
	}

	function datedif(tgl1, tgl2) {
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

	function submit_filter() {
		var formData = new FormData($('#form_filter')[0]);
		var tgl1 = document.getElementById('dt_startDate').value;
		var tgl2 = document.getElementById('dt_endDate').value;
		var days = datedif(tgl1, tgl2);
		if (tgl1 == "" || tgl2 == "") {
			document.getElementById('alert1').style.display = 'none';
			document.getElementById('alert2').style.display = 'block';
			document.getElementById('alert3').style.display = 'none';
		} else if (days > 31) {
			document.getElementById('alert1').style.display = 'none';
			document.getElementById('alert2').style.display = 'none';
			document.getElementById('alert3').style.display = 'block';
		} else {
			$.ajax({
				url: "<?php echo site_url('incoming/pasar_polis/filter_data') ?>",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function(data) {
					//if success reload to home page
					$('#modalFilter').modal('hide');
					window.location.reload();

				}
			});
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
				<form id="form_filter" method="post" novalidate="novalidate">
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
							<input type="text" name="date_from" readonly id="dt_startDate" class="form-control tanggal" value="<?php echo $this->session->userdata('date_from'); ?>" autocomplete="off" />
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
							<input type="text" name="date_to" readonly id="dt_endDate" class="form-control tanggal" value="<?php echo $this->session->userdata('date_to'); ?>" autocomplete="off" />
						</div>
					</div>
					<!--<input type="submit" class="btn btn-primary btn-sm" value="Go"/>-->
					<a onclick="submit_filter()" class="btn btn-primary btn-sm">Go</a>
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