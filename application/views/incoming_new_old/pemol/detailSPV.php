<?php
$disallow_position = array('DSR', 'SPV');
if (in_array($position, $disallow_position)) {
	$total_dsr = "";
	$column    = "11";
} else {
	$total_dsr = "<th>Total DSR <br> <small>(Active)</small></th>";
	$column    = "12";
}
?>
<p style="color:green;">
	<b>Periode : <?php echo date('d/m/Y', strtotime($this->session->userdata('date_from'))); ?> s/d <?php echo date('d/m/Y', strtotime($this->session->userdata('date_to'))); ?></b>
</p>
<div class="table-responsive">
	<!-- <table id="data-table-spv" class="table table-hover" width="100%"> -->
	<table class="table table-hover" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Sales</th>
				<th>Branch</th>
				<th>
					Total DSR <br />
					<small>(Active)</small>
				</th>
				<th>Total Input</th>
				<th>Send (BCA)</th>
				<th>Inprocess</th>
				<th>Duplicate</th>
				<th>RTS</th>
				<th>Cancel</th>
				<th>Reject</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<!-- <td colspan="12">Loading data from server</td> -->
				<td>1</td>
				<td>K123123, Husain (DSR)</td>
				<td>Jakarta</td>
				<td>0</td>
				<td>2</td>
				<td>1</td>
				<td>0</td>
				<td>1</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	var table2;
	$(document).ready(function() {
		table2 = $("#data-table-spv").DataTable({
			ordering: false,
			//searching:false,
			processing: true,
			serverSide: true,
			responsive: true,
			ajax: {
				url: "<?php echo site_url('incoming/cc_ms/get_data_spv') ?>",
				type: 'POST',
				/*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
			},
			initComplete: function() {
				var input = $('#data-table-spv_filter input').unbind(),
					self = this.api(),
					searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
					.click(function() {
						self.search(input.val()).draw();
					});
				$(document).keypress(function(event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
				$('#data-table-spv_filter').append(searchButton);
			}
		});
	});
</script>