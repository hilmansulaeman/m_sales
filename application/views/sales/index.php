<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Data Sales<small></small></h4>
        <div class="box-tools pull-right">
        </div>
    </div>
    <div class="box-body">
		<table id="data-sales" class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>						
					<th align="center">Foto</th>		
					<th>Profile</th>	
					<th>Branch</th>
					<th>Position</th>
					<th>Product</th>									
					<th>Channel</th>								
					<th>Level</th>
					<th>SPV Name</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<td colspan="10">Loading data from server</td>
			</tbody>
		</table>
    </div>
    <div class="box-footer clearfix"></div>
</div>

<script src="<?php echo base_url() ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>public/mytemplate/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-sales").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
		  url: "<?php echo site_url('sales/get_data') ?>",
		  type:'POST',
		},
		initComplete : function() {
			var input = $('#data-sales_filter input').unbind(),
				self = this.api(),
				searchButton = $('<button id="btnSearch" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>')
						   .click(function() {
							  self.search(input.val()).draw();
						   });
			    $(document).keypress(function (event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
			$('#data-sales_filter').append(searchButton);
		}
	});
	$('#btn-filter').click(function(){ //button filter event click
		table.ajax.reload();  //just reload table
	});
});
</script>