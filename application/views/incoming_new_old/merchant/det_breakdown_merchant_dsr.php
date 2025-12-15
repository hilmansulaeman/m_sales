<?php
$uri = $this->uri->segment(3);
$status = str_replace('_', ' ', $part);
?>
Status <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Merchant Name</th>
			<th>Owner Name</th>
			<th>Sales Code</th>
			<th>Sales Name</th>
		</thead>
		<tbody>
			<?php
			foreach ($query as $data) {
			?>
				<tr>
					<td><?= masking_name($data->Merchant_Name); ?></td>
					<!-- <td><?php echo $data->Merchant_Name; ?></td> -->
					<td><?php echo $data->Owner_Name; ?></td>
					<td><?php echo $data->Sales_Code; ?></td>
					<td><?php echo $data->Sales_Name; ?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	// $(document).ready(function() {
	// 	$('#example2').DataTable({
	// 		responsive: true,
	// 		// "paging" : true,
	// 		// "label" : false
	// 		lengthChange: true,
	// 		lengthMenu: [
	// 			[10, 25, 50, -1],
	// 			[10, 25, 50, 'All']
	// 		],

	// 		dom: 'Blfrtip',
	// 		buttons: [{
	// 			extend: 'excel',
	// 			className: 'btn btn-primary',
	// 			text: 'Export Excel',
	// 			filename: 'Data_Merchant',
	// 		}],

	// 	});
	// 	$('.buttons-excel').css('margin-bottom', '10px');
	// });

	$(document).ready(function() {
    var table = $('#example2').DataTable({
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