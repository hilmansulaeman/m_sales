<?php
    $month = date('M');
    $year = date('Y');
    $periode = $month." / ".$year;
	
	if($this->input->post('date') == "")
	{
		$tgl = date('Y-m');
		$periode = $periode;
	}
	else
	{
		$tgl = $this->input->post('date');
		$periode = date('M / Y', strtotime($this->input->post('date')));
	}
	
	//$rowCount = $counter->row();
	$br_edc = $breakdown_edc->row();
?>
<div class="alert alert-success alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?> </h4>
</div>
<a href="" class="btn btn-success">Resfresh <i class="fa fa-refresh"></i></a>
<a data-toggle="modal" data-target="#myModal" class="btn btn-success">Filter <i class="fa fa-filter"></i></a>
<br>
<br>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision EDC</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_edc->counter_edc; ?></span>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('APPROVE', '<?php echo $tgl; ?>')">APPROVE
							NTB<span class="pull-right badge bg-green"><?php echo $br_edc->approve_edc; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('ADDON', '<?php echo $tgl; ?>')">APPROVE
							ADDON<span
								class="pull-right badge bg-green"><?php echo $br_edc->approve_addon; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span
								class="pull-right badge bg-green"><?php echo $br_edc->cancel_edc; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);"
							onclick="detail_edc('DECLINE', '<?php echo $tgl; ?>')">DECLINE<span
								class="pull-right badge bg-green"><?php echo $br_edc->decline_edc; ?></span></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>

<script>
	function detail_edc(status, tanggal) {
		$('#modalDetail').modal('show');
		$.ajax({
			url: '<?php echo site_url(); ?>decision/edc/det_breakdown_edc/' + status + '/' + tanggal,
			type: 'POST',
			data: $('#frmsave').serialize(),
			success: function (data) {
				$("#pop").html('');
				$("#pop").append(data);
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