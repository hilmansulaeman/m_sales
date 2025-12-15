<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
?>
<a href="<?= site_url('incoming/cc_ms') ?>" class="btn btn-danger">Back</a>
<br><br>
Status <?php echo $status; ?>
<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-body">
		
			<div class="table-responsive">
				<table class="table table-hover" style="font-size:10px;" id="example2">
					<thead>
						<th>Nama CH</th>
						<th>Status</th>
						<th>DSR</th>
						<th>SPV</th>
						<th>Input Date</th>
					</thead>
					<tbody>
					<?php
						foreach ($query as $data) {
					?>
						<tr>
							<td><?= masking_name($data->Customer_Name); ?></td>
							<td><?php echo $data->Status; ?></td>
							<td><?php echo $data->Sales_Name; ?></td>
							<td><?php echo $data->SPV_Name; ?></td>
							<td><?php echo $data->Created_Date2; ?></td>
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
/*$(document).ready(function() {
	$('#example2').DataTable({
			responsive: true,
			"paging" : true,
			"label" : false
	});
});*/
</script>