<?php
$date_from = date('d/M/Y', strtotime($this->session->userdata('date_from')));
$date_to   = date('d/M/Y', strtotime($this->session->userdata('date_to')));
$periode   = $date_from . ' - ' . $date_to;

?>
<div class="alert alert-info alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $this->session->userdata('date_from'); ?></h4>
</div>

<a href="<?= site_url('incoming_new/tmsc') ?>" class="btn btn-danger">Back</a>
<a href="javascript:void(0);" onclick="Filter()" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a>

<br>
<br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Total Input TM SC</h4>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow" onclick="view_detail()"><?php echo number_format($query->total); ?></span>
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
		</div>
	</div>
</div>

<script>
	function view_detail() {
		sales_code = '<?php echo $var2; ?>';
		// alert(sales_code)
		nik = '<?php echo $nik; ?>';
		$('#modalDetail').modal('show');
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmsc/detail_leader_popup'); ?>/" + sales_code + "/" + nik,
			type: 'POST',
			data: $('#frmsave').serialize(),
			success: function(data) {
				$("#pop").html('');
				$("#pop").append(data);
			}
		});
	}

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
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmsc/filter_data') ?>",
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
							<input type="text" name="date_from" id="dt_startDate" readonly data-dateformat='yy-mm' class="form-control date-input" value="<?php echo $this->session->userdata('date_from'); ?>" autocomplete="off" />
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