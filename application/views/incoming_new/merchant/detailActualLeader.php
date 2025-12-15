<?php
	$uri = $this->uri->segment(3);
	// $status = str_replace('_',' ', $part);
?>
<a href="<?= site_url('incoming/merchant') ?>" class="btn btn-danger">Back</a>
<br><br>
<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Data</h4>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-hover" style="font-size:10px;" id="example3">
					<thead>
						<th>Merchant Name</th>
						<th>Owner Name</th>
						<th>Sales Code</th>
						<th>Sales Name</th>
					</thead>
					<tbody>
					<?php
						foreach ($query as $data) {
					?>
						<tr>
							<td><?= masking_name($data->Merchant_Name); ?></td>
							<td><?php echo $data->Owner_Name; ?></td>
							<td><?php echo $data->Sales_Code; ?></td>
							<td><?php echo $data->Sales_Name; ?></td>
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

<script type="text/javascript">
// $(document).ready(function() {
// 	$('#example3').DataTable({
// 			responsive: true,
// 			"paging" : false,
// 			"label" : false
// 	});
// });
</script>