<style>
#data-table-customer td {
    background:#4caf50;
}
</style>
<!-- MAIN CONTENT -->
<div id="ribbon">
	<span class="ribbon-button-alignment"> 
		<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
			<i class="fa fa-refresh"></i>
		</span> 
	</span>	
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Qris</li>
		<li>Index</li>
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
		<h3 class="box-title">FORM <?php echo $this->uri->segment(4); ?> </h3>			  
	</div>
	<div class="panel-body">
		<form id="checkout-form" method="post" enctype="multipart/form-data" class="smart-form" onsubmit="return confirm('Data sudah benar?');" >
			<div class="row">
				<div class="col-md-6">
					<a href="<?php echo site_url('input/qris/add'); ?>" class="btn btn-header btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>Add Data</a>
				</div>
				<div class="col-md-6">
					<span class="pull-right">
					<h3 class="page-title txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> 
						DATA QRIS QRIS
					</h3>
				</span>	
				</div>
				<!-- <button onclick="topFunction()" id="myBtn" title="Go to top">UP</button>  -->
			</div>
		</form>
		<table id="data-qris" class="table table-hover" width="100%">
			<thead>											
				<tr>				 											 				
					<th>No</th>
					<th>Owner Name</th>
					<th>Merchant Name</th>
					<th>Mobile Phone Number</th>
					<th>MID Type</th>
					<th>Email</th>
					<th>Account Number</th>
					<th>Officer Code</th>
					<th>Status</th>
					<th>Actions</th>
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

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->

<script type="text/javascript">
	var table;
	$(document).ready(function() {
		table = $("#data-qris").DataTable({
			ordering: false,
			//searching:false,
			processing: true,
			serverSide: true,
			responsive:true,
			ajax: {
			url: "<?php echo site_url('input/qris/get_data_qris') ?>",
			type:'POST',
			/*data: function ( data ) {
					data.created_date = $('#created_date').val();
				}*/
			},
			initComplete : function() {
				var input = $('#data-qris input').unbind(),
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
				$('#data-qris_filter').append(searchButton);
			}
		});
	});
</script>