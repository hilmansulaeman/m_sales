<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Bucket</a></li>
		<li class="breadcrumb-item active">Schedule</li>
	</ol>
	
	<!-- begin page-header -->
	<h1 class="page-header">
		SCHEDULE
		<small>&nbsp;</small>
	</h1>
	<!-- end page-header -->
	
	<!-- begin row -->
	<div class="row">
		<div class="col-lg-12 m-b-5">
			<a href="<?php echo site_url(); ?>schedule/list_schedule" class="btn btn-info btn-sm">Lihat Daftar Schedule</a>
		</div>
		<div class="col-lg-12">
			<div id="calendar" class="vertical-box-column calendar"></div>
		</div>
	</div>
</div>
<script>
	function AddNew()
	{
		$('#modalAdd').modal('show');
	}
	
	function ShowDetail()
	{
		$('#modalDetail').modal('show');
	}
</script>
<!-- Modal Log -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Form Input</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form>
					<fieldset>
						<div class="form-group">
							<label for="modules">Modules :</label>
							<input type="modules" class="form-control" id="modules" placeholder="Type Youre New Modules" />
						</div>
						<button type="submit" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default">Cancel</button>
						<button type="submit" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-primary m-r-5">Save</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Log -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-eye"></i> Detail Data</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-hover">
					<tbody>
						<tr>
							<td>Modules :</td>
						</tr>
						<tr>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->