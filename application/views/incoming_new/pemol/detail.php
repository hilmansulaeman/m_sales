<p style="color:green;">
	<b>Periode : <?php echo $this->session->userdata('date_from'); ?></b>
</p>
<div class="table-responsive">
	<table id="data-table-spv" class="table table-hover" width="100%">
		<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Nama Sales</th>
				<th rowspan="2">Branch</th>
				<th rowspan="2">Total DSR</th>
				<th rowspan="2">Total Input (DSR)</th>
				<th colspan="2" style="text-align:center;">Input</th>
				<th rowspan="2">Total</th>
				<th rowspan="2">Action</th>
			</tr>
			<tr>
				<th>BCA Mobile</th>
				<th>My BCA</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="7">Loading data from server</td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	var table;
	$(document).ready(function() {
		table = $("#data-table-spv").DataTable({
			ordering: false,
			//searching:false,
			processing: true,
			serverSide: true,
			responsive: true,
			ajax: {
				url: "<?php echo site_url('incoming_new/pemol/get_data_spv') ?>",
				type: 'POST',
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