<?php
	$date_from = date('d/M/Y',strtotime($this->session->userdata('date_from')));
	$date_to   = date('d/M/Y',strtotime($this->session->userdata('date_to')));
    $periode   = $date_from.' - '.$date_to;
	$send      = $query->send == null ? 0 : $query->send;
	$send_hc   = $query->send_hc == null ? 0 : $query->send_hc;
	$inprocess = $query->inprocess == null ? 0 : $query->inprocess;
	$duplicate = $query->duplicate == null ? 0 : $query->duplicate;
	$rts       = $query->rts == null ? 0 : $query->rts;
	$cancel    = $query->cancel == null ? 0 : $query->cancel;
	$reject    = $query->reject == null ? 0 : $query->reject;
?>

<div class="alert alert-info">
	<div class="row">
		<div class="col-sm-12">
			<span class="pull-right">
				<form action="" method="post">
					<table style="width:100%;">
						<tr>
							<th colspan="5"><b>Periode : </b></th>	
						</tr>
						<tr>	
							<td>
								<label class="input">
									<input type="date" name="date_from" value="<?php echo $this->session->userdata('date_from'); ?>" class="form-control" required/>
								<?php echo form_error('date_from'); ?>
								</label>	
							</td>											
							<td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>											
							<td>
								<label class="input">
									<input type="date" name="date_to" value="<?php echo $this->session->userdata('date_to'); ?>" class="form-control" required/>
								<?php echo form_error('date_to'); ?>
								</label>
							</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>
								<button type="submit" id="btn-filter" name="date_filter" class="btn btn-success" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
							</td>																					
						</tr>						 
					</table>
				</form>
			</span>
		</div>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Setoran Aplikasi CC</h4>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge bg-yellow"><?php echo number_format($query->total); ?></span>
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes -->
			<ul class="nav nav-stacked">
				<li><a href="javascript:void(0);" onclick="view_detail('SEND');">SEND BCA <span class="pull-right badge bg-blue"><?php echo $send ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('SEND_HC');">SEND HC <span class="pull-right badge bg-blue"><?php echo $send_hc ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('INPROCESS');">INPROCESS APP <span class="pull-right badge bg-blue"><?php echo $inprocess ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('DUPLICATE');"> DUPLICATE <span class="pull-right badge bg-blue"><?php echo $duplicate ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('RTS');"> RTS <span class="pull-right badge bg-blue"><?php echo $rts ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('CANCEL');">CANCEL <span class="pull-right badge bg-blue"><?php echo $cancel ?></span></a></li>
				<li><a href="javascript:void(0);" onclick="view_detail('REJECT');">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $reject ?></span></a></li>
			</ul>
		</div>
	</div>
</div>

<script>
function view_detail(status)
{
	$('#modalDetail').modal('show');
	$.ajax({
		url:"<?php echo site_url('incoming/cc_reg/detail_dsr'); ?>/" + status,
		type:'POST',
		data:$('#frmsave').serialize(),
		success:function(data){ 
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