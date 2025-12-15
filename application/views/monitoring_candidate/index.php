<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Monitoring Candidate</li>
	</ol>	 
</div>

<div class="box box-primary">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-warning fade in">
		<button class="close" data-dismiss="alert" id="notif">
			×
		</button>
		<i class="fa-fw fa fa-check"></i>
		<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php }?>
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
			</button>
		</div>		
		<h3 class="box-title">Monitoring Candidate <?php echo $position; ?></h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="data-table-customer" class="table table-hover" width="100%">
				<thead>											
					<tr>
						<th>No</th>
						<th>Name</th>
						<th>Product</th>
						<th>Training</th>
						<th>Trainer</th>
						<th>Room</th>
						<th>Kehadiran</th>
						<th>Score</th>
						<th>Grades</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="9">Loading data from server</td>
					</tr>
				</tbody>
			</table>					
		</div>
	</div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-table-customer").DataTable({
		ordering: false,
		//searching:false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('monitoring_candidate/get_data') ?>",
			type:'POST',
		},
		// columnDefs: [{
		// 	targets: [0,3,4,5,6],
		// 	orderable: false,
		// }, ],

		initComplete : function() {
			var input = $('#data-table-customer_filter input').unbind(),
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
			$('#data-table-customer_filter').append(searchButton);
		}
	});
});
</script> 