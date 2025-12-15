<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
?>
Status <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<th>Status</th>
			<th>DSR</th>
			<th>SPV</th>
			<th>Source Code</th>
			<th>Nama Pameran</th>
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
				<td><?php echo $data->Source_Code; ?></td>
				<td><?php echo $data->Project . $data->Position;  ?></td>
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
			"paging" : true,
			"label" : false
	});
});
</script>