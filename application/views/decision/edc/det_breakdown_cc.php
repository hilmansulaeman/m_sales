<?php
	$uri = $this->uri->segment(3);
	$status = str_replace('_',' ', $uri);
?>
Status <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Name</th>
			<th>DSR</th>
			<th>Input Date</th>
		</thead>
		<tbody>
		<?php
			foreach ($query->result() as $data) {
		?>
			<tr>
				<td><?= masking_name($data->Customer_Name); ?></td>
				<td><?php echo $data->Name; ?></td>
				<td><?php echo $data->Created_Date2; ?></td>
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