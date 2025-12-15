<div class="table-responsive">
  <table id="data-table-spv" class="table table-hover" width="100%">
	<thead>											
		<tr>				 											 				
			<th>No</th>
			<th>Sales Name</th>
			<th>Meeting Invite</th>
			<th>Total Hadir</th>
			<th>Total Tidak Hadir</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="6">Loading data from server</td>
		</tr>
	</tbody>
</table>					
</div>

<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-table-spv").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('meeting/presence_meeting/get_data_spv') ?>",
			type:'POST',
			data: function ( data ) {
          data.date_from = $('#date_from').val();
          data.date_to = $('#date_to').val();
      }
		},
		initComplete : function() {
			var input = $('#data-table-spv_filter input').unbind(),
				self = this.api(),
				searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
							.click(function() {
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