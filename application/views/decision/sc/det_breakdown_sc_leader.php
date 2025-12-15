<?php
	$uri = $this->uri->segment(3);
	$status = str_replace('_',' ', $uri);
?>
Status <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<th>DSR</th>
			<th>SPV</th>
			<th>Date</th>
		</thead>
		<tbody>
		<?php
			foreach ($query->result() as $data) {
		?>
			<tr>
				<td><?= masking_name($data->cust_name); ?></td>
				<td><?php echo $data->sales_name; ?></td>
				<td><?php echo $data->spv_name; ?></td>
				<td><?php echo $row->date_result; ?></td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#example2').DataTable({
			responsive: true,
			"paging" : false,
			"label" : false
	});
});
</script>