<div class="table-responsive">
  <table id="data-table-participant" class="table table-hover" width="100%">
	<thead>											
		<tr>				 											 				
			<th>No.</th>
      <th>Nama</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">Loading data from server</td>
		</tr>
	</tbody>
</table>					
</div>

<script type="text/javascript">
var table_participant;
$(document).ready(function() {
    table_participant = $("#data-table-participant").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('meeting/monitoring/get_data_participant') ?>",
			type:'POST',
			data: function ( data ) {
          data.date_from = $('#date_from').val();
          data.date_to = $('#date_to').val();
      }
		},
		initComplete : function() {
			var input = $('#data-table-participant_filter input').unbind(),
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
			$('#data-table-participant_filter').append(searchButton);
		}
	});
});
</script>