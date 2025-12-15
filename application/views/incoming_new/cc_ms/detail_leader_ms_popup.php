<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
?>
Status SEND_MS
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<th>DSR</th>
			<th>SPV</th>
			<th>Input Date</th>
		</thead>
		<tbody>
		<?php
			foreach ($query as $data) {
		?>
			<tr>
				<td><?= masking_name($data->Cust_Name); ?></td>
				<td><?php echo $data->Sales_Name; ?></td>
				<td><?php echo $data->SPV_Name; ?></td>
				<td><?php echo $data->Created_Date; ?></td>
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
			"paging" : true,
			"label" : false
	});
});
</script>