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
		<li>CC Reg</li>
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
		<h3 class="box-title">CC Reg <?php echo $position; ?></h3>
		<?php if (in_array($position, $array_structure)) { ?>
			<a href="<?php echo site_url('incoming/cc_reg/view_leader'); ?>" class="btn btn-primary">View Detail</a>
		<?php } ?>
	</div>
	<div class="panel-body">
		<!-- <div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12">
				<h1 class="page-title txt-color-blueDark"><i class="fa fa-bank fa-fw "></i>
					<b>CC Reg <?php echo $position; ?></b>
				</h1>
			</div>
		</div> -->

		<div class="row">
			<div class="col-sm-2">
				
			</div>
			<div class="col-sm-10">
				<span class="pull-right">
					<h6 class="txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> Filter</h6>
					<form id="form_filter" method="post" class="smart-form" novalidate="novalidate">
						<table>
							<tr>
								<td>
									<h6 class="txt-color-blueDark">Periode &nbsp; </h6>
								</td>
								<td>
									<label class="input" style="display:inline-block;">
										<!-- <i class="icon-prepend fa fa- fa-calendar"></i> -->
										<input type="date" name="date_from" value="<?php echo date('Y-m-01'); ?>" data-dateformat='yy-mm-dd' class="form-control datepicker" required />
										<?php echo form_error('date_from'); ?>
									</label>
									<h6 class="txt-color-blueDark" style="display:inline-block;">&nbsp; S/D &nbsp; </h6>
									<label class="input" style="display:inline-block;">
										<!-- <i class="icon-prepend fa fa- fa-calendar"></i> -->
										<input type="date" name="date_to" value="<?php echo date('Y-m-d'); ?>" data-dateformat='yy-mm-dd' class="form-control datepicker" required />
										<?php echo form_error('date_to'); ?>
									</label>
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<h6 class="txt-color-blueDark">Nama Pameran &nbsp; </h6>
								</td>
								<td>
									<select name="project" id="project" class="multiple-select2 form-control" style="width:100%;">
										<option value="All">All</option>
										<?php foreach ($project as $row) { ?>
											<option value="<?php echo $row->Project; ?>" <?php echo ($row->Project == $selected_project) ? 'selected' : ''; ?>><?php echo $row->Project; ?></option>
										<?php } ?>
									</select>

									<?php echo form_error('project'); ?>
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type="button" name="date_filter" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
									<button type="button" onclick="refreshPage()" class="btn btn-warning" style="padding:5px;"><span class="fa fa-refresh"></span> refresh</button>
								</td>
								<td>&nbsp;&nbsp;</td>
							</tr>
						</table>
					</form>
				</span>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<a href="cc_reg/export"><button type="button" id="btn-export" class="btn btn-primary" style="padding:5px;"><i class="fa fa-file-excel-o"></i> Export Data</button></a>
				<!-- <a href="cc_reg/unit_testing_export"><button type="button" id="btn-export" class="btn btn-primary" style="padding:5px;"><i class="fa fa-file-excel-o"></i> Export Data Test</button></a> -->
				<br><br>
				<div class="table-responsive">
					<table id="data-table-customer" class="table table-hover" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Sales</th>
								<th>Branch</th>
								<th>
									Total DSR <br />
									<small>(Active)</small>
								</th>
								<th>Total Input</th>
								<th>Send BCA Reg</th>
								<th>Send BCA Acco</th>
								<th>Send HC</th>
								<th>Inprocess</th>
								<th>Duplicate</th>
								<th>RTS</th>
								<th>Cancel</th>
								<th>Reject</th>
								<th>Send CC MS</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="12">Loading data from server</td>
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
				url: "<?php echo site_url('incoming/cc_reg/get_data') ?>",
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

	function refreshPage()
	{
		window.location.replace(window.location.href);			
	}

	function filter_data() {
		var formData = new FormData($('#form_filter')[0]);
		$.ajax({
			url: "<?php echo site_url('incoming/cc_reg/filter_data') ?>",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			dataType: "JSON",
			success: function(data) {
				//if success reload to home page
				// table.ajax.reload();
				console.log(data);

				$('#data-table-customer').DataTable().ajax.reload();
				$('#data-table-processing').DataTable().ajax.reload();

			}
		});
	}
	
	// sales-> DSR_Code, pos-> Position, name->Name
	function view_spv(sales, pos, name) {
		console.log(sales, pos, name);
		$('#modalSPV').modal('show');
		var names1 = $('#names1').val();
		var names2 = $('#names2').val();
		var names3 = $('#names3').val();

		if (names1 == "") {
			console.log(name);
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

			$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> -> " + names2);
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
			url: "<?php echo site_url('incoming/cc_reg/detailSPV'); ?>/" + sales + "/" + pos,
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
		console.log(sales);
		console.log(pos);
		$('#modalSPV').modal('show');
		$('#header-all').html("<b>" + names + "</b>");
		$('#names2').val("");
		$('#names3').val("");

		$.ajax({
			url: "<?php echo site_url('incoming/cc_reg/detailSPV'); ?>/" + sales + "/" + pos,
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
			url: "<?php echo site_url('incoming/cc_reg/detail_leader_popup'); ?>/" + sales + "/" + nik + "/" + status,
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
			url: "<?php echo site_url('incoming/cc_reg/detail_leaderMS_popup'); ?>/" + sales + "/" + nik,
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
	// 	window.location.href = '<?php echo site_url('incoming/merchant/detailActualLink'); ?>/' + sales + '/' + pos;
	// }
</script>