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
			<th>Date</th>
		</thead>
		<tbody>
		<?php
			foreach ($query->result() as $data) {
		?>
			<tr>
				<td><?php masking_name($row->Debitur_Name); ?></td>
				<td><?php echo $row->Sales_Name; ?></td>
				<td><?php echo $row->Date_Result; ?></td>
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