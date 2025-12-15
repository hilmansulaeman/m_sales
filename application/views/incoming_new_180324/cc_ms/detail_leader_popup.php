<?php
// $uri = $this->uri->segment(3);
//$status = str_replace('_',' ', $part);
?>
Status
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama Customer</th>
			<th>Branch</th>
			<th>Status</th>
			<th>DSR</th>
			<th>SPV</th>
			<th>ASM</th>
			<th>RSM</th>
			<th>BSH</th>
			<th>Group Date</th>
			<th>Type</th>
			<th>Week</th>
		</thead>
		<tbody>
			<?php
			foreach ($query as $data) {
				?>
				<tr>
					<td><?= masking_name($data->Customer_Name); ?></td>
					<td><?php echo $data->Branch; ?></td>
					<td><?php echo $data->Status; ?></td>
					<td><?php echo $data->Sales_Name; ?></td>
					<td><?php echo $data->SPV_Name; ?></td>
					<td><?php echo $data->ASM_Name; ?></td>
					<td><?php echo $data->RSM_Name; ?></td>
					<td><?php echo $data->BSH_Name; ?></td>
					<td><?php echo $data->Group_Date; ?></td>
					<td><?php echo $data->Type; ?></td>
					<td><?php echo $data->Week; ?></td>
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
			"paging": true,
			"label": false
		});
	});
</script>