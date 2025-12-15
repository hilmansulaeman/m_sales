<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Setup</a></li>
		<li class="breadcrumb-item active">Fix Schedule</li>
	</ol>
	
	<!-- begin page-header -->
	<h1 class="page-header">
		FIX SCHEDULE
		<small>&nbsp;</small>
	</h1>

	<div class="row">
		<div col class="col-lg-12 m-b-5">
			<?php if ($this->session->flashdata('message')) { ?>
			<div class="alert alert-success fade show">
			  <span class="close" data-dismiss="alert">×</span>
			  <strong>Success!</strong>
			  <?php echo $this->session->flashdata('message'); ?>
			</div>
			<?php }?>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-inverse" data-sortable-id="ui-widget-1">
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
					<h4 class="panel-title">View</h4>
				</div>
				<div class="panel-body">
					<table class="table table-hover" id="data-table-default">
						<thead>
							<tr>
								<td>No.</td>
								<td>Hari</td>
								<td>Product</td>
								<td>Aksi</td>
							</tr>
						</thead>
						<tbody>
						<?php
							$no = 0;
							foreach ($sql->result() as $row) {
						?>
							<tr>
								<td><?php echo ++$no; ?></td>
								<td><?php echo $row->Training_Day; ?></td>
								<td><?php echo $row->Product; ?></td>
								<td>
									<a href="javascript:void(0)" onclick="edit('<?php echo $row->Setup_ID ?>', '<?php echo $row->Training_Day ?>', '<?php echo $row->Product; ?>')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
								</td>
							</tr>
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function edit(id, day, product)
	{
		$('#modalEdit').modal('show');
		document.getElementById('setup_id').value = id;
		document.getElementById('hari').value = day;
		document.getElementById('product').value = product;
	}
</script>

!-- Modal Add -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Form Edit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo site_url(); ?>schedule/update_fix_schedule">
					<input type="hidden" name="setup_id" id="setup_id">
					<fieldset>
						<div class="form-group">
							<label class="col-form-label">Hari :</label>
							<div class="input-group">
								<input type="text" id="hari" name="hari" class="form-control" readonly="true" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Product :</label>
							<div class="input-group">
								<input type="text" id="product" name="product" class="form-control"/>
							</div>
						</div>
						<button type="submit" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default">Cancel</button>
						<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->