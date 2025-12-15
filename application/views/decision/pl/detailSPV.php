<div class="table-responsive">
	<table id="data-table-spv" class="table table-hover" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Sales</th>
				<th>Approve</th>
				<th>In Process</th>
				<th>Cancel</th>
				<th>Decline</th>
				<th>Action</th>
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
	var table2;
	$(document).ready(function () {
		table2 = $("#data-table-spv").DataTable({
			ordering: false,
			//searching:false,
			processing: true,
			serverSide: true,
			responsive: true,
			ajax: {
				url: "<?php echo site_url('decision/pl/get_data_spv') ?>",
				type: 'POST',
				/*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
			},
			initComplete: function () {
				var input = $('#data-table-spv_filter input').unbind(),
					self = this.api(),
					searchButton = $(
						'<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>'
						)
					.click(function () {
						self.search(input.val()).draw();
					});
				$(document).keypress(function (event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
				$('#data-table-spv_filter').append(searchButton);
			}
		});
	});
</script>