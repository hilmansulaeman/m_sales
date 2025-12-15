<?php

$nik 		= $this->session->userdata('username');
$position 	= $this->session->userdata('position');
$name 		= $this->session->userdata('realname');
$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

?>
<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Incoming</li>
		<li>TM CC</li>
	</ol>
</div>

<div class="box box-primary">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-warning fade in">
			<button class="close" data-dismiss="alert" id="notif">
				×
			</button>
			<i class="fa-fw fa fa-check"></i>
			<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php } ?>
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
			</button>
		</div>
		<h3 class="box-title">FORM <?= $this->session->userdata('position') ?></h3>
		<?php if (in_array($position, $array_structure)) { ?>
			<a href="<?php echo site_url('incoming_new/tmcc/view_leader'); ?>" class="btn btn-primary">View Detail</a>
		<?php } ?>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12">
				<h1 class="page-title txt-color-blueDark"><i class="fa fa-bank fa-fw "></i>
					<b>TM CC <?php echo $position; ?></b>
				</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-2">
				<h6 class="txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> Filter</h6>
				<a href="<?= site_url('incoming_new/tmcc/export') ?>"><button type="button" id="btn-export" class="btn btn-primary" style="padding:5px;"><i class="fa fa-file-excel-o"></i> Export Data</button></a>
			</div>
			<div class="col-sm-10">
				<span class="pull-right">
					<form id="form_filter" method="post" class="smart-form" novalidate="novalidate">
						<table>
							<tr>
								<td>
									<h6 class="txt-color-blueDark">Periode &nbsp; </h6>
								</td>
								<td>
									<label class="input">
										<input type="text" name="date_from" value="<?php echo $this->session->userdata('date_from'); ?>" data-dateformat='yy-mm' class="form-control date-input" autocomplete="off" required />
										<?php echo form_error('date_from'); ?>
									</label>
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
								<td>
									<button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
								</td>
							</tr>
						</table>
					</form>
				</span>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="data-table-customer" class="table table-hover" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>Branch</th>
								<th>
									Total DSR <br />
									<small>(Active)</small>
								</th>
								<th>Submit BCA</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="4">Loading data from server</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalSPV">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header" id="headerSPV">
				<!-- <h4 class="modal-title" id="header-name"></h4> -->
				<input type="hidden" name="names1" id="names1" value="">
				<input type="hidden" name="pos1" id="pos1" value="">
				<input type="hidden" name="sales1" id="sales1" value="">
				<input type="hidden" name="names2" id="names2" value="">
				<input type="hidden" name="names3" id="names3" value="">
				<span id="header-all"></span><br>


				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmspv" class="form-horizontal form-bordered">
					<div id="pop"></div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalActual">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="header-name1"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmspv1" class="form-horizontal form-bordered">
					<div id="pop1"></div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		table = $("#data-table-customer").DataTable({
			ordering: false,
			//searching:false,
			processing: true,
			serverSide: true,
			responsive: true,
			ajax: {
				url: "<?php echo site_url('incoming_new/tmcc/get_data') ?>",
				type: 'POST',
				/*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
			},
			initComplete: function() {
				var input = $('#data-table-customer_filter input').unbind(),
					self = this.api(),
					searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
					.click(function() {
						self.search(input.val()).draw();
					});
				$(document).keypress(function(event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
				$('#data-table-customer_filter').append(searchButton);
			}
		});
	});

	function filter_data() {
		var formData = new FormData($('#form_filter')[0]);
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmcc/filter_data') ?>",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			dataType: "JSON",
			success: function(data) {
				//if success reload to home page
				// table.ajax.reload();

				$('#data-table-customer').DataTable().ajax.reload();
				$('#data-table-processing').DataTable().ajax.reload();

			}
		});
	}

	function view_spv(sales, pos, name) {
		console.log(sales, pos, name);
		$('#modalSPV').modal('show');
		var names1 = $('#names1').val();
		var names2 = $('#names2').val();
		var names3 = $('#names3').val();

		if (names1 == "") {
			$('#names1').val(name);
			$('#pos1').val(pos);
			$('#sales1').val(sales);
			names1 = $('#names1').val();
			$('#header-all').html("<b>" + names1 + "</b>");
		} else if (names2 == "") {
			$('#names2').val(name);
			names2 = $('#names2').val();
			names1 = $('#names1').val();
			pos1 = $('#pos1').val();
			sales1 = $('#sales1').val();

			// if (names2 == names1) {
			// 	$('#names2').val("");
			// 	$('#header-all').html("<b>"+names1+"</b>");
			// }else{
			// }

			$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\"" + sales1 + "\",\"" + pos1 + "\",\"" + names1 + "\")'>" + names1 + "</a></b> -> " + names2);
		} else {
			$('#names3').val(name);
			names3 = $('#names3').val();
			names1 = $('#names1').val();
			names2 = $('#names2').val();

			pos1 = $('#pos1').val();
			sales1 = $('#sales1').val();

			// if (names3 == names1) {
			// 	$('#names3').val("");
			// 	$('#header-all').html("<b>"+names1+"</b>");
			// }else if(names3 == names2) {
			// 	$('#names2').val("");
			// 	$('#names3').val("");
			// 	$('#header-all').html("<b>"+names1+"</b>");
			// }else{
			// }
			$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\"" + sales1 + "\",\"" + pos1 + "\",\"" + names1 + "\")'>" + names1 + "</a></b> -> " + names2 + " -> " + names3);
		}

		// var inpBaru = document.createElement('a');
		// var teksInpBaru = document.createTextNode('item baru');
		// inpBaru.appendChild(teksInpBaru);
		// divSPV.appendChild(inpBaru);
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmcc/detailSPV'); ?>/" + sales + "/" + pos,
			type: "POST",
			data: $("#frmspv").serialize(),
			success: function(data) {
				$("#pop").html('');
				$("#pop").append(data);
			}
		});
	}

	$('#modalSPV').on('hidden.bs.modal', function() {
		$('#names1').val(name);
		$('#names2').val(name);
		$('#names3').val(name);

		$('#pos1').val(name);
		$('#sales1').val(name);
	});

	function view_spv_click(sales, pos, names) {
		// console.log(sales);
		// console.log(pos);
		$('#modalSPV').modal('show');
		$('#header-all').html("<b>" + names + "</b>");
		$('#names2').val("");
		$('#names3').val("");

		$.ajax({
			url: "<?php echo site_url('incoming_new/tmcc/detailSPV'); ?>/" + sales + "/" + pos,
			type: "POST",
			data: $("#frmspv").serialize(),
			success: function(data) {
				$("#pop").html('');
				$("#pop").append(data);
			}
		});
	}

	function view_detail(sales, nik, status, name) {
		$('#modalActual').modal('show');
		console.log(status);
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmcc/detail_leader_popup'); ?>/" + sales + "/" + nik + "/" + status,
			type: "POST",
			data: $("#frmspv1").serialize(),
			success: function(data) {
				$("#pop1").html('');
				$("#pop1").append(data);
				$("#header-name1").html(name);
			}
		});
	}

	function view_detail_ms(sales, nik, name) {
		$('#modalActual').modal('show');
		// console.log(status);
		$.ajax({
			url: "<?php echo site_url('incoming_new/tmcc/detail_leaderMS_popup'); ?>/" + sales + "/" + nik,
			type: "POST",
			data: $("#frmspv1").serialize(),
			success: function(data) {
				$("#pop1").html('');
				$("#pop1").append(data);
				$("#header-name1").html(name);
			}
		});
	}
	// function view_detail_link(sales, pos)
	// {
	// 	window.location.href = '<?php echo site_url('incoming_new/merchant/detailActualLink'); ?>/' + sales + '/' + pos;
	// }
</script>