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
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Merchant EDC</li>
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
		<h3 class="box-title">FORM 
			<?php 
				$uri_4_ = $this->uri->segment(4);
				$uri_4 = str_replace('_', ' ', $uri_4_);
				echo $uri_4; 
			?> 
		</h3>
	</div>
	<div class="panel-body">
		<form id="checkout-form" method="post" enctype="multipart/form-data" class="smart-form" onsubmit="return confirm('Data sudah benar?');" >
			<div class="row">
				<div class="col-md-6">
					<a href="<?php echo site_url('input/merchant_edc/add'); ?>" class="btn btn-header btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>Input Merchant EDC</a>
				</div>
				<div class="col-md-6">
					<span class="pull-right">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> 
						DATA MERCHANT EDC
					</h1>
				</span>	
				</div>
				<!-- <button onclick="topFunction()" id="myBtn" title="Go to top">UP</button>  -->
			</div>
		</form>
			
		<table id="table-data" class="table table-bordered table-striped" width="100%">
			<thead>											
				<tr>				 											 				
					<th width="1%"></th>
					<th>Jenis Pengajuan</th>
					<th>Status Merchant</th>
					<th>Nama Owner</th>
					<th>Nama Merchant</th>
					<th>No. Rekening</th>
					<th>No. Handphone</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2">Loading data from server</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<script type="text/javascript">
var table;
$(document).ready(function() {
	table = $("#table-data").DataTable({
		ordering: false,
		//searching:false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
		url: "<?php echo site_url('input/merchant_edc/get_data_merchant') ?>",
		type:'POST',
		/*data: function ( data ) {
				data.created_date = $('#created_date').val();
			}*/
		},
		initComplete : function() {
			var input = $('#table-data_filter input').unbind(),
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
			$('#table-data_filter').append(searchButton);
		}
	});
});

// var table;
// $(document).ready(function() {
//     table = $("#data-merchant").DataTable({
//         ordering: false,
//         //searching:false,
//         processing: true,
//         serverSide: true,
//         responsive:true,
//         ajax: {
// 			url: "<?php echo site_url('sales/merchant_edc/get_data_merchant') ?>",
// 			type:'POST',
// 			/*data: function ( data ) {
// 					data.created_date = $('#created_date').val();
// 				}*/
//         },
//         initComplete : function() {
//             var input = $('#data-merchant_filter input').unbind(),
//                 self = this.api(),
//                 searchButton = $('<button id="btnSearch" class="btn btn-default active"><i class="fa fa-search"></i></button>')
// 						.click(function() {
// 							self.search(input.val()).draw();
// 						});
//                 $(document).keypress(function (event) {
//                     if (event.which == 13) {
//                         searchButton.click();
//                     }
//                 });
//             $('#data-merchant_filter').append(searchButton);
//         }
//     });
// });
</script>