<?php  

// $flag = $this->session->userdata('FL_pass');
// if ($flag != 0) {
// 	$checkTTD = check_ttd();
	
// 	if ($checkTTD['status']) {
// 		// if ($checkTTD['activity'] == 1) {
// 			echo "<script>alert('Anda harus melengkapi tanda tangan terlebih dahulu!!!');</script>";
// 		// }
// 	}
// }

?>

<?php if ($this->session->flashdata('msg')) : ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h5><i class="fa fa-ban"></i> Danger!</h5>
		<?= $this->session->flashdata('msg') ?>
	</div>
<?php endif; ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Data Addendum<small></small></h4> <?= $this->session->userdata('adendum_id') ?>
        <div class="box-tools pull-right">
        </div>
    </div>
    <div class="box-body">
		<table id="data-sales" class="table table-striped table-hover table-responsive">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th> Jenis </th>
					<th> Status </th>
					<th>action</th>
					
				</tr>
			</thead>
			<tbody>
				<td colspan="10">Loading data from server</td>
			</tbody>
		</table>
    </div>
    <div class="box-footer clearfix"></div>
</div>

<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-sales").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
		  url: "<?php echo site_url('addendum/get_all_data') ?>",
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