<div class="table-responsive">
  <table id="data-table-schedule" class="table table-hover" width="100%">
	<thead>											
		<tr>				 											 				
	  <th>No.</th>
      <th>Tgl. Meeting</th>
	  <th>Pembuat Meeting</th>
      <th>Tema / Pembahasan</th>
      <th>Hari</th>
      <th>Tipe</th>
      <th>Lokasi</th>
      <th>Link</th>
      <th>Status</th> 
      <th>MOM</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="9">Loading data from server</td>
		</tr>
	</tbody>
</table>					
</div>

<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-table-schedule").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('meeting/monitoring/get_data_schedule_up') ?>",
			type:'POST',
			data: function ( data ) {
          data.date_from = $('#date_from').val();
          data.date_to = $('#date_to').val();
      }
		},
		initComplete : function() {
			var input = $('#data-table-schedule_filter input').unbind(),
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
			$('#data-table-schedule_filter').append(searchButton);
		}
	});
});
</script>