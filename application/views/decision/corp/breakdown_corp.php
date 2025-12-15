<?php
	$uri = $this->uri->segment(3);
	// $status = str_replace('_',' ', $uri);
?>
Status : <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<th>Perusahaan</th>
			<th>DSR</th>
			<th>Date</th>
		</thead>
		<tbody>
		<?php
			foreach($query->result() as $row)
			{
		?>
			<tr>
				<td>
				    <b><?php masking_name($row->customer_name); ?></b><br>
					<small>Decision Date: <?php echo $row->date_result; ?></small>
				</td>
				<td><?php echo $row->company_name; ?></td>
				<td><?php echo $row->sales_name; ?></td>
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
				"paging" : true,
                "label" : false
        });
    });
</script>