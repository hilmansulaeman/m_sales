<?php
	$uri = $this->uri->segment(3);
?>
Status : <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<?php if($status == 'APPROVED' || $status == 'APPROVE'){ ?>
			<th>Tipe Kartu</th>
			<?php } ?>
			<th>DSR</th>
			<th>Date</th>
		</thead>
		<tbody>
		<?php 
			foreach($query->result() as $value)
			{
		?>
			<tr>
				<td>
				    <b><?php masking_name($value->Cust_Name); ?></b><br>
					<small>Decision Date: <?php echo $value->Date_Result; ?></small>
				</td>
				<?php if($status == 'APPROVED' || $status == 'APPROVE'){ ?>
				<td><?php echo $value->Note; ?></td>
				<?php } ?>
				<td><?php echo $value->Sales_Name; ?></td>
				<td><?php echo $value->Date_Result; ?></td>
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