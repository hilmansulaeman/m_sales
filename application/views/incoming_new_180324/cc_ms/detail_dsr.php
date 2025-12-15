<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
?>

<p>Status <?php echo $status; ?></p>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Nama CH</th>
			<th>Status pop up</th>
			<th>Input Date</th>
			<th>Nama Pameran</th>
			<th>Source Code</th>
		</thead>
		<tbody>
		<?php
			foreach ($query as $data) {
		?>
			<tr>
				<td><?= masking_name($data->Customer_Name); ?></td>
				<td><?php echo $data->Status; ?></td>
				<td><?php echo $data->Created_Date2; ?></td>
				<td><?php echo $data->Project; ?></td>
				<td><?php echo $data->Source_Code; ?></td>
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
			// responsive: true,
			// "paging" : true,
			// "label" : false
			responsive: true,
			lengthChange: true,
			lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50,'All']
			],
			dom: 'Blfrtip',
			buttons: [{
					extend: 'excel',
					className: 'btn btn-primary',
					text: 'Export Excel',
					filename: 'Data_Merchant',
			}],
	});

	//jika tidak ada data, button tidak dishow
	table.on('draw.dt', function() {
        var recordsTotal = table.page.info().recordsTotal;
        if (recordsTotal === 0) {
            $('.buttons-excel').hide();
        } else {
            $('.buttons-excel').show();
        }
    });

    var initialRecordsTotal = table.page.info().recordsTotal;
    if (initialRecordsTotal === 0) {
        $('.buttons-excel').hide();
    } else {
        $('.buttons-excel').show();
    }

	//jaraj button export dengan pagination
    $('.buttons-excel').css('margin-bottom', '10px');
});
</script>