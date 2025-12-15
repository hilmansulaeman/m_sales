<p style="color:green;">
	<b>Periode : <?php echo date('d/m/Y',strtotime($this->session->userdata('date_from'))); ?> s/d <?php echo date('d/m/Y',strtotime($this->session->userdata('date_to'))); ?></b>
</p>
<div class="table-responsive">
  <table id="data-table-spv" class="table table-hover" width="100%">
	<thead>											
		<tr>				 											 				
			<th rowspan="2">No</th>
			<th rowspan="2">Sales Name</th>
			<th rowspan="2">Branch</th>
			<th rowspan="2">Total DSR <br> <small>(Active)</small></th>
			<th rowspan="2">Total DSR <br> <small>(Input)</small></th>
			<th colspan="3" style="text-align:center;">Input</th>
			<th rowspan="2">Action</th>
		</tr>
		<tr>
			<th>Mobile BCA</th>
			<th>My BCA</th>
			<th>Total</th>
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
    table = $("#data-table-spv").DataTable({
		ordering: false,
		//searching:false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('incoming/pemol/get_data_spv') ?>",
			type:'POST',
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