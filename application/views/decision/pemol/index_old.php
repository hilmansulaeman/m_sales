<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Data Decision</li>
		<li>Pemol</li>
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
		<h3 class="box-title">Decision Pemol <?php echo $position; ?></h3>			  
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<a href="pemol/export"><button type="button" id="btn-export" class="btn btn-primary" style="padding:5px;"><i class="fa fa-file-excel-o"></i> Export Data</button></a>
				
				<span class="pull-right">
					<form id="form_filter" method="post" class="smart-form" novalidate="novalidate"> 		
						<table>
							<tr>											 																				
								<td><h6 class="txt-color-blueDark">Filter Periode &nbsp; </h6></td>	
								<td>
									<label class="input"> 	
											<!-- <i class="icon-prepend fa fa- fa-calendar"></i> -->
										<input type="text" name="group_date" value="<?php echo $this->session->userdata('groupDate');?>" data-dateformat='yy-mm'  class="form-control date-input" autocomplete="off" required/>
									<?php echo form_error('group_date'); ?>
									</label>	
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
								<td>
									<button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
								</td>																					
							</tr>						 
						</table>
					</form>
				</span>
			</div>
		</div>

		<?php
			$position_ = $this->session->userdata('position');
			$disallow_position = array('DSR');
			if(in_array($position,$disallow_position)){
				$control = '';
				$products= '';
				$branchs  = '';
				$column = '3';
			}
			else{
				$control = '<th>Action</th>';
				$products= '<th>Product</th>';
				$branchs  = '<th>Branch</th>';
				$column = '6';
			}
		?>
		<div class="table-responsive">
			<table id="data-table-customer" class="table table-hover" width="100%">
				<thead>											
					<tr>				 											 				
						<th>No</th>
						<th>Nama Sales</th>
						<th>OA</th>
						<th>SN</th>
						<th>SK</th>
						<th>SD</th>
						<th>KTB</th>
						<th>Total</th>
						<?php echo $control; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="<?php echo $column; ?>">Loading data from server</td>
					</tr>
				</tbody>
			</table>					
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalSPV">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<input type="hidden" name="names1" id="names1" value="">
				<input type="hidden" name="pos1" id="pos1" value="">
				<input type="hidden" name="sales1" id="sales1" value="">
				<input type="hidden" name="names2" id="names2" value="">
				<input type="hidden" name="names3" id="names3" value="">
				<p id="header-all"></p>
				<!-- <h4 class="modal-title">Summary Pemol <?php echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmspv" class="form-horizontal form-bordered">					 				 								
					<div id="pop"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
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
		url: "<?php echo site_url('decision/pemol/get_data') ?>",
		type:'POST',
		/*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
		},
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

function filter_data()
{
    var formData = new FormData($('#form_filter')[0]);
    $.ajax({
        url : "<?php echo site_url('decision/pemol/filter_data')?>",
        type: "POST",
		data: formData,
		contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            //if success reload to home page
            table.ajax.reload();
        }
    });
}

function view_spv(sales, pos, name)
{
	$('#modalSPV').modal('show');
	var names1 = $('#names1').val();
	var names2 = $('#names2').val();
	var names3 = $('#names3').val();

	if (names1 == "") {
		$('#names1').val(name);
		$('#pos1').val(pos);
		$('#sales1').val(sales);
		names1 = $('#names1').val();
		$('#header-all').html("<b>"+names1+"</b>");
	}else if(names2 == "" ) {
		$('#names2').val(name);
		names2 = $('#names2').val();
		names1 = $('#names1').val();
		pos1 = $('#pos1').val();
		sales1 = $('#sales1').val();

		$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> -> " + names2);
	}else{
		$('#names3').val(name);
		names3 = $('#names3').val();
		names1 = $('#names1').val();
		names2 = $('#names2').val();

		pos1 = $('#pos1').val();
		sales1 = $('#sales1').val();

		$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> -> " + names2 + " -> " + names3);
	}

	$.ajax({
		url:"<?php echo site_url('decision/pemol/detail'); ?>/" + sales +"/"+ pos,
		type:"POST",
		data:$("#frmspv").serialize(),
		success:function(data){ 
			$("#pop").html('');  
			$("#pop").append(data);  
		}  
	});
}

$('#modalSPV').on('hidden.bs.modal', function () {
	$('#names1').val(name);
	$('#names2').val(name);
	$('#names3').val(name);

	$('#pos1').val(name);
	$('#sales1').val(name);
});

function view_spv_click(sales, pos, names)
{
	// console.log(sales);
	// console.log(pos);
	$('#modalSPV').modal('show');
	$('#header-all').html("<b>"+names+"</b>");
	$('#names2').val("");
	$('#names3').val("");

	$.ajax({
		url:"<?php echo site_url('decision/pemol/detail'); ?>/" + sales + "/" + pos,
		type:"POST",
		data:$("#frmspv").serialize(),
		success:function(data){ 
			$("#pop").html('');  
			$("#pop").append(data);
		}  
	});
}
</script> 