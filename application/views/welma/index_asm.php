<style>
#data-table-customer td {
    background:#4caf50;
}
</style>
<!-- MAIN CONTENT -->
<div id="ribbon">	
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Pemol</li>
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
		<h3 class="box-title">FORM ASM</h3>			  
	</div>
	<div class="panel-body">
		<form id="form_filter" method="post" class="smart-form" novalidate="novalidate">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-4">
					<h6 class="txt-color-blueDark">SPV	</h6>
						<select name="SPV" id="SPV" class="form-control" required >
							<option value="">--Pilih--</option>
							<?php foreach($getSPV as $row){?>
							<option value="<?php echo $row->DSR_Code; ?>"><?php echo $row->DSR_Code; ?> <?php echo $row->Name; ?></option>
							<?php } ?>
						</select>
					</div>
					<h6 class="txt-color-blueDark">Periode</h6>
					<label class="input"> 	
						<input type="date" name="date_from" value="<?php echo date('Y-m-01');?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
						<?php echo form_error('date_from'); ?>
					</label>
					<i class="icon-prepend fa fa- fa-calendar"></i>
					S/D
					<label class="input"> 							
						<input type="date" name="date_to" value="<?php echo date('Y-m-d');?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
						<?php echo form_error('date_to'); ?>
					</label>
					<i class="icon-prepend fa fa- fa-calendar"></i>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
				</div>
			</div>
		</form>
		&nbsp;
		&nbsp;
		<table id="data-table-customer" class="table table-hover" width="100%">
			<thead>											
				<tr>				 											 				
					<th>No</th>
					<th>Data Pemol</th>
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
    table = $("#data-table-customer").DataTable({
		ordering: false,
		//searching:false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
		  url: "<?php echo site_url('input/pemol/get_data') ?>",
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
        url : "<?php echo site_url('input/pemol/filter_data')?>",
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
</script> 